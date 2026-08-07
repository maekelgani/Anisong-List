<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Song;
use App\Models\Franchise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use App\Exports\SongsExport;
use App\Imports\SongsImport;
use Maatwebsite\Excel\Facades\Excel;

class SongController extends Controller
{
    public function index(Request $request)
    {
        return redirect()->route('admin.songs.type', 'opening');
    }

    public function indexByType(Request $request, $tipe)
    {
        $allowedTypes = ['opening', 'ending', 'movie'];
        if (!in_array($tipe, $allowedTypes)) {
            $tipe = 'opening';
        }

        $query = Song::with('franchise')->where('tipe', $tipe);

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul_lagu', 'like', "%{$search}%")
                  ->orWhere('penyanyi', 'like', "%{$search}%")
                  ->orWhere('anime_name', 'like', "%{$search}%");
            });
        }

        if ($request->has('franchise_id') && $request->franchise_id != '') {
            $query->where('franchise_id', $request->franchise_id);
        }

        $songs = $query->orderBy('peringkat')->paginate(10)->appends($request->query());
        $franchises = Franchise::orderBy('nama')->get();

        return view('admin.songs.index', compact('songs', 'tipe', 'franchises'));
    }

    public function create()
    {
        $franchises = Franchise::all();
        return view('admin.songs.create', compact('franchises'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_lagu' => 'required|string|max:255',
            'penyanyi' => 'required|string|max:255',
            'tipe' => 'required|in:opening,ending,movie',
            'peringkat' => 'required|integer|min:1|max:100',
            'tahun_rilis' => 'required|integer',
        ]);

        $data = $request->all();
        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
        }

        DB::transaction(function () use ($data) {
            Song::where('tipe', $data['tipe'])
                ->where('peringkat', '>=', $data['peringkat'])
                ->increment('peringkat');
            
            Song::create($data);
        });

        return redirect()->route('admin.songs.type', $request->tipe)->with('success', 'Lagu berhasil ditambahkan.');
    }

    public function edit(Song $song)
    {
        $franchises = Franchise::all();
        return view('admin.songs.edit', compact('song', 'franchises'));
    }

    public function update(Request $request, Song $song)
    {
        $request->validate([
            'judul_lagu' => 'required|string|max:255',
            'penyanyi' => 'required|string|max:255',
            'tipe' => 'required|in:opening,ending,movie',
            'peringkat' => 'required|integer|min:1|max:100',
            'tahun_rilis' => 'required|integer',
        ]);

        $oldRank = $song->peringkat;
        $newRank = $request->peringkat;
        $oldType = $song->tipe;
        $newType = $request->tipe;

        DB::transaction(function () use ($song, $oldRank, $newRank, $oldType, $newType, $request) {
            $data = $request->all();
            if ($request->hasFile('cover_image')) {
                if ($song->cover_image) {
                    Storage::disk('public')->delete($song->cover_image);
                }
                $data['cover_image'] = $request->file('cover_image')->store('covers', 'public');
            }

            if ($oldType === $newType) {
                if ($newRank != $oldRank) {
                    if ($newRank < $oldRank) {
                        Song::where('tipe', $newType)->whereBetween('peringkat', [$newRank, $oldRank - 1])->increment('peringkat');
                    } else {
                        Song::where('tipe', $newType)->whereBetween('peringkat', [$oldRank + 1, $newRank])->decrement('peringkat');
                    }
                }
            } else {
                Song::where('tipe', $oldType)->where('peringkat', '>', $oldRank)->decrement('peringkat');
                Song::where('tipe', $newType)->where('peringkat', '>=', $newRank)->increment('peringkat');
            }
            
            $song->update($data);
        });

        return redirect()->route('admin.songs.type', $request->tipe)->with('success', 'Lagu berhasil diperbarui.');
    }

    public function destroy(Song $song)
    {
        $tipe = $song->tipe;
        if ($song->cover_image) {
            Storage::disk('public')->delete($song->cover_image);
        }
        
        DB::transaction(function () use ($song) {
            $oldRank = $song->peringkat;
            Song::where('tipe', $song->tipe)->where('peringkat', '>', $oldRank)->decrement('peringkat');
            $song->delete();
        });

        return redirect()->route('admin.songs.type', $tipe)->with('success', 'Lagu berhasil dihapus.');
    }

    // Ajax Reorder
    public function reorder(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:songs,id',
            'new_rank' => 'required|integer|min:1|max:100'
        ]);

        $song = Song::findOrFail($request->id);
        $oldRank = $song->peringkat;
        $newRank = $request->new_rank;
        $tipe = $song->tipe;

        if ($oldRank == $newRank) {
            return response()->json(['success' => true]);
        }

        DB::transaction(function () use ($song, $oldRank, $newRank, $tipe) {
            if ($newRank < $oldRank) {
                Song::where('tipe', $tipe)->whereBetween('peringkat', [$newRank, $oldRank - 1])->increment('peringkat');
            } else {
                Song::where('tipe', $tipe)->whereBetween('peringkat', [$oldRank + 1, $newRank])->decrement('peringkat');
            }
            $song->update(['peringkat' => $newRank]);
        });

        return response()->json(['success' => true]);
    }

    public function export()
    {
        return Excel::download(new SongsExport(false), 'anisong_database.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,xls|max:10240',
        ]);

        try {
            Excel::import(new SongsImport, $request->file('file'));
            return back()->with('success', 'Data lagu dari file berhasil diimpor!');
        } catch (\Exception $e) {
            return back()->withErrors(['file' => 'Gagal mengimpor data: Pastikan format file sesuai. Error: ' . $e->getMessage()]);
        }
    }

    public function downloadTemplate()
    {
        return Excel::download(new SongsExport(true), 'template_import_songs.xlsx');
    }

    public function deleteAll()
    {
        Song::query()->delete();
        return back()->with('success', 'Semua lagu berhasil dihapus dari sistem.');
    }
}

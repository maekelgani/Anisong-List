<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Song;
use App\Models\Franchise;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SongController extends Controller
{
    public function index(Request $request)
    {
        $query = Song::with('franchise')->orderBy('peringkat');

        if ($request->has('tipe') && $request->tipe != '') {
            $query->where('tipe', $request->tipe);
        }

        $songs = $query->paginate(20);
        return view('admin.songs.index', compact('songs'));
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

        Song::create($request->all());

        return redirect()->route('admin.songs.index')->with('success', 'Lagu berhasil ditambahkan.');
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

        DB::transaction(function () use ($song, $oldRank, $newRank, $request) {
            if ($newRank != $oldRank) {
                if ($newRank < $oldRank) {
                    Song::whereBetween('peringkat', [$newRank, $oldRank - 1])->increment('peringkat');
                } else {
                    Song::whereBetween('peringkat', [$oldRank + 1, $newRank])->decrement('peringkat');
                }
            }
            $song->update($request->all());
        });

        return redirect()->route('admin.songs.index')->with('success', 'Lagu berhasil diperbarui.');
    }

    public function destroy(Song $song)
    {
        $song->delete();
        return redirect()->route('admin.songs.index')->with('success', 'Lagu berhasil dihapus.');
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

        if ($oldRank == $newRank) {
            return response()->json(['success' => true]);
        }

        DB::transaction(function () use ($song, $oldRank, $newRank) {
            if ($newRank < $oldRank) {
                Song::whereBetween('peringkat', [$newRank, $oldRank - 1])->increment('peringkat');
            } else {
                Song::whereBetween('peringkat', [$oldRank + 1, $newRank])->decrement('peringkat');
            }
            $song->update(['peringkat' => $newRank]);
        });

        return response()->json(['success' => true]);
    }
}

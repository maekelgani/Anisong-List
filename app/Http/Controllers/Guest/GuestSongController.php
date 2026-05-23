<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\Song;
use Illuminate\Http\Request;
use Inertia\Inertia;

class GuestSongController extends Controller
{
    public function index()
    {
        return Inertia::render('Guest/List');
    }

    public function getSongs(Request $request)
    {
        $query = Song::with('franchise')->orderBy('peringkat');

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('judul_lagu', 'like', "%{$search}%")
                  ->orWhere('penyanyi', 'like', "%{$search}%")
                  ->orWhere('anime_name', 'like', "%{$search}%")
                  ->orWhereHas('franchise', function($q) use ($search) {
                      $q->where('nama', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('tahun') && $request->tahun != '') {
            $query->where('tahun_rilis', $request->tahun);
        }

        if ($request->has('tipe') && $request->tipe != '') {
            $query->where('tipe', $request->tipe);
        }

        return response()->json($query->get());
    }
}

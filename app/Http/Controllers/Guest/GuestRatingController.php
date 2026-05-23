<?php

namespace App\Http\Controllers\Guest;

use App\Http\Controllers\Controller;
use App\Models\GuestRatingSession;
use App\Models\GuestRatingDetail;
use App\Models\Song;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class GuestRatingController extends Controller
{
    public function index()
    {
        // For the rate wizard, we need the initial data, we can pass all songs
        // or just let the Vue component fetch what it needs. We'll pass all songs ordered by rank.
        $songs = Song::with('franchise')->orderBy('peringkat')->get();
        return Inertia::render('Guest/RateWizard', [
            'songs' => $songs
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_guest' => 'required|string|max:255',
            'tipe_rate' => 'required|in:opening,ending,movie,all',
            'limit_top' => 'required|in:10,25,50,100',
            'komentar_guest' => 'nullable|string',
            'ratings' => 'required|array',
            'ratings.*.song_id' => 'required|exists:songs,id',
            'ratings.*.score' => 'required|numeric|min:0|max:10',
        ]);

        DB::transaction(function () use ($request) {
            $ratings = collect($request->ratings);
            $avgScore = $ratings->avg('score');

            $session = GuestRatingSession::create([
                'nama_guest' => $request->nama_guest,
                'tipe_rate' => $request->tipe_rate,
                'limit_top' => $request->limit_top,
                'rata_rata_score' => $avgScore,
                'komentar_guest' => $request->komentar_guest,
            ]);

            foreach ($ratings as $rating) {
                GuestRatingDetail::create([
                    'session_id' => $session->id,
                    'song_id' => $rating['song_id'],
                    'score_diberikan' => $rating['score'],
                ]);
            }
        });

        return redirect()->route('home')->with('success', 'Terima kasih telah memberikan rating!');
    }
}

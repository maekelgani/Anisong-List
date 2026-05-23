<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuestRatingSession;
use Illuminate\Http\Request;

class GuestRateAdminController extends Controller
{
    public function index()
    {
        // Get all sessions with their basic info
        $sessions = GuestRatingSession::latest()->paginate(15);
        return view('admin.guest_rates.index', compact('sessions'));
    }

    public function show(GuestRatingSession $guest_rate)
    {
        // Eager load details, songs, and franchises to prevent N+1
        $guest_rate->load('details.song.franchise');

        // Sort details by the score given (descending) or by the original song rank
        $details = $guest_rate->details->sortByDesc('score_diberikan');

        return view('admin.guest_rates.show', compact('guest_rate', 'details'));
    }
}

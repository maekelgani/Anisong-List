<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GuestRatingSession;
use Illuminate\Http\Request;

class GuestRateAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = GuestRatingSession::query();

        // Search by nama
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_guest', 'like', '%' . $request->search . '%');
        }

        // Filter by tipe_rate
        if ($request->has('tipe_rate') && $request->tipe_rate != '') {
            $query->where('tipe_rate', $request->tipe_rate);
        }

        // Filter by limit_top
        if ($request->has('limit_top') && $request->limit_top != '') {
            $query->where('limit_top', $request->limit_top);
        }

        // Order
        $order = $request->input('order', 'newest');
        if ($order === 'highest') {
            $query->orderBy('rata_rata_score', 'desc');
        } elseif ($order === 'lowest') {
            $query->orderBy('rata_rata_score', 'asc');
        } else {
            // Default to newest
            $query->latest();
        }

        $sessions = $query->paginate(15)->appends($request->query());
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

    public function destroy(GuestRatingSession $guest_rate)
    {
        // Because of constraints, it's safer to delete details first if cascade isn't set up
        $guest_rate->details()->delete();
        $guest_rate->delete();

        return redirect()->route('admin.guest_rates.index')->with('success', 'Sesi rating guest berhasil dihapus.');
    }
}

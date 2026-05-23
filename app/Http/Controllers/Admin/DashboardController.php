<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Song;
use App\Models\GuestRatingSession;
use App\Models\Franchise;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $totalLagu = Song::count();
        $totalGuest = GuestRatingSession::count();
        $avgScore = GuestRatingSession::avg('rata_rata_score') ?? 0;

        $topSingers = Song::select('penyanyi', DB::raw('count(*) as total'))
            ->groupBy('penyanyi')
            ->orderByDesc('total')
            ->take(10)
            ->get();

        $topFranchises = Franchise::withCount('songs')
            ->orderByDesc('songs_count')
            ->take(10)
            ->get();

        $trendTahun = Song::select('tahun_rilis', DB::raw('count(*) as total'))
            ->groupBy('tahun_rilis')
            ->orderBy('tahun_rilis')
            ->get();

        return view('admin.dashboard', compact(
            'totalLagu',
            'totalGuest',
            'avgScore',
            'topSingers',
            'topFranchises',
            'trendTahun'
        ));
    }
}

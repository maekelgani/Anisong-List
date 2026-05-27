<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\FranchiseController;
use App\Http\Controllers\Admin\SongController;
use App\Http\Controllers\Admin\GuestRateAdminController;
use App\Http\Controllers\Guest\GuestSongController;
use App\Http\Controllers\Guest\GuestRatingController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Middleware\AdminMiddleware;

// ----------------------------------------------------
// GUEST PANEL (SPA VUE.JS + INERTIA)
// ----------------------------------------------------

// Home / Portofolio
Route::get('/', function () {
    $lastUpdate = \App\Models\Song::max('updated_at');
    $lastUpdateFormatted = $lastUpdate ? \Carbon\Carbon::parse($lastUpdate)->translatedFormat('d F Y') : 'Belum ada update';
    return Inertia::render('Guest/Home', [
        'canLogin' => Route::has('login'),
        'lastUpdate' => $lastUpdateFormatted
    ]);
})->name('home');

// Lihat List Top 100
Route::get('/list', function() {
    $lastUpdate = \App\Models\Song::max('updated_at');
    $lastUpdateFormatted = $lastUpdate ? \Carbon\Carbon::parse($lastUpdate)->translatedFormat('d F Y') : 'Belum ada update';
    return Inertia::render('Guest/List', [
        'lastUpdate' => $lastUpdateFormatted
    ]);
})->name('guest.list');
Route::get('/api/songs', [GuestSongController::class, 'getSongs']); // API endpoint for filtering/search

// Rate List Wizard
Route::get('/rate', [GuestRatingController::class, 'index'])->name('guest.rate');
Route::post('/rate/submit', [GuestRatingController::class, 'store'])->name('guest.rate.submit');


// ----------------------------------------------------
// ADMIN PANEL (BLADE)
// ----------------------------------------------------

Route::middleware(['auth', AdminMiddleware::class])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::resource('franchises', FranchiseController::class);
    
    Route::post('/songs/reorder', [SongController::class, 'reorder'])->name('songs.reorder');
    Route::get('/songs/export', [SongController::class, 'export'])->name('songs.export');
    Route::post('/songs/import', [SongController::class, 'import'])->name('songs.import');
    Route::get('/songs/template', [SongController::class, 'downloadTemplate'])->name('songs.template');
    
    Route::get('/songs/type/{tipe}', [SongController::class, 'indexByType'])->name('songs.type');
    Route::resource('songs', SongController::class)->except(['show']);
    
    Route::get('/guest-rates', [GuestRateAdminController::class, 'index'])->name('guest_rates.index');
    Route::get('/guest-rates/{guest_rate}', [GuestRateAdminController::class, 'show'])->name('guest_rates.show');
    Route::delete('/guest-rates/{guest_rate}', [GuestRateAdminController::class, 'destroy'])->name('guest_rates.destroy');
});


// ----------------------------------------------------
// DEFAULT BREEZE AUTH
// ----------------------------------------------------

Route::get('/dashboard', function () {
    // Redirect authenticated user depending on role
    if (auth()->user()->is_admin) {
        return redirect()->route('admin.dashboard');
    }
    return redirect('/');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';

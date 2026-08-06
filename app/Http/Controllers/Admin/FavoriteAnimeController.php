<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FavoriteAnime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FavoriteAnimeController extends Controller
{
    public function index()
    {
        $favoriteAnimes = FavoriteAnime::latest()->paginate(10);
        return view('admin.favorite-animes.index', compact('favoriteAnimes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'studio' => 'nullable|string|max:255',
            'release_year' => 'nullable|integer',
            'rating' => 'nullable|string|max:10',
        ]);

        $data = $request->except('cover_image');

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('favorite-animes', 'public');
        }

        FavoriteAnime::create($data);

        return redirect()->route('admin.favorite-animes.index')->with('success', 'Anime favorite berhasil ditambahkan.');
    }

    public function update(Request $request, FavoriteAnime $favoriteAnime)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'studio' => 'nullable|string|max:255',
            'release_year' => 'nullable|integer',
            'rating' => 'nullable|string|max:10',
        ]);

        $data = $request->except('cover_image');

        if ($request->hasFile('cover_image')) {
            // Hapus gambar lama
            if ($favoriteAnime->cover_image && Storage::disk('public')->exists($favoriteAnime->cover_image)) {
                Storage::disk('public')->delete($favoriteAnime->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('favorite-animes', 'public');
        }

        $favoriteAnime->update($data);

        return redirect()->route('admin.favorite-animes.index')->with('success', 'Anime favorite berhasil diperbarui.');
    }

    public function destroy(FavoriteAnime $favoriteAnime)
    {
        if ($favoriteAnime->cover_image && Storage::disk('public')->exists($favoriteAnime->cover_image)) {
            Storage::disk('public')->delete($favoriteAnime->cover_image);
        }
        $favoriteAnime->delete();

        return redirect()->route('admin.favorite-animes.index')->with('success', 'Anime favorite berhasil dihapus.');
    }
}

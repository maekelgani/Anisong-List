<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FavoriteManga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FavoriteMangaController extends Controller
{
    public function index()
    {
        $favoriteMangas = FavoriteManga::latest()->paginate(10);
        return view('admin.favorite-mangas.index', compact('favoriteMangas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'author' => 'nullable|string|max:255',
            'release_year' => 'nullable|integer',
            'status' => 'nullable|string|max:50',
        ]);

        $data = $request->except('cover_image');

        if ($request->hasFile('cover_image')) {
            $data['cover_image'] = $request->file('cover_image')->store('favorite-mangas', 'public');
        }

        FavoriteManga::create($data);

        return redirect()->route('admin.favorite-mangas.index')->with('success', 'Manga favorite berhasil ditambahkan.');
    }

    public function update(Request $request, FavoriteManga $favoriteManga)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'author' => 'nullable|string|max:255',
            'release_year' => 'nullable|integer',
            'status' => 'nullable|string|max:50',
        ]);

        $data = $request->except('cover_image');

        if ($request->hasFile('cover_image')) {
            if ($favoriteManga->cover_image && Storage::disk('public')->exists($favoriteManga->cover_image)) {
                Storage::disk('public')->delete($favoriteManga->cover_image);
            }
            $data['cover_image'] = $request->file('cover_image')->store('favorite-mangas', 'public');
        }

        $favoriteManga->update($data);

        return redirect()->route('admin.favorite-mangas.index')->with('success', 'Manga favorite berhasil diperbarui.');
    }

    public function destroy(FavoriteManga $favoriteManga)
    {
        if ($favoriteManga->cover_image && Storage::disk('public')->exists($favoriteManga->cover_image)) {
            Storage::disk('public')->delete($favoriteManga->cover_image);
        }
        $favoriteManga->delete();

        return redirect()->route('admin.favorite-mangas.index')->with('success', 'Manga favorite berhasil dihapus.');
    }
}

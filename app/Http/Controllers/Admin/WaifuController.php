<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Waifu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WaifuController extends Controller
{
    public function index()
    {
        $waifus = Waifu::latest()->paginate(10);
        return view('admin.waifus.index', compact('waifus'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'anime_title' => 'nullable|string|max:255',
            'image_path' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except('image_path');

        if ($request->hasFile('image_path')) {
            $data['image_path'] = $request->file('image_path')->store('waifus', 'public');
        }

        Waifu::create($data);

        return redirect()->route('admin.waifus.index')->with('success', 'Waifu berhasil ditambahkan.');
    }

    public function update(Request $request, Waifu $waifu)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'anime_title' => 'nullable|string|max:255',
            'image_path' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $data = $request->except('image_path');

        if ($request->hasFile('image_path')) {
            if ($waifu->image_path && Storage::disk('public')->exists($waifu->image_path)) {
                Storage::disk('public')->delete($waifu->image_path);
            }
            $data['image_path'] = $request->file('image_path')->store('waifus', 'public');
        }

        $waifu->update($data);

        return redirect()->route('admin.waifus.index')->with('success', 'Waifu berhasil diperbarui.');
    }

    public function destroy(Waifu $waifu)
    {
        if ($waifu->image_path && Storage::disk('public')->exists($waifu->image_path)) {
            Storage::disk('public')->delete($waifu->image_path);
        }
        $waifu->delete();

        return redirect()->route('admin.waifus.index')->with('success', 'Waifu berhasil dihapus.');
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Franchise;
use Illuminate\Http\Request;

class FranchiseController extends Controller
{
    public function index()
    {
        $franchises = Franchise::withCount('songs')->latest()->paginate(10);
        return view('admin.franchises.index', compact('franchises'));
    }

    public function create()
    {
        return view('admin.franchises.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:franchises,nama'
        ]);

        Franchise::create($request->all());

        return redirect()->route('admin.franchises.index')->with('success', 'Franchise berhasil ditambahkan.');
    }

    public function edit(Franchise $franchise)
    {
        return view('admin.franchises.edit', compact('franchise'));
    }

    public function update(Request $request, Franchise $franchise)
    {
        $request->validate([
            'nama' => 'required|string|max:255|unique:franchises,nama,' . $franchise->id
        ]);

        $franchise->update($request->all());

        return redirect()->route('admin.franchises.index')->with('success', 'Franchise berhasil diperbarui.');
    }

    public function destroy(Franchise $franchise)
    {
        $franchise->delete();
        return redirect()->route('admin.franchises.index')->with('success', 'Franchise berhasil dihapus.');
    }
}

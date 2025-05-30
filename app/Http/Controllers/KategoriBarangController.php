<?php

namespace App\Http\Controllers;

use App\Models\KategoriBarang;
use Illuminate\Http\Request;

class KategoriBarangController extends Controller
{
    public function index()
    {
        $kategori = KategoriBarang::all();
        return response()->json($kategori);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
        ]);

        $kategori = KategoriBarang::create($validated);
        return response()->json($kategori, 201);
    }

    public function show($id)
    {
        $kategori = KategoriBarang::findOrFail($id);
        return response()->json($kategori);
    }

    public function update(Request $request, $id)
    {
        $kategori = KategoriBarang::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:100',
        ]);

        $kategori->update($validated);
        return response()->json($kategori);
    }

    public function destroy($id)
    {
        $kategori = KategoriBarang::findOrFail($id);
        $kategori->delete();
        return response()->json(null, 204);
    }
}
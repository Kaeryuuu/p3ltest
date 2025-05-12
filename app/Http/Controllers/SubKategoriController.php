<?php

namespace App\Http\Controllers;

use App\Models\Subkategori;
use Illuminate\Http\Request;

class SubKategoriController extends Controller
{
    public function index()
    {
        $subkategori = Subkategori::all();
        return response()->json($subkategori);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_kategori' => 'required|exists:kategoribarang,id_kategori',
            'namaSubKategori' => 'required|string|max:100',
        ]);

        $subkategori = SubKategori::create($validated);
        return response()->json($subkategori, 201);
    }

    public function show($id)
    {
        $subkategori = SubKategori::findOrFail($id);
        return response()->json($subkategori);
    }

    public function update(Request $request, $id)
    {
        $subkategori = SubKategori::findOrFail($id);
        $validated = $request->validate([
            'id_kategori' => 'sometimes|required|exists:kategoribarang,id_kategori',
            'namaSubKategori' => 'sometimes|required|string|max:100',
        ]);

        $subkategori->update($validated);
        return response()->json($subkategori);
    }

    public function destroy($id)
    {
        $subkategori = SubKategori::findOrFail($id);
        $subkategori->delete();
        return response()->json(null, 204);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Diskusi;
use Illuminate\Http\Request;

class DiskusiController extends Controller
{
    public function index()
    {
        $diskusi = Diskusi::all();
        return response()->json($diskusi);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tanggal_dibuat' => 'nullable|date',
            'isi' => 'nullable|string',
            'id_pembeli' => 'required|exists:pembeli,id_pembeli',
            'kode_barang' => 'required|exists:barangtitipan,kode_barang',
        ]);

        $diskusi = Diskusi::create($validated);
        return response()->json($diskusi, 201);
    }

    public function show($id)
    {
        $diskusi = Diskusi::findOrFail($id);
        return response()->json($diskusi);
    }

    public function update(Request $request, $id)
    {
        $diskusi = Diskusi::findOrFail($id);
        $validated = $request->validate([
            'tanggal_dibuat' => 'sometimes|nullable|date',
            'isi' => 'sometimes|nullable|string',
            'id_pembeli' => 'sometimes|required|exists:pembeli,id_pembeli',
            'kode_barang' => 'sometimes|required|exists:barangtitipan,kode_barang',
        ]);

        $diskusi->update($validated);
        return response()->json($diskusi);
    }

    public function destroy($id)
    {
        $diskusi = Diskusi::findOrFail($id);
        $diskusi->delete();
        return response()->json(null, 204);
    }
}
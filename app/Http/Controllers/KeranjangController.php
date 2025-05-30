<?php

namespace App\Http\Controllers;

use App\Models\Keranjang;
use Illuminate\Http\Request;

class KeranjangController extends Controller
{
    public function index()
    {
        $keranjang = Keranjang::all();
        return response()->json($keranjang);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pembeli' => 'required|exists:pembeli,id_pembeli',
        ]);

        $keranjang = Keranjang::create($validated);
        return response()->json($keranjang, 201);
    }

    public function show($id)
    {
        $keranjang = Keranjang::findOrFail($id);
        return response()->json($keranjang);
    }

    public function update(Request $request, $id)
    {
        $keranjang = Keranjang::findOrFail($id);
        $validated = $request->validate([
            'id_pembeli' => 'sometimes|required|exists:pembeli,id_pembeli',
        ]);

        $keranjang->update($validated);
        return response()->json($keranjang);
    }

    public function destroy($id)
    {
        $keranjang = Keranjang::findOrFail($id);
        $keranjang->delete();
        return response()->json(null, 204);
    }
}
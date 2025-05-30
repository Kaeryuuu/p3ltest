<?php

namespace App\Http\Controllers;

use App\Models\Komisi;
use Illuminate\Http\Request;

class KomisiController extends Controller
{
    public function index()
    {
        $komisi = Komisi::all();
        return response()->json($komisi);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'bonus_hunter' => 'nullable|numeric',
            'bonus_mart' => 'nullable|numeric',
            'id_pembelian' => 'required|exists:transaksipembelian,id_pembelian',
            'bonus_penitip' => 'nullable|numeric',
        ]);

        $komisi = Komisi::create($validated);
        return response()->json($komisi, 201);
    }

    public function show($id)
    {
        $komisi = Komisi::findOrFail($id);
        return response()->json($komisi);
    }

    public function update(Request $request, $id)
    {
        $komisi = Komisi::findOrFail($id);
        $validated = $request->validate([
            'bonus_hunter' => 'sometimes|nullable|numeric',
            'bonus_mart' => 'sometimes|nullable|numeric',
            'id_pembelian' => 'sometimes|required|exists:transaksipembelian,id_pembelian',
            'bonus_penitip' => 'sometimes|nullable|numeric',
        ]);

        $komisi->update($validated);
        return response()->json($komisi);
    }

    public function destroy($id)
    {
        $komisi = Komisi::findOrFail($id);
        $komisi->delete();
        return response()->json(null, 204);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Donasi;
use Illuminate\Http\Request;

class DonasiController extends Controller
{
    public function index()
    {
        $donasi = Donasi::all();
        return response()->json($donasi);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_request' => 'required|exists:requestdonasi,id_request',
            'tanggal_permintaan' => 'nullable|date',
            'status' => 'nullable|string|max:20',
        ]);

        $donasi = Donasi::create($validated);
        return response()->json($donasi, 201);
    }

    public function show($id)
    {
        $donasi = Donasi::findOrFail($id);
        return response()->json($donasi);
    }

    public function update(Request $request, $id)
    {
        $donasi = Donasi::findOrFail($id);
        $validated = $request->validate([
            'id_request' => 'sometimes|required|exists:requestdonasi,id_request',
            'tanggal_permintaan' => 'sometimes|nullable|date',
            'status' => 'sometimes|nullable|string|max:20',
        ]);

        $donasi->update($validated);
        return response()->json($donasi);
    }

    public function destroy($id)
    {
        $donasi = Donasi::findOrFail($id);
        $donasi->delete();
        return response()->json(null, 204);
    }
}
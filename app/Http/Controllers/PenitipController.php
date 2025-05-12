<?php

namespace App\Http\Controllers;

use App\Models\Penitip;
use Illuminate\Http\Request;

class PenitipController extends Controller
{
    public function index()
    {
        $penitip = Penitip::all();
        return response()->json($penitip);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_penitip' => 'required|string|max:11|unique:penitip,id_penitip',
            'nama' => 'required|string|max:100',
            'telepon' => 'required|string|max:15',
            'email' => 'required|email|max:100|unique:penitip,email',
            'poin_loyalitas' => 'nullable|integer',
            'password' => 'required|string|max:255',
            'url_foto' => 'nullable|string|max:255',
        ]);

        $penitip = Penitip::create($validated);
        return response()->json($penitip, 201);
    }

    public function show($id)
    {
        $penitip = Penitip::findOrFail($id);
        return response()->json($penitip);
    }

    public function update(Request $request, $id)
    {
        $penitip = Penitip::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:100',
            'telepon' => 'sometimes|required|string|max:15',
            'email' => 'sometimes|required|email|max:100|unique:penitip,email,' . $id . ',id_penitip',
            'poin_loyalitas' => 'sometimes|nullable|integer',
            'password' => 'sometimes|required|string|max:255',
            'url_foto' => 'sometimes|nullable|string|max:255',
        ]);

        $penitip->update($validated);
        return response()->json($penitip);
    }

    public function destroy($id)
    {
        $penitip = Penitip::findOrFail($id);
        $penitip->delete();
        return response()->json(null, 204);
    }
}
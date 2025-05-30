<?php

namespace App\Http\Controllers;

use App\Models\Alamat;
use Illuminate\Http\Request;

class AlamatController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $alamat = Alamat::all();
        return response()->json($alamat);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'kecamatan' => 'nullable|string|max:100',
            'kode_pos' => 'nullable|string|max:10',
            'alamat_utama' => 'nullable|string',
            'id_pembeli' => 'required|exists:pembeli,id_pembeli',
        ]);

        $alamat = Alamat::create($validated);
        return response()->json($alamat, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $alamat = Alamat::findOrFail($id);
        return response()->json($alamat);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $alamat = Alamat::findOrFail($id);
        $validated = $request->validate([
            'kecamatan' => 'sometimes|nullable|string|max:100',
            'kode_pos' => 'sometimes|nullable|string|max:10',
            'alamat_utama' => 'sometimes|nullable|string',
            'id_pembeli' => 'sometimes|required|exists:pembeli,id_pembeli',
        ]);

        $alamat->update($validated);
        return response()->json($alamat);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $alamat = Alamat::findOrFail($id);
        $alamat->delete();
        return response()->json(null, 204);
    }
}
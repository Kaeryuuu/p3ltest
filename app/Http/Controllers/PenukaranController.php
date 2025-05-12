<?php

namespace App\Http\Controllers;

use App\Models\Penukaran;
use Illuminate\Http\Request;

class PenukaranController extends Controller
{
    public function index()
    {
        $penukaran = Penukaran::all();
        return response()->json($penukaran);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pembeli' => 'required|exists:pembeli,id_pembeli',
            'kode_penukaran' => 'required|string|max:50',
            'tanggal_penukaran' => 'nullable|date',
            'id_merchandise' => 'required|exists:merchandise,id_merchandise',
        ]);

        $penukaran = Penukaran::create($validated);
        return response()->json($penukaran, 201);
    }

    public function show($id)
    {
        $penukaran = Penukaran::findOrFail($id);
        return response()->json($penukaran);
    }

    public function update(Request $request, $id)
    {
        $penukaran = Penukaran::findOrFail($id);
        $validated = $request->validate([
            'id_pembeli' => 'sometimes|required|exists:pembeli,id_pembeli',
            'kode_penukaran' => 'sometimes|required|string|max:50',
            'tanggal_penukaran' => 'sometimes|nullable|date',
            'id_merchandise' => 'sometimes|required|exists:merchandise,id_merchandise',
        ]);

        $penukaran->update($validated);
        return response()->json($penukaran);
    }

    public function destroy($id)
    {
        $penukaran = Penukaran::findOrFail($id);
        $penukaran->delete();
        return response()->json(null, 204);
    }
}
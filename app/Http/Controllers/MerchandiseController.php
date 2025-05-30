<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use Illuminate\Http\Request;

class MerchandiseController extends Controller
{
    public function index()
    {
        $merchandise = Merchandise::all();
        return response()->json($merchandise);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'stok' => 'required|integer',
            'url_gambar' => 'nullable|string|max:255',
            'poin_dibutuhkan' => 'required|integer',
        ]);

        $merchandise = Merchandise::create($validated);
        return response()->json($merchandise, 201);
    }

    public function show($id)
    {
        $merchandise = Merchandise::findOrFail($id);
        return response()->json($merchandise);
    }

    public function update(Request $request, $id)
    {
        $merchandise = Merchandise::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:100',
            'deskripsi' => 'sometimes|nullable|string',
            'stok' => 'sometimes|required|integer',
            'url_gambar' => 'sometimes|nullable|string|max:255',
            'poin_dibutuhkan' => 'sometimes|required|integer',
        ]);

        $merchandise->update($validated);
        return response()->json($merchandise);
    }

    public function destroy($id)
    {
        $merchandise = Merchandise::findOrFail($id);
        $merchandise->delete();
        return response()->json(null, 204);
    }
}
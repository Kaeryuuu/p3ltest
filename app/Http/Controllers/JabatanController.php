<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatan = Jabatan::all();
        return response()->json($jabatan);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:50',
        ]);

        $jabatan = Jabatan::create($validated);
        return response()->json($jabatan, 201);
    }

    public function show($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        return response()->json($jabatan);
    }

    public function update(Request $request, $id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:50',
        ]);

        $jabatan->update($validated);
        return response()->json($jabatan);
    }

    public function destroy($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();
        return response()->json(null, 204);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use Illuminate\Http\Request;

class PegawaiController extends Controller
{
    public function index()
    {
        $pegawai = Pegawai::all();
        return response()->json($pegawai);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pegawai' => 'required|string|max:11|unique:pegawai,id_pegawai',
            'id_jabatan' => 'required|exists:jabatan,id_jabatan',
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:pegawai,email',
            'password' => 'required|string|max:255',
            'status' => 'nullable|string|max:20',
        ]);

        $pegawai = Pegawai::create($validated);
        return response()->json($pegawai, 201);
    }

    public function show($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        return response()->json($pegawai);
    }

    public function update(Request $request, $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $validated = $request->validate([
            'id_jabatan' => 'sometimes|required|exists:jabatan,id_jabatan',
            'nama' => 'sometimes|required|string|max:100',
            'email' => 'sometimes|required|email|max:100|unique:pegawai,email,' . $id . ',id_pegawai',
            'password' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|nullable|string|max:20',
        ]);

        $pegawai->update($validated);
        return response()->json($pegawai);
    }

    public function destroy($id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->delete();
        return response()->json(null, 204);
    }
}
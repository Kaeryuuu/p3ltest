<?php

namespace App\Http\Controllers;

use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class OrganisasiController extends Controller
{
    public function index()
    {
        $organisasi = Organisasi::all();
        return response()->json($organisasi);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'email' => 'required|email|max:255|unique:organisasi,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $lastOrganisasi = Organisasi::orderBy('id_organisasi', 'desc')->first();

        if ($lastOrganisasi) {
            $lastNumber = (int) str_replace('ORG', '', $lastOrganisasi->id_organisasi);
        } else {
            $lastNumber = 0;
        }

        $newId = 'ORG' . ($lastNumber + 1);

        // Persiapkan data
        $validated['id_organisasi'] = $newId;
        $validated['password'] = Hash::make($validated['password']);

        Organisasi::create($validated);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat, silahkan login.');
    }

    public function show($id)
    {
        $organisasi = Organisasi::findOrFail($id);
        return response()->json($organisasi);
    }

    public function update(Request $request, $id)
    {
        $organisasi = Organisasi::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:100',
            'deskripsi' => 'sometimes|nullable|string',
        ]);

        $organisasi->update($validated);
        return response()->json($organisasi);
    }

    public function destroy($id)
    {
        $organisasi = Organisasi::findOrFail($id);
        $organisasi->delete();
        return response()->json(null, 204);
    }
}

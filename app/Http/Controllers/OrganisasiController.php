<?php

namespace App\Http\Controllers;

use App\Models\Organisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class OrganisasiController extends Controller
{
    public function index()
    {
        $organisasi = Organisasi::all();
        return response()->json($organisasi);
    }

    public function registerORG(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'deskripsi' => 'nullable|string',
            'email' => 'required|email|max:255|unique:organisasi,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $lastOrganisasi = Organisasi::orderBy('id_organisasi', 'desc')->first();
            $lastNumber = $lastOrganisasi ? (int) str_replace('ORG', '', $lastOrganisasi->id_organisasi) : 0;
            $newId = 'ORG' . ($lastNumber + 1);

            $organisasi = Organisasi::create([
                'id_organisasi' => $newId,
                'nama' => $validated['nama'],
                'deskripsi' => $validated['deskripsi'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            Auth::guard('organisasi')->login($organisasi);

            return redirect()->route('organisasi.dashboard')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            \Log::error('Organisasi registration failed: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Registration failed: ' . $e->getMessage()]);
        }
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
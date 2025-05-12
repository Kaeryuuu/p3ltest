<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use Illuminate\Http\Request;

class PembeliController extends Controller
{
    public function index()
    {
        $pembeli = Pembeli::all();
        return response()->json($pembeli);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'telepon' => 'required|string|max:15',
            'email' => 'required|email|max:100|unique:pembeli,email',
            'password' => 'required|string|max:255',
        ]);

        $lastPembeli = Pembeli::orderBy('id_pembeli', 'desc')->first();

        $newId = $lastPembeli ? $lastPembeli->id_pembeli + 1 : 1;
        $validated['id_pembeli'] = $newId;
        $validated['password'] = bcrypt($validated['password']);

        Pembeli::create($validated);

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat, silahkan login.');
    }

    public function show($id)
    {
        $pembeli = Pembeli::findOrFail($id);
        return response()->json($pembeli);
    }

    public function update(Request $request, $id)
    {
        $pembeli = Pembeli::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:100',
            'telepon' => 'sometimes|required|string|max:15',
            'email' => 'sometimes|required|email|max:100|unique:pembeli,email,' . $id . ',id_pembeli',
            'password' => 'sometimes|required|string|max:255',
        ]);

        $pembeli->update($validated);
        return response()->json($pembeli);
    }

    public function destroy($id)
    {
        $pembeli = Pembeli::findOrFail($id);
        $pembeli->delete();
        return response()->json(null, 204);
    }
}

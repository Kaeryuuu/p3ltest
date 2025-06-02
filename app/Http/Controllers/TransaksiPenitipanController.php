<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPenitipan;
use Illuminate\Http\Request;

class TransaksiPenitipanController extends Controller
{
    public function index()
    {
        $transaksi = TransaksiPenitipan::all();
        return response()->json($transaksi);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_penitip' => 'required|exists:penitip,id_penitip',
            'catatan' => 'nullable|string',
            'tanggal_konfirmasi_ambil' => 'nullable|date',        
            'tanggal_diambil' => 'nullable|date',
            'tanggal_penitipan' => 'nullable|date',
            'id_pegawai' => 'required|exists:pegawai,id_pegawai', 
            'no_nota' => 'nullable|string'  

        ]);

        $transaksi = TransaksiPenitipan::create($validated);
        return response()->json($transaksi, 201);
    }

    public function show($id)
    {
        $transaksi = TransaksiPenitipan::findOrFail($id);
        return response()->json($transaksi);
    }

    public function update(Request $request, $id)
    {
        $transaksi = TransaksiPenitipan::findOrFail($id);
        $validated = $request->validate([
            'id_penitip' => 'sometimes|required|exists:penitip,id_penitip',
            'catatan' => 'sometimes|nullable|string',
            'tanggal_penitipan' => 'sometimes|nullable|date',
            'id_pegawai' => 'sometimes|required|exists:pegawai,id_pegawai',
        ]);

        $transaksi->update($validated);
        return response()->json($transaksi);
    }

    public function destroy($id)
    {
        $transaksi = TransaksiPenitipan::findOrFail($id);
        $transaksi->delete();
        return response()->json(null, 204);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\TransaksiPembelian;
use Illuminate\Http\Request;

class TransaksiPembelianController extends Controller
{
    public function index()
    {
        $transaksi = TransaksiPembelian::all();
        return response()->json($transaksi);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'id_pembeli' => 'required|exists:pembeli,id_pembeli',
            'total' => 'required|numeric',
            'tanggal_pembelian' => 'nullable|date',
            'bukti_pembayaran' => 'nullable|string|max:50',
            'tanggal_pengiriman' => 'nullable|date',
            'status' => 'nullable|string|max:20',
            'kode_barang' => 'required|exists:barangtitipan,kode_barang',
            'metode_pengiriman' => 'nullable|string|max:11',
        ]);

        $transaksi = TransaksiPembelian::create($validated);
        return response()->json($transaksi, 201);
    }

    public function show($id)
    {
        $transaksi = TransaksiPembelian::findOrFail($id);
        return response()->json($transaksi);
    }

    public function update(Request $request, $id)
    {
        $transaksi = TransaksiPembelian::findOrFail($id);
        $validated = $request->validate([
            'id_pembeli' => 'sometimes|required|exists:pembeli,id_pembeli',
            'total' => 'sometimes|required|numeric',
            'tanggal_pembelian' => 'sometimes|nullable|date',
            'bukti_pembayaran' => 'sometimes|nullable|string|max:50',
            'tanggal_pengiriman' => 'sometimes|nullable|date',
            'status' => 'sometimes|nullable|string|max:20',
            'kode_barang' => 'sometimes|required|exists:barangtitipan,kode_barang',
            'metode_pengiriman' => 'sometimes|nullable|string|max:11',
        ]);

        $transaksi->update($validated);
        return response()->json($transaksi);
    }

    public function destroy($id)
    {
        $transaksi = TransaksiPembelian::findOrFail($id);
        $transaksi->delete();
        return response()->json(null, 204);
    }
}
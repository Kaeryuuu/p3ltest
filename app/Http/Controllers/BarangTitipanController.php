<?php

namespace App\Http\Controllers;

use App\Models\BarangTitipan;
use Illuminate\Http\Request;

class BarangTitipanController extends Controller
{
    public function index()
    {
        $barang = BarangTitipan::all();
        return response()->json($barang);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kode_barang' => 'required|string|max:11|unique:barangtitipan,kode_barang',
            'nama' => 'required|string|max:100',
            'harga' => 'required|numeric',
            'berat' => 'required|numeric',
            'status_barang' => 'required|string|max:11',
            'tanggal_kadaluarsa' => 'nullable|date',
            'deskripsi' => 'nullable|string',
            'id_kategori' => 'required|exists:kategoribarang,id_kategori',
            'id_penitip' => 'required|exists:penitip,id_penitip',
            'perpanjangan' => 'required|boolean',
            'garansi' => 'nullable|date',
            'id_pembelian' => 'nullable|exists:transaksipembelian,id_pembelian',
        ]);

        $barang = BarangTitipan::create($validated);
        return response()->json($barang, 201);
    }

    public function show($id)
    {
        $barang = BarangTitipan::findOrFail($id);
        return response()->json($barang);
    }

    public function update(Request $request, $id)
    {
        $barang = BarangTitipan::findOrFail($id);
        $validated = $request->validate([
            'nama' => 'sometimes|required|string|max:100',
            'harga' => 'sometimes|required|numeric',
            'berat' => 'sometimes|required|numeric',
            'status_barang' => 'sometimes|required|string|max:11',
            'tanggal_kadaluarsa' => 'sometimes|nullable|date',
            'deskripsi' => 'sometimes|nullable|string',
            'id_kategori' => 'sometimes|required|exists:kВідатарibarang,id_kategori',
            'id_penitip' => 'sometimes|required|exists:penitip,id_penitip',
            'perpanjangan' => 'sometimes|required|boolean',
            'garansi' => 'sometimes|nullable|date',
            'id_pembelian' => 'sometimes|nullable|exists:transaksipembelian,id_pembelian',
        ]);

        $barang->update($validated);
        return response()->json($barang);
    }

    public function destroy($id)
    {
        $barang = BarangTitipan::findOrFail($id);
        $barang->delete();
        return response()->json(null, 204);
    }
}
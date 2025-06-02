<?php

namespace App\Http\Controllers;

use App\Models\BarangTitipan;
use App\Models\BarangTitipanFoto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class BarangTitipanController extends Controller
{
    public function index()
    {
        $barang = BarangTitipan::with('fotos')->get();
        return response()->json($barang);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'kode_barang' => 'required|string|max:11|unique:barangtitipan,kode_barang',
            'nama' => 'required|string|max:100',
            'harga' => 'required|numeric',
            'berat' => 'required|numeric',
            'status' => 'required|string|max:11',
            'kondisi' => 'required|string',
            'tanggal_kadaluarsa' => 'nullable|date',
            'deskripsi' => 'nullable|string',
            'id_kategori' => 'required|exists:kategoribarang,id_kategori',
            'id_penitip' => 'required|exists:penitip,id_penitip',
            'perpanjangan' => 'required|boolean',
            'garansi' => 'nullable|date',
            'id_pembelian' => 'nullable|exists:transaksipembelian,id_pembelian',
            'id_subkategori' => 'required|exists:subkategori,id_subkategori',
            'id_penitipan' => 'nullable|exists:transaksipenitipan,id_penitipan',
            'fotos' => 'nullable|array',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        $barangTitipanData = $validatedData;
        unset($barangTitipanData['fotos']);

        $barang = BarangTitipan::create($barangTitipanData);
        Log::info('BarangTitipan created', ['kode_barang' => $barang->kode_barang]);

        if ($request->hasFile('fotos')) {
            Log::info('Processing uploaded photos for BarangTitipan', ['kode_barang' => $barang->kode_barang]);
            foreach ($request->file('fotos') as $index => $file) {
                try {
                    $path = $file->store('photos/barang_titipan', 'public');
                    $barang->fotos()->create([
                        'url_foto' => $path,
                        'urutan' => $index + 1,
                    ]);
                    Log::info('BarangTitipanFoto record created', ['kode_barang' => $barang->kode_barang, 'url_foto' => $path]);
                } catch (\Exception $e) {
                    Log::error('Failed to process one of the photos', [
                        'kode_barang' => $barang->kode_barang,
                        'filename' => $file->getClientOriginalName(),
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        $barang->load('fotos');
        return response()->json($barang, 201);
    }

    public function show($id)
    {
        $barang = BarangTitipan::with('fotos')->findOrFail($id);
        return response()->json($barang);
    }

    public function update(Request $request, $id)
    {
        $barang = BarangTitipan::findOrFail($id);

        $validatedData = $request->validate([
            'nama' => 'sometimes|required|string|max:100',
            'harga' => 'sometimes|required|numeric',
            'berat' => 'sometimes|required|numeric',
            'status' => 'sometimes|required|string|max:11',
            'tanggal_kadaluarsa' => 'sometimes|nullable|date',
            'deskripsi' => 'sometimes|nullable|string',
            'id_kategori' => 'sometimes|required|exists:kategoribarang,id_kategori',
            'id_penitip' => 'sometimes|required|exists:penitip,id_penitip',
            'perpanjangan' => 'sometimes|required|boolean',
            'garansi' => 'sometimes|nullable|date',
            'id_pembelian' => 'sometimes|nullable|exists:transaksipembelian,id_pembelian',
            'id_penitipan' => 'sometimes|nullable|exists:transaksipenitipan,id_penitipan',
            'fotos' => 'nullable|array',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'hapus_fotos' => 'nullable|array',
            'hapus_fotos.*' => 'integer|exists:barang_titipan_fotos,id'
        ]);

        $updateData = $validatedData;
        unset($updateData['fotos']);
        unset($updateData['hapus_fotos']);

        $barang->update($updateData);
        Log::info('BarangTitipan updated', ['kode_barang' => $barang->kode_barang]);

        if ($request->filled('hapus_fotos')) {
            $idsFotoDihapus = $request->input('hapus_fotos');
            Log::info('Attempting to delete photos', ['ids' => $idsFotoDihapus, 'kode_barang' => $barang->kode_barang]);
            foreach ($idsFotoDihapus as $idFoto) {
                $foto = BarangTitipanFoto::where('kode_barang', $barang->kode_barang)->find($idFoto);
                if ($foto) {
                    Storage::disk('public')->delete($foto->url_foto);
                    $foto->delete();
                    Log::info('Photo deleted', ['id_foto' => $idFoto, 'url_foto' => $foto->url_foto]);
                }
            }
        }

        if ($request->hasFile('fotos')) {
            Log::info('Processing new uploaded photos for update', ['kode_barang' => $barang->kode_barang]);
            $lastUrutan = $barang->fotos()->max('urutan') ?? 0;
            foreach ($request->file('fotos') as $index => $file) {
                try {
                    $path = $file->store('photos/barang_titipan', 'public');
                    $barang->fotos()->create([
                        'url_foto' => $path,
                        'urutan' => $lastUrutan + $index + 1,
                    ]);
                    Log::info('New photo added during update', ['kode_barang' => $barang->kode_barang, 'url_foto' => $path]);
                } catch (\Exception $e) {
                    Log::error('Failed to process one of the new photos during update', [
                        'kode_barang' => $barang->kode_barang,
                        'filename' => $file->getClientOriginalName(),
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        $barang->load('fotos');
        return response()->json($barang);
    }

    public function destroy($id)
    {
        $barang = BarangTitipan::with('fotos')->findOrFail($id);
        Log::info('Attempting to delete BarangTitipan', ['kode_barang' => $barang->kode_barang]);

        foreach ($barang->fotos as $foto) {
            try {
                Storage::disk('public')->delete($foto->url_foto);
                Log::info('Physical photo file deleted', ['url_foto' => $foto->url_foto]);
            } catch (\Exception $e) {
                Log::error('Failed to delete physical photo file', ['url_foto' => $foto->url_foto, 'error' => $e->getMessage()]);
            }
        }

        $barang->delete();
        Log::info('BarangTitipan deleted', ['kode_barang' => $id]);
        return response()->json(null, 204);
    }

    public function showPickupForm($kode_barang)
    {
        $barang = BarangTitipan::where('kode_barang', $kode_barang)->firstOrFail();
        return view('gudang.barang-titipan.pickup-form', compact('barang'));
    }
}
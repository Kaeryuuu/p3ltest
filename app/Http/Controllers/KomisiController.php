<?php

namespace App\Http\Controllers;

use App\Models\Komisi;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Diperlukan untuk validasi unik saat update

class KomisiController extends Controller
{
    public function index()
    {
        // Anda mungkin ingin memuat relasi juga
        // $komisi = Komisi::with(['pegawai', 'penitip', 'barangTitipan'])->get();
        $komisi = Komisi::all();
        return response()->json($komisi);
    }

    public function store(Request $request)
    {
        // Sesuaikan aturan validasi dengan kolom tabel 'komisi'
        $validated = $request->validate([
            'kode_barang' => [
                'required',
                'string',
                'max:255',
                Rule::unique('komisi', 'kode_barang'), // Pastikan kode_barang unik di tabel komisi
                // Anda juga mungkin ingin memastikan kode_barang ini ada di tabel barangtitipan
                // 'exists:barangtitipan,kode_barang'
            ],
            'id_pegawai' => 'nullable|string|max:255|exists:pegawai,id_pegawai', // Pastikan id_pegawai ada di tabel pegawai
            'id_penitip' => 'required|string|max:255|exists:penitip,id_penitip', // Pastikan id_penitip ada di tabel penitip
            'komisi_hunter' => 'nullable|integer',
            'komisi_mart' => 'nullable|integer',
            'komisi_penitip' => 'nullable|integer',
            'bonus' => 'nullable|integer',
        ]);

        $komisi = Komisi::create($validated);
        return response()->json($komisi, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param Komisi $komisi Menggunakan Route Model Binding dengan primary key 'kode_barang'
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Komisi $komisi) // Menggunakan Route Model Binding
    {
        // Jika Anda tidak menggunakan Route Model Binding, atau jika primary key bukan 'id'
        // $komisi = Komisi::findOrFail($kode_barang);
        // Pastikan route Anda didefinisikan dengan parameter yang benar, misal: Route::get('/komisi/{komisi}', ...)
        // di mana {komisi} akan menjadi 'kode_barang'
        return response()->json($komisi->load(['pegawai', 'penitip', 'barangTitipan']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param Komisi $komisi Menggunakan Route Model Binding
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Komisi $komisi) // Menggunakan Route Model Binding
    {
        // $komisi = Komisi::findOrFail($kode_barang_dari_route);
        $validated = $request->validate([
            // kode_barang biasanya tidak diupdate karena merupakan primary key.
            // Jika Anda mengizinkan update kode_barang (tidak disarankan untuk PK):
            // 'kode_barang' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('komisi', 'kode_barang')->ignore($komisi->kode_barang, 'kode_barang'), 'exists:barangtitipan,kode_barang'],

            'id_pegawai' => 'sometimes|nullable|string|max:255|exists:pegawai,id_pegawai',
            'id_penitip' => 'sometimes|required|string|max:255|exists:penitip,id_penitip',
            'komisi_hunter' => 'sometimes|nullable|integer',
            'komisi_mart' => 'sometimes|nullable|integer',
            'komisi_penitip' => 'sometimes|nullable|integer',
            'bonus' => 'sometimes|nullable|integer',
        ]);

        $komisi->update($validated);
        return response()->json($komisi);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Komisi $komisi Menggunakan Route Model Binding
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Komisi $komisi) // Menggunakan Route Model Binding
    {
        // $komisi = Komisi::findOrFail($kode_barang_dari_route);
        $komisi->delete();
        return response()->json(null, 204);
    }
}
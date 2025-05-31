<?php

namespace App\Http\Controllers;

use App\Models\BarangTitipan;
use App\Models\BarangTitipanFoto; // Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Tambahkan ini
use Illuminate\Support\Facades\Log; // Untuk logging

class BarangTitipanController extends Controller
{
    public function index()
    {
        // Eager load fotos relationship
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
            'id_kategori' => 'required|exists:kategoribarang,id_kategori', // Pastikan nama tabel kategoribarang benar
            'id_penitip' => 'required|exists:penitip,id_penitip',
            'perpanjangan' => 'required|boolean',
            'garansi' => 'nullable|date',
            'id_pembelian' => 'nullable|exists:transaksipembelian,id_pembelian',
             'id_subkategori' => 'required|exists:subkategori,id_subkategori', 
            // 'url_foto' sudah dihapus, jadi validasinya juga dihapus

            // Validasi untuk multiple foto (array of files)
            'fotos' => 'nullable|array', // 'fotos' adalah nama input field untuk file
            'fotos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048' // Validasi untuk setiap file dalam array
        ]);

        // Buat BarangTitipan terlebih dahulu (tanpa url_foto lama)
        $barangTitipanData = $validatedData;
        unset($barangTitipanData['fotos']); // Hapus 'fotos' dari data untuk BarangTitipan

        $barang = BarangTitipan::create($barangTitipanData);
        Log::info('BarangTitipan created', ['kode_barang' => $barang->kode_barang]);

        // Proses upload dan simpan multiple foto jika ada
        if ($request->hasFile('fotos')) {
            Log::info('Processing uploaded photos for BarangTitipan', ['kode_barang' => $barang->kode_barang]);
            foreach ($request->file('fotos') as $index => $file) {
                try {
                    // Simpan file ke storage, misalnya 'public/photos/barang_titipan'
                    // Path akan menjadi sesuatu seperti 'photos/barang_titipan/namafileunik.jpg'
                    $path = $file->store('photos/barang_titipan', 'public');
                    Log::info('Photo stored', ['path' => $path]);

                    // Simpan informasi foto ke tabel barang_titipan_fotos
                    $barang->fotos()->create([
                        'url_foto' => $path, // Simpan path relatif terhadap disk 'public'
                        'urutan' => $index + 1, // Atur urutan berdasarkan index upload
                    ]);
                    Log::info('BarangTitipanFoto record created', ['kode_barang' => $barang->kode_barang, 'url_foto' => $path]);

                } catch (\Exception $e) {
                    Log::error('Failed to process one of the photos', [
                        'kode_barang' => $barang->kode_barang,
                        'filename' => $file->getClientOriginalName(),
                        'error' => $e->getMessage()
                    ]);
                    // Anda bisa memutuskan untuk melanjutkan atau mengembalikan error di sini
                }
            }
        }

        // Load relasi fotos untuk respons JSON
        $barang->load('fotos');

        return response()->json($barang, 201);
    }

    public function show($id) // $id di sini adalah kode_barang
    {
        // Eager load fotos relationship
        $barang = BarangTitipan::with('fotos')->findOrFail($id);
        return response()->json($barang);
    }

    public function update(Request $request, $id) // $id di sini adalah kode_barang
    {
        $barang = BarangTitipan::findOrFail($id);

        // Validasi data barang titipan (tanpa url_foto)
        $validatedData = $request->validate([
            'nama' => 'sometimes|required|string|max:100',
            'harga' => 'sometimes|required|numeric',
            'berat' => 'sometimes|required|numeric',
            // 'status_barang' di controller asli, mungkin maksudnya 'status'
            'status' => 'sometimes|required|string|max:11',
            'tanggal_kadaluarsa' => 'sometimes|nullable|date',
            'deskripsi' => 'sometimes|nullable|string',
            'id_kategori' => 'sometimes|required|exists:kategoribarang,id_kategori', // Pastikan nama tabel kategoribarang benar
            'id_penitip' => 'sometimes|required|exists:penitip,id_penitip',
            'perpanjangan' => 'sometimes|required|boolean',
            'garansi' => 'sometimes|nullable|date',
            'id_pembelian' => 'sometimes|nullable|exists:transaksipembelian,id_pembelian', // Pastikan nama tabel transaksipembelian benar
            // Tidak ada validasi 'url_foto' lagi

            // Validasi untuk multiple foto baru (jika ada yang diupload untuk mengganti/menambah)
            'fotos' => 'nullable|array',
            'fotos.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            // Anda mungkin perlu field tambahan untuk menandai foto mana yang mau dihapus
            'hapus_fotos' => 'nullable|array', // Array berisi ID foto yang mau dihapus
            'hapus_fotos.*' => 'integer|exists:barang_titipan_fotos,id'
        ]);
        
        $updateData = $validatedData;
        unset($updateData['fotos']);
        unset($updateData['hapus_fotos']);

        $barang->update($updateData);
        Log::info('BarangTitipan updated', ['kode_barang' => $barang->kode_barang]);

        // Proses hapus foto lama jika ada
        if ($request->filled('hapus_fotos')) {
            $idsFotoDihapus = $request->input('hapus_fotos');
            Log::info('Attempting to delete photos', ['ids' => $idsFotoDihapus, 'kode_barang' => $barang->kode_barang]);
            foreach ($idsFotoDihapus as $idFoto) {
                $foto = BarangTitipanFoto::where('kode_barang', $barang->kode_barang)->find($idFoto);
                if ($foto) {
                    Storage::disk('public')->delete($foto->url_foto); // Hapus file fisik
                    $foto->delete(); // Hapus record dari DB
                    Log::info('Photo deleted', ['id_foto' => $idFoto, 'url_foto' => $foto->url_foto]);
                }
            }
        }

        // Proses upload dan simpan multiple foto baru jika ada
        if ($request->hasFile('fotos')) {
             Log::info('Processing new uploaded photos for update', ['kode_barang' => $barang->kode_barang]);
            // Dapatkan urutan terakhir untuk foto baru
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
        
        $barang->load('fotos'); // Reload relasi
        return response()->json($barang);
    }

    public function destroy($id) // $id di sini adalah kode_barang
    {
        $barang = BarangTitipan::with('fotos')->findOrFail($id); // Load fotos untuk dihapus
        Log::info('Attempting to delete BarangTitipan', ['kode_barang' => $barang->kode_barang]);

        // Hapus file fisik foto-foto terkait sebelum menghapus record barang
        // (Jika menggunakan onDelete('cascade') pada foreign key di migrasi, record foto di DB akan otomatis terhapus)
        // Namun file fisik tetap perlu dihapus manual.
        foreach ($barang->fotos as $foto) {
            try {
                Storage::disk('public')->delete($foto->url_foto);
                Log::info('Physical photo file deleted', ['url_foto' => $foto->url_foto]);
            } catch (\Exception $e) {
                Log::error('Failed to delete physical photo file', ['url_foto' => $foto->url_foto, 'error' => $e->getMessage()]);
            }
        }
        // Jika tidak ada onDelete('cascade'), hapus record foto secara manual:
        // $barang->fotos()->delete(); 

        $barang->delete(); // Ini akan trigger onDelete('cascade') jika diset di migrasi untuk tabel barang_titipan_fotos
        Log::info('BarangTitipan deleted', ['kode_barang' => $id]);

        return response()->json(null, 204);
    }
}
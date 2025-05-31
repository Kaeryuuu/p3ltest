<?php

namespace App\Http\Controllers;

use App\Models\Pegawai;
use App\Models\BarangTitipan;
use App\Models\TransaksiPenitipan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function barangTitipanIndex()
    {
        $this->middleware(function ($request, $next) {
            if (Auth::guard('pegawai')->user()->jabatan->id_jabatan !== 4) {
                abort(403, 'Unauthorized. Only Pegawai Gudang can access this page.');
            }
            return $next($request);
        })->only(['barangTitipanIndex', 'recordPickup']);

        $barangTitipan = BarangTitipan::whereIn('status', ['tersedia', 'didonasikan'])
            ->with('transaksiPenitipan')
            ->get()
            ->map(function ($barang) {
                $deskripsi = json_decode($barang->deskripsi, true);
                $barang->photos = isset($deskripsi['photos']) ? $deskripsi['photos'] : [];
                return $barang;
            });

        return view('gudang.barang-titipan.index', compact('barangTitipan'));
    }

    public function recordPickup(Request $request, $kode_barang)
    {
        $this->middleware(function ($request, $next) {
            if (Auth::guard('pegawai')->user()->jabatan->id_jabatan !== 4) {
                abort(403, 'Unauthorized. Only Pegawai Gudang can access this page.');
            }
            return $next($request);
        });

        $barang = BarangTitipan::where('kode_barang', $kode_barang)
            ->whereIn('status', ['tersedia', 'didonasikan'])
            ->firstOrFail();

        $transaksi = TransaksiPenitipan::where('kode_barang', $kode_barang)->firstOrFail();
    
        $transaksi->tanggal_diambil = now();
        $transaksi->id_pegawai = Auth::guard('pegawai')->user()->id_pegawai;
        $transaksi->save();

        $barang->status = 'hangus';
        $barang->save();

        return redirect()->route('gudang.barang-titipan.index')->with('success', 'Pengambilan barang berhasil dicatat.');
    }
}
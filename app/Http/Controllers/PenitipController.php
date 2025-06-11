<?php

namespace App\Http\Controllers;

use App\Models\Penitip;
use App\Models\BarangTitipan;
use App\Models\TransaksiPenitipan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
// use Carbon\Carbon; // Sudah ada di file asli

class PenitipController extends Controller
{
    public function __construct()
    {
        // Apply auth:penitip middleware to Penitip-specific methods
        $this->middleware('auth:penitip')->only([
            'barangTitipanIndex',
            'barangTitipanSearch', // Jika masih ada
            'barangTitipanExtend',
            'barangTitipanConfirmPickup',
            'barangTitipanManage',
            'barangTitipanShow' // Tambahkan method baru ini
        ]);

        // Apply auth:pegawai and CS role check for CS-specific methods
        $this->middleware(['auth:pegawai', function ($request, $next) {
            if (Auth::guard('pegawai')->user()->jabatan->nama !== 'Customer Service') {
                Log::warning('Unauthorized CS access attempt', [
                    'user_id' => Auth::guard('pegawai')->id(),
                    'session_id' => $request->session()->getId(),
                ]);
                abort(403, 'Unauthorized. Only Customer Service can access this page.');
            }
            return $next($request);
        }])->only([
            'index',
            'create',
            'store',
            'edit',
            'update',
            'deactivate',
            'activate'
        ]);
    }

    // ... (method index, create, store, edit, update, deactivate, activate untuk CS tetap sama) ...
    // Salin method index, create, store, edit, update, deactivate, activate dari file PenitipController.php Anda yang sudah ada

    public function index(Request $request) // Ini method untuk CS
    {
        Log::info('Accessing CS Penitip index', [
            'user_id' => Auth::guard('pegawai')->id(),
            'session_id' => $request->session()->getId(),
            'session_data' => $request->session()->all(),
            'cookies' => $request->cookies->all()
        ]);

        $query = Penitip::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where('nama', 'like', "%{$search}%")
                  ->orWhere('id_penitip', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('no_ktp', 'like', "%{$search}%");
        }

        $penitips = $query->orderBy('id_penitip')->paginate(10);

        return view('dashboards.cs-penitip', compact('penitips'));
    }

    public function create() // Ini method untuk CS
    {
        Log::info('Accessing CS Penitip create form', [
            'user_id' => Auth::guard('pegawai')->id(),
            'session_id' => request()->session()->getId(),
            'session_data' => request()->session()->all(),
            'cookies' => request()->cookies->all()
        ]);
        return view('dashboards.cs-penitip-create');
    }

    public function store(Request $request) // Ini method untuk CS
    {
        Log::info('Attempting to store new Penitip', [
            'user_id' => Auth::guard('pegawai')->id(),
            'session_id' => $request->session()->getId(),
            'session_data' => $request->session()->all(),
            'cookies' => $request->cookies->all()
        ]);

        $validated = $request->validate([
            'no_ktp' => 'required|string|max:16|unique:penitip,no_ktp',
            'nama' => 'required|string|max:100',
            'telepon' => 'required|string|max:15',
            'email' => 'required|email|max:100|unique:penitip,email',
            'password' => 'required|string|min:8',
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $number = 1;
        do {
            $idPenitip = 'T' . $number;
            $number++;
        } while (Penitip::where('id_penitip', $idPenitip)->exists());

        $fotoKtpPath = $request->file('foto_ktp')->store('ktp_photos', 'public');

        try {
            Penitip::create([
                'id_penitip' => $idPenitip,
                'no_ktp' => $validated['no_ktp'],
                'nama' => $validated['nama'],
                'telepon' => $validated['telepon'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'url_foto' => Storage::url($fotoKtpPath),
                'poin_loyalitas' => 0,
                'status' => 'active',
                'saldo' => 0,
                'jumlah_jual' => 0,
                'rating' => 0,
                'badge' => null,
            ]);
            Log::info('Penitip created successfully', ['id_penitip' => $idPenitip]);
            return redirect()->route('cs.penitip.index')->with('success', 'Penitip created successfully.');
        } catch (\Exception $e) {
            Log::error('Penitip creation failed: ' . $e->getMessage(), [
                'session_id' => $request->session()->getId(),
                'session_data' => $request->session()->all(),
                'cookies' => $request->cookies->all()
            ]);
            return back()->withErrors(['error' => 'Failed to create Penitip: ' . $e->getMessage()]);
        }
    }

    public function edit($id_penitip) // Ini method untuk CS
    {
        Log::info('Accessing CS Penitip edit form', [
            'user_id' => Auth::guard('pegawai')->id(),
            'id_penitip' => $id_penitip,
            'session_id' => request()->session()->getId(),
            'session_data' => request()->session()->all(),
            'cookies' => request()->cookies->all()
        ]);

        $penitip = Penitip::findOrFail($id_penitip);
        return view('dashboards.cs-penitip-edit', compact('penitip'));
    }

    public function update(Request $request, $id_penitip) // Ini method untuk CS
    {
        Log::info('Attempting to update Penitip', [
            'user_id' => Auth::guard('pegawai')->id(),
            'id_penitip' => $id_penitip,
            'session_id' => $request->session()->getId(),
            'session_data' => $request->session()->all(),
            'cookies' => $request->cookies->all()
        ]);

        $penitip = Penitip::findOrFail($id_penitip);

        $validated = $request->validate([
            'no_ktp' => 'sometimes|string|max:16|unique:penitip,no_ktp,' . $id_penitip . ',id_penitip',
            'nama' => 'required|string|max:100',
            'telepon' => 'required|string|max:15',
            'email' => 'required|email|max:100|unique:penitip,email,' . $id_penitip . ',id_penitip',
            'password' => 'nullable|string|min:8',
            'foto_ktp' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'status' => 'sometimes|required|in:active,inactive',
            'poin_loyalitas' => 'sometimes|integer|min:0',
            'saldo' => 'sometimes|numeric|min:0',
            'jumlah_jual' => 'sometimes|integer|min:0',
            'rating' => 'sometimes|numeric|min:0|max:5',
            'badge' => 'sometimes|nullable|string|max:100',
        ]);

        try {
            $updateData = [
                'nama' => $validated['nama'],
                'telepon' => $validated['telepon'],
                'email' => $validated['email'],
            ];

            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }
            if (isset($validated['no_ktp'])) { // Tambahkan no_ktp jika diisi
                $updateData['no_ktp'] = $validated['no_ktp'];
            }
            if (isset($validated['status'])) {
                $updateData['status'] = $validated['status'];
            }
            if (isset($validated['poin_loyalitas'])) {
                $updateData['poin_loyalitas'] = $validated['poin_loyalitas'];
            }
            if (isset($validated['saldo'])) {
                $updateData['saldo'] = $validated['saldo'];
            }
            if (isset($validated['jumlah_jual'])) {
                $updateData['jumlah_jual'] = $validated['jumlah_jual'];
            }
            if (isset($validated['rating'])) {
                $updateData['rating'] = $validated['rating'];
            }
            if (isset($validated['badge'])) {
                $updateData['badge'] = $validated['badge'];
            }

            if ($request->hasFile('foto_ktp')) {
                if ($penitip->url_foto && Storage::disk('public')->exists(str_replace('/storage/', '', $penitip->url_foto))) {
                     Storage::disk('public')->delete(str_replace('/storage/', '', $penitip->url_foto));
                }
                $fotoKtpPath = $request->file('foto_ktp')->store('ktp_photos', 'public');
                $updateData['url_foto'] = Storage::url($fotoKtpPath);
            }

            $penitip->update($updateData);
            Log::info('Penitip updated successfully', ['id_penitip' => $id_penitip]);
            return redirect()->route('cs.penitip.index')->with('success', 'Penitip updated successfully.');
        } catch (\Exception $e) {
            Log::error('Penitip update failed: ' . $e->getMessage(), [
                'session_id' => $request->session()->getId(),
                'session_data' => $request->session()->all(),
                'cookies' => $request->cookies->all(),
                'exception_trace' => $e->getTraceAsString()
            ]);
            return back()->withErrors(['error' => 'Failed to update Penitip: ' . $e->getMessage()]);
        }
    }

    public function deactivate($id_penitip) // Ini method untuk CS
    {
        Log::info('Attempting to deactivate Penitip', [
            'user_id' => Auth::guard('pegawai')->id(),
            'id_penitip' => $id_penitip,
            'session_id' => request()->session()->getId(),
            'session_data' => request()->session()->all(),
            'cookies' => request()->cookies->all()
        ]);

        $penitip = Penitip::findOrFail($id_penitip);

        try {
            $penitip->update(['status' => 'inactive']);
            Log::info('Penitip deactivated successfully', ['id_penitip' => $id_penitip]);
            return redirect()->route('cs.penitip.index')->with('success', 'Penitip deactivated successfully.');
        } catch (\Exception $e) {
            Log::error('Penitip deactivation failed: ' . $e->getMessage(), [
                'session_id' => request()->session()->getId(),
                'session_data' => request()->session()->all(),
                'cookies' => request()->cookies->all()
            ]);
            return back()->withErrors(['error' => 'Failed to deactivate Penitip: ' . $e->getMessage()]);
        }
    }

    public function activate($id_penitip) // Ini method untuk CS
    {
        Log::info('Attempting to activate Penitip', [
            'user_id' => Auth::guard('pegawai')->id(),
            'id_penitip' => $id_penitip,
            'session_id' => request()->session()->getId(),
            'session_data' => request()->session()->all(),
            'cookies' => request()->cookies->all()
        ]);

        $penitip = Penitip::findOrFail($id_penitip);

        try {
            $penitip->update(['status' => 'active']);
            Log::info('Penitip activated successfully', ['id_penitip' => $id_penitip]);
            return redirect()->route('cs.penitip.index')->with('success', 'Penitip activated successfully.');
        } catch (\Exception $e) {
            Log::error('Penitip activation failed: ' . $e->getMessage(), [
                'session_id' => request()->session()->getId(),
                'session_data' => request()->session()->all(),
                'cookies' => request()->cookies->all()
            ]);
            return back()->withErrors(['error' => 'Failed to activate Penitip: ' . $e->getMessage()]);
        }
    }


    public function barangTitipanIndex(Request $request) // Dashboard Penitip
    {
        Log::info('Accessing Penitip dashboard', [
            'is_authenticated' => Auth::guard('penitip')->check(),
            'user_id' => Auth::guard('penitip')->check() ? Auth::guard('penitip')->id() : null,
            'session_id' => $request->session()->getId(),
        ]);

        if (!Auth::guard('penitip')->check()) {
            Log::warning('Unauthenticated access to penitip dashboard', ['session_id' => $request->session()->getId()]);
            return redirect()->route('login')->withErrors(['error' => 'Silakan login kembali untuk mengakses dashboard.']);
        }

        $penitip = Auth::guard('penitip')->user();
        
        // Jika Anda sudah beralih ke relasi 'fotos', gunakan with('fotos')
        // Untuk sementara, jika masih menggunakan 'deskripsi' JSON untuk foto di list:
        $barangTitipan = BarangTitipan::where('id_penitip', $penitip->id_penitip)
            ->get()
            ->map(function ($barang) {
                // Jika 'deskripsi' masih dipakai untuk foto di list view dashboard
                if (isset($barang->deskripsi)) {
                    $deskripsi = json_decode($barang->deskripsi, true);
                    if (is_array($deskripsi) && isset($deskripsi['photos'])) {
                        $barang->photos_from_deskripsi = $deskripsi['photos'];
                    } else {
                        $barang->photos_from_deskripsi = [];
                    }
                } else {
                     $barang->photos_from_deskripsi = [];
                }
                return $barang;
            });

        Log::info('Penitip dashboard loaded', [
            'user_id' => $penitip->id_penitip,
            'results_count' => $barangTitipan->count(),
        ]);

        return view('dashboards.penitip', compact('barangTitipan', 'penitip'));
    }

    public function barangTitipanSearch(Request $request) // Jika masih digunakan
    {
        // Implementasi search jika ada halaman search terpisah
        // Jika search terintegrasi di manage, method ini mungkin tidak dipanggil
        Log::info('Attempting penitip barang titipan search', [
            'user_id' => Auth::guard('penitip')->check() ? Auth::guard('penitip')->id() : null,
            'search_query' => $request->input('search'),
        ]);
        if (!Auth::guard('penitip')->check()) {
            return redirect()->route('login')->withErrors(['error' => 'Silakan login kembali.']);
        }
        $penitip = Auth::guard('penitip')->user();
        $query = trim($request->input('search', ''));

        $barangTitipanQuery = BarangTitipan::with('fotos') // Eager load 'fotos'
                                     ->where('id_penitip', $penitip->id_penitip);

        if (!empty($query)) {
            $barangTitipanQuery->where(function ($q) use ($query) {
                $q->where('nama', 'LIKE', "%{$query}%")
                  ->orWhere('harga', 'LIKE', "%{$query}%")
                  ->orWhere('berat', 'LIKE', "%{$query}%")
                  ->orWhere('status', 'LIKE', "%{$query}%")
                  ->orWhere('kondisi', 'LIKE', "%{$query}%");
            });
        }
        $barangTitipan = $barangTitipanQuery->paginate(10); // Atau get() jika tidak paginasi

        // Untuk tampilan list, Anda mungkin ingin menampilkan foto utama saja
        // $barangTitipan->getCollection()->transform(function ($barang) {
        //     $barang->foto_utama_url = $barang->fotoUtama ? Storage::url($barang->fotoUtama->url_foto) : null;
        //     return $barang;
        // });


        // Mengembalikan ke view yang sama dengan manage atau dashboard, tergantung alur
        return view('dashboards.penitip-barang-titipan', compact('barangTitipan', 'penitip', 'query'));
    }


    public function barangTitipanExtend(Request $request, $kode_barang)
    {
        Log::info('Attempting to extend barang titipan', [
            'kode_barang' => $kode_barang,
            'user_id' => Auth::guard('penitip')->check() ? Auth::guard('penitip')->id() : null,
        ]);

        if (!Auth::guard('penitip')->check()) {
            return redirect()->route('login')->withErrors(['error' => 'Silakan login kembali untuk memperpanjang barang.']);
        }

        try {
            $barang = BarangTitipan::where('kode_barang', $kode_barang)
                ->where('id_penitip', Auth::guard('penitip')->id())
                ->where('status', 'tersedia')
                ->where('perpanjangan', false)
                ->firstOrFail();

            if (is_null($barang->tanggal_kadaluarsa)) {
                Log::error('Tanggal kadaluarsa is null for extension', ['kode_barang' => $kode_barang]);
                return redirect()->route('penitip.barang-titipan.manage')->withErrors(['error' => 'Tanggal kadaluarsa tidak valid. Tidak bisa diperpanjang.']);
            }

            $barang->perpanjangan = true;
            $barang->tanggal_kadaluarsa = \Carbon\Carbon::parse($barang->tanggal_kadaluarsa)->addDays(30);
            $barang->save();

            Log::info('Barang titipan extended successfully', [
                'kode_barang' => $kode_barang,
                'new_tanggal_kadaluarsa' => $barang->tanggal_kadaluarsa->toDateString()
            ]);

            return redirect()->route('penitip.barang-titipan.manage')->with('success', ['message' => 'Durasi penitipan berhasil diperpanjang.']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('BarangTitipan not found for extension or does not meet criteria.', ['kode_barang' => $kode_barang]);
            return redirect()->route('penitip.barang-titipan.manage')->withErrors(['error' => 'Barang tidak ditemukan atau sudah pernah diperpanjang/tidak tersedia.']);
        } catch (\Exception $e) {
            Log::error('Failed to extend barang titipan: ' . $e->getMessage(), [
                'kode_barang' => $kode_barang,
                'exception_trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('penitip.barang-titipan.manage')->withErrors(['error' => 'Gagal memperpanjang durasi penitipan: ' . $e->getMessage()]);
        }
    }

    public function barangTitipanConfirmPickup(Request $request, $kode_barang)
{
    Log::info('[CONFIRM PICKUP] START', [
        'kode_barang' => $kode_barang,
        'user_id' => Auth::guard('penitip')->check() ? Auth::guard('penitip')->id() : 'Not Authenticated',
        'session_id' => $request->session()->getId(),
    ]);

    if (!Auth::guard('penitip')->check()) {
        Log::warning('[CONFIRM PICKUP] Unauthenticated access.', ['kode_barang' => $kode_barang]);
        return redirect()->route('login')->withErrors(['error' => 'Silakan login kembali untuk mengkonfirmasi pengambilan.']);
    }

    try {
        Log::info('[CONFIRM PICKUP] Mencari BarangTitipan...', [
            'kode_barang' => $kode_barang,
            'id_penitip' => Auth::guard('penitip')->id(),
            'target_status' => ['tersedia', 'didonasikan'],
            'target_kadaluarsa_before' => now()->toDateTimeString()
        ]);

        // Fetch BarangTitipan with the related TransaksiPenitipan
        $barang = BarangTitipan::with('transaksiPenitipan') // Ensure the relationship is defined
            ->where('kode_barang', $kode_barang)
            ->where('id_penitip', Auth::guard('penitip')->id())
            ->whereIn('status', ['tersedia', 'didonasikan'])
            ->whereNotNull('tanggal_kadaluarsa')
            ->whereDate('tanggal_kadaluarsa', '<', now()->toDateString())
            ->first();

        if (!$barang) {
            Log::error('[CONFIRM PICKUP] BarangTitipan tidak ditemukan atau tidak memenuhi kondisi (misal belum kadaluarsa atau status tidak sesuai).', [
                'kode_barang' => $kode_barang,
                'id_penitip' => Auth::guard('penitip')->id()
            ]);
            $debugBarang = BarangTitipan::where('kode_barang', $kode_barang)
                ->where('id_penitip', Auth::guard('penitip')->id())->first();
            Log::info('[CONFIRM PICKUP] Data barang aktual (tanpa filter status/kadaluarsa):', [
                'barang_aktual' => $debugBarang ? $debugBarang->toArray() : 'Tidak ditemukan sama sekali'
            ]);
            return redirect()->route('penitip.barang-titipan.manage')->withErrors([
                'error' => 'Barang tidak ditemukan atau tidak memenuhi syarat untuk konfirmasi pengambilan (misalnya belum kadaluarsa).'
            ]);
        }
        Log::info('[CONFIRM PICKUP] BarangTitipan ditemukan.', [
            'kode_barang' => $barang->kode_barang,
            'status_awal_barang' => $barang->status,
            'tanggal_kadaluarsa_barang' => $barang->tanggal_kadaluarsa
        ]);

        // Fetch the related TransaksiPenitipan using the relationship or id_penitipan
        $transaksi = $barang->transaksiPenitipan; // Assumes a relationship is defined
        if (!$transaksi) {
            // Fallback: Fetch TransaksiPenitipan using id_penitipan from BarangTitipan
            $transaksi = TransaksiPenitipan::where('id_penitipan', $barang->id_penitipan)->first();
        }

        if (!$transaksi) {
            Log::error('[CONFIRM PICKUP] TransaksiPenitipan tidak ditemukan.', [
                'kode_barang' => $kode_barang,
                'id_penitipan' => $barang->id_penitipan
            ]);
            return redirect()->route('penitip.barang-titipan.manage')->withErrors([
                'error' => 'Data transaksi penitipan terkait tidak ditemukan.'
            ]);
        }
        Log::info('[CONFIRM PICKUP] TransaksiPenitipan ditemukan.', [
            'id_penitipan' => $transaksi->id_penitipan,
            'tanggal_konfirmasi_ambil_awal' => $transaksi->tanggal_konfirmasi_ambil
        ]);

        // Update TransaksiPenitipan
        $transaksi->tanggal_konfirmasi_ambil = now();
        $transaksiSaved = $transaksi->save();
        Log::info('[CONFIRM PICKUP] Hasil simpan TransaksiPenitipan.', [
            'saved' => $transaksiSaved,
            'transaksi_setelah_simpan' => $transaksi->toArray()
        ]);

        if (!$transaksiSaved) {
            Log::error('[CONFIRM PICKUP] Gagal menyimpan TransaksiPenitipan.', ['id_penitipan' => $transaksi->id_penitipan]);
            return redirect()->route('penitip.barang-titipan.manage')->withErrors([
                'error' => 'Gagal menyimpan pembaruan transaksi penitipan.'
            ]);
        }

        // Update BarangTitipan status
        $status_barang_sebelum_update = $barang->status;
        $barang->status = 'akan diambil';
        $barangSaved = $barang->save();
        Log::info('[CONFIRM PICKUP] Hasil simpan BarangTitipan.', [
            'kode_barang' => $barang->kode_barang,
            'status_sebelum_update' => $status_barang_sebelum_update,
            'status_akan_diset' => 'akan diambil',
            'saved' => $barangSaved,
            'barang_setelah_simpan' => $barang->toArray()
        ]);

        if (!$barangSaved) {
            Log::error('[CONFIRM PICKUP] Gagal menyimpan status BarangTitipan.', ['kode_barang' => $barang->kode_barang]);
            return redirect()->route('penitip.barang-titipan.manage')->withErrors([
                'error' => 'Gagal menyimpan pembaruan status barang.'
            ]);
        }

        Log::info('[CONFIRM PICKUP] Sukses: Pickup confirmed. Tanggal konfirmasi ambil di transaksi & status barang diperbarui.', [
            'kode_barang' => $kode_barang,
            'barang_status_final' => $barang->status,
            'transaksi_tanggal_konfirmasi_ambil' => $transaksi->tanggal_konfirmasi_ambil
        ]);

        return redirect()->route('penitip.barang-titipan.manage')->with('success', [
            'message' => 'Konfirmasi pengambilan barang berhasil. Status barang diubah menjadi "Akan Diambil".'
        ]);

    } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        Log::error('[CONFIRM PICKUP] ModelNotFoundException: ' . $e->getMessage(), ['kode_barang' => $kode_barang]);
        return redirect()->route('penitip.barang-titipan.manage')->withErrors([
            'error' => 'Data barang atau transaksi terkait tidak ditemukan (exception).'
        ]);
    } catch (\Exception $e) {
        Log::error('[CONFIRM PICKUP] Exception: ' . $e->getMessage(), [
            'kode_barang' => $kode_barang,
            'session_id' => $request->session()->getId(),
            'exception_trace' => $e->getTraceAsString()
        ]);
        return redirect()->route('penitip.barang-titipan.manage')->withErrors([
            'error' => 'Gagal mengkonfirmasi pengambilan: Terjadi kesalahan sistem.'
        ]);
    }
}

    public function barangTitipanManage(Request $request) // Manage Barang Titipan Page
    {
        Log::info('Attempting to access manage barang titipan', [
            'is_authenticated' => Auth::guard('penitip')->check(),
            'user_id' => Auth::guard('penitip')->check() ? Auth::guard('penitip')->id() : null,
            'session_id' => $request->session()->getId(),
        ]);

        if (!Auth::guard('penitip')->check()) {
            Log::warning('Unauthenticated access to manage barang titipan', ['session_id' => $request->session()->getId()]);
            return redirect()->route('login')->withErrors(['error' => 'Silakan login kembali untuk mengelola barang titipan.']);
        }

        $penitip = Auth::guard('penitip')->user(); // Ambil data penitip
        $querySearch = trim($request->input('search', ''));

        $barangTitipanQuery = BarangTitipan::with(['fotos', 'kategori']) // Eager load relasi fotos dan kategori
            ->where('id_penitip', Auth::guard('penitip')->id());

        if (!empty($querySearch)) {
            $barangTitipanQuery->where(function ($q) use ($querySearch) {
                $q->where('nama', 'LIKE', "%{$querySearch}%")
                  ->orWhere('harga', 'LIKE', "%{$querySearch}%")
                  ->orWhere('berat', 'LIKE', "%{$querySearch}%")
                  ->orWhere('status', 'LIKE', "%{$querySearch}%")
                  ->orWhere('kondisi', 'LIKE', "%{$querySearch}%");
            });
        }

        $barangTitipan = $barangTitipanQuery->orderBy('nama', 'asc')->paginate(10); // Urutkan dan paginasi

        // Jika Anda masih menggunakan $barang->photos dari deskripsi JSON untuk thumbnail di list:
        // (Sebaiknya beralih ke $barang->fotos->first() jika sudah memungkinkan)
        $barangTitipan->getCollection()->transform(function ($barang) {
            if (isset($barang->deskripsi)) {
                $deskripsi = json_decode($barang->deskripsi, true);
                 if (is_array($deskripsi) && isset($deskripsi['photos'])) {
                    $barang->photos_from_deskripsi = $deskripsi['photos']; // Untuk kompatibilitas sementara di view list
                } else {
                    $barang->photos_from_deskripsi = [];
                }
            } else {
                $barang->photos_from_deskripsi = [];
            }
            return $barang;
        });


        Log::info('Manage barang titipan loaded', [
            'user_id' => Auth::guard('penitip')->id(),
            'results_count' => $barangTitipan->total(),
        ]);
        // Kirim $penitip dan $querySearch (nama variabel query di blade) ke view
        return view('dashboards.penitip-barang-titipan', compact('barangTitipan', 'penitip', 'querySearch'));
    }

    /**
     * Menampilkan detail barang titipan untuk penitip.
     */
    public function barangTitipanShow(Request $request, $kode_barang)
    {
        Log::info('[BARANG DETAIL] Attempting to show barang titipan detail', [
            'kode_barang' => $kode_barang,
            'user_id' => Auth::guard('penitip')->check() ? Auth::guard('penitip')->id() : null,
        ]);

        if (!Auth::guard('penitip')->check()) {
            Log::warning('[BARANG DETAIL] Unauthenticated access.', ['kode_barang' => $kode_barang]);
            return redirect()->route('login')->withErrors(['error' => 'Silakan login kembali untuk melihat detail barang.']);
        }

        try {
            $barang = BarangTitipan::with(['fotos', 'kategori', 'penitip']) // Eager load relasi yang dibutuhkan
                ->where('kode_barang', $kode_barang)
                ->where('id_penitip', Auth::guard('penitip')->id())
                ->firstOrFail(); // Gunakan firstOrFail untuk otomatis 404 jika tidak ditemukan

            $penitip = Auth::guard('penitip')->user(); // Ambil data penitip yang sedang login

            Log::info('[BARANG DETAIL] Barang titipan detail loaded successfully.', ['kode_barang' => $kode_barang]);
            return view('dashboards.penitip-barang-titipan-detail', compact('barang', 'penitip'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('[BARANG DETAIL] BarangTitipan not found for detail view.', ['kode_barang' => $kode_barang, 'user_id' => Auth::guard('penitip')->id()]);
            // Redirect kembali ke halaman manage dengan pesan error
            return redirect()->route('penitip.barang-titipan.manage')->withErrors(['error' => 'Detail barang tidak ditemukan atau Anda tidak memiliki akses.']);
        } catch (\Exception $e) {
            Log::error('[BARANG DETAIL] Exception occurred: ' . $e->getMessage(), [
                'kode_barang' => $kode_barang,
                'user_id' => Auth::guard('penitip')->id(),
                'exception_trace' => $e->getTraceAsString()
            ]);
            return redirect()->route('penitip.barang-titipan.manage')->withErrors(['error' => 'Terjadi kesalahan saat menampilkan detail barang.']);
        }
    }

    public function lowSaldoPenitip(Request $request)
    {
        $query = Penitip::where('saldo', '>', 500000)
        ->where('jumlah_Jual','>=', 2);
        


        if ($request->has('sort')) {
            $query->orderBy($request->input('sort'), $request->input('direction', 'asc'));
        } else {
            $query->orderBy('id_penitip', 'asc');
        }
        $penitipList = $query->get();
        return view('dashboards.cs-penitip-low', compact('penitipList'));
    }
}
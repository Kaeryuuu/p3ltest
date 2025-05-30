<?php

namespace App\Http\Controllers;

use App\Models\Penitip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PenitipController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:pegawai');
        $this->middleware(function ($request, $next) {
            if (Auth::guard('pegawai')->user()->jabatan->nama !== 'Customer Service') {
                abort(403, 'Unauthorized. Only Customer Service can access this page.');
            }
            return $next($request);
        });
    }

    public function index(Request $request)
    {
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

    public function create()
    {
        return view('dashboards.cs-penitip-create');
    }

    public function store(Request $request)
{
    $validated = $request->validate([
        'no_ktp' => 'required|string|max:16|unique:penitip,no_ktp',
        'nama' => 'required|string|max:100',
        'telepon' => 'required|string|max:15',
        'email' => 'required|email|max:100|unique:penitip,email',
        'password' => 'required|string|min:8',
        'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    // Generate unique id_penitip
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
        return redirect()->route('cs.penitip.index')->with('success', 'Penitip created successfully.');
    } catch (\Exception $e) {
        \Log::error('Penitip creation failed: ' . $e->getMessage());
        return back()->withErrors(['error' => 'Failed to create Penitip: ' . $e->getMessage()]);
    }
}

    public function edit($id_penitip)
    {
        $penitip = Penitip::findOrFail($id_penitip);
        return view('dashboards.cs-penitip-edit', compact('penitip'));
    }

    public function update(Request $request, $id_penitip)
    {
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
                if ($penitip->url_foto) {
                    Storage::disk('public')->delete(str_replace('/storage/', '', $penitip->url_foto));
                }
                $fotoKtpPath = $request->file('foto_ktp')->store('ktp_photos', 'public');
                $updateData['url_foto'] = Storage::url($fotoKtpPath);
            }

            $penitip->update($updateData);
            return redirect()->route('cs.penitip.index')->with('success', 'Penitip updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Penitip update failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update Penitip: ' . $e->getMessage()]);
        }
    }

    public function deactivate($id_penitip)
    {
        $penitip = Penitip::findOrFail($id_penitip);

        try {
            $penitip->update(['status' => 'inactive']);
            return redirect()->route('cs.penitip.index')->with('success', 'Penitip deactivated successfully.');
        } catch (\Exception $e) {
            \Log::error('Penitip deactivation failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to deactivate Penitip: ' . $e->getMessage()]);
        }
    }
    public function activate($id_penitip)
    {
        $penitip = Penitip::findOrFail($id_penitip);

        try {
            $penitip->update(['status' => 'active']);
            return redirect()->route('cs.penitip.index')->with('success', 'Penitip activated successfully.');
        } catch (\Exception $e) {
            \Log::error('Penitip activation failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to activate Penitip: ' . $e->getMessage()]);
        }
    }
}
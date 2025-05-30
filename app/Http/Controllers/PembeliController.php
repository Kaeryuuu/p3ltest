<?php

namespace App\Http\Controllers;

use App\Models\Pembeli;
use App\Models\TransaksiPembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PembeliController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:pembeli')->except(['registerUser']);
    }

    public function registerUser(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'telepon' => 'required|string|max:15',
            'email' => 'required|email|max:100|unique:pembeli,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        try {
            $pembeli = Pembeli::create([
                'nama' => $validated['nama'],
                'telepon' => $validated['telepon'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'poin_loyalitas' => 0,
                'status' => 'active',
            ]);

            Auth::guard('pembeli')->login($pembeli);

            return redirect()->route('homepage')->with('success', 'Registration successful!');
        } catch (\Exception $e) {
            \Log::error('Pembeli registration failed: ' . $e->getMessage());
            return back()->withErrors(['email' => 'Registration failed: ' . $e->getMessage()]);
        }
    }

    public function dashboard(Request $request)
    {
        $pembeli = Auth::guard('pembeli')->user();
        
        $query = TransaksiPembelian::where('id_pembeli', $pembeli->id_pembeli)
            ->with('barangTitipan')
            ->orderBy('tanggal_pembelian', 'desc');

        if ($request->filled('invoice_id')) {
            $query->where('id_pembelian', $request->input('invoice_id'));
        }

        if ($request->filled('start_date')) {
            $query->whereDate('tanggal_pembelian', '>=', $request->input('start_date'));
        }

        $transaksis = $query->paginate(10);

        return view('dashboards.pembeli', compact('pembeli', 'transaksis'));
    }

    public function transactionDetail($id_pembelian)
    {
        $pembeli = Auth::guard('pembeli')->user();
        $transaksi = TransaksiPembelian::where('id_pembelian', $id_pembelian)
            ->where('id_pembeli', $pembeli->id_pembeli)
            ->with('barangTitipan')
            ->firstOrFail();

        return view('dashboards.transaction_detail', compact('transaksi'));
    }

    public function index()
    {
        $pembeli = Pembeli::all();
        return response()->json($pembeli);
    }

    public function store(Request $request)
    {
        return redirect()->route('registerUser');
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
            'password' => 'sometimes|required|string|min:8',
            'status' => 'sometimes|required|in:active,inactive',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

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
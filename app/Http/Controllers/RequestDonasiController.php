<?php

namespace App\Http\Controllers;

use App\Models\RequestDonasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RequestDonasiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:organisasi');
    }

    public function index(Request $request)
    {
        $query = RequestDonasi::where('id_organisasi', Auth::guard('organisasi')->user()->id_organisasi);

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('deskripsi', 'like', "%{$search}%")
                  ->orWhere('status', 'like', "%{$search}%")
                  ->orWhere('tanggal_permintaan', 'like', "%{$search}%");
            });
        }

        $requests = $query->orderBy('tanggal_permintaan', 'asc')->paginate(10);

        return view('dashboards.organisasi-request-donasi', compact('requests'));
    }

    public function create()
    {
        return view('dashboards.organisasi-request-donasi-create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'deskripsi' => 'required|string|max:255',
        ]);

        try {
            RequestDonasi::create([
                'id_organisasi' => Auth::guard('organisasi')->user()->id_organisasi,
                'deskripsi' => $validated['deskripsi'],
                'tanggal_permintaan' => now(),
                'status' => 'Pending',
            ]);

            return redirect()->route('organisasi.request-donasi.create')->with('success', [
                'message' => "Berhasil Menambahkan Request Donasi !",
                'ID Organisasi' => Auth::guard('organisasi')->user()->id_organisasi,
                'Nama Organisasi' => Auth::guard('organisasi')->user()->nama,
                'deskripsi' => $validated['deskripsi'],
            ]);
        } catch (\Exception $e) {
            \Log::error('Donation request creation failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create donation request: ' . $e->getMessage()]);
        }
    }

    public function edit($id_request)
    {
        $requestDonasi = RequestDonasi::where('id_organisasi', Auth::guard('organisasi')->user()->id_organisasi)
                                      ->findOrFail($id_request);
        return view('dashboards.organisasi-request-donasi-edit', compact('requestDonasi'));
    }

    public function update(Request $request, $id_request)
    {
        $requestDonasi = RequestDonasi::where('id_organisasi', Auth::guard('organisasi')->user()->id_organisasi)
                                      ->findOrFail($id_request);

        $validated = $request->validate([
            'deskripsi' => 'required|string|max:255',
            'status' => 'required|in:Pending,Approved,Rejected',
        ]);

        try {
            $requestDonasi->update([
                'deskripsi' => $validated['deskripsi'],
                'status' => $validated['status'],
            ]);
            return redirect()->route('organisasi.request-donasi.index')->with('success', 'Donation request updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Donation request update failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update donation request: ' . $e->getMessage()]);
        }
    }

    public function destroy($id_request)
    {
        $requestDonasi = RequestDonasi::where('id_organisasi', Auth::guard('organisasi')->user()->id_organisasi)
                                      ->findOrFail($id_request);

        try {
            $requestDonasi->delete();
            return redirect()->route('organisasi.request-donasi.index')->with('success', 'Donation request deleted successfully.');
        } catch (\Exception $e) {
            \Log::error('Donation request deletion failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to delete donation request: ' . $e->getMessage()]);
        }
    }
}
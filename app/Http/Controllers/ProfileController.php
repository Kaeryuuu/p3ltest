<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:pembeli,organisasi');
    }

    public function index()
    {
        $user = Auth::guard('pembeli')->check() ? Auth::guard('pembeli')->user() : Auth::guard('organisasi')->user();
        return view('profile', compact('user'));
    }

    public function update(Request $request)
    {
        $user = Auth::guard('pembeli')->check() ? Auth::guard('pembeli')->user() : Auth::guard('organisasi')->user();
        $guard = Auth::guard('pembeli')->check() ? 'pembeli' : 'organisasi';

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'telepon' => 'required|string|max:15',
            'email' => 'required|email|max:100|unique:penitip,email',
            'password' => 'nullable|string|min:8|confirmed',
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

            $user->update($updateData);

            return redirect()->route('profile')->with('success', 'Profile updated successfully.');
        } catch (\Exception $e) {
            \Log::error('Profile update failed: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to update profile: ' . $e->getMessage()]);
        }
    }
}
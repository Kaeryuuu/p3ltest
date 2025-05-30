<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Pembeli;
use App\Models\Penitip;
use App\Models\Organisasi;
use App\Models\Pegawai;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Attempt login for each user type
        if (Auth::guard('pembeli')->attempt($credentials)) {
            $pembeli = Auth::guard('pembeli')->user();
            if ($pembeli->status === 'inactive') {
                Auth::guard('pembeli')->logout();
                return back()->withErrors(['email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
            }
            $request->session()->regenerate();
            return redirect()->route('homepage');
        }

        if (Auth::guard('penitip')->attempt($credentials)) {
            $penitip = Auth::guard('penitip')->user();
            if ($penitip->status === 'inactive') {
                Auth::guard('penitip')->logout();
                return back()->withErrors(['email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
            }
            $request->session()->regenerate();
            return redirect()->route('homepage');
        }

        if (Auth::guard('organisasi')->attempt($credentials)) {
            $organisasi = Auth::guard('organisasi')->user();
            if ($organisasi->status === 'inactive') {
                Auth::guard('organisasi')->logout();
                return back()->withErrors(['email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
            }
            $request->session()->regenerate();
            return redirect()->route('homepage');
        }
if (Auth::guard('pegawai')->attempt($credentials)) {
            $request->session()->regenerate();
            $pegawai = Auth::guard('pegawai')->user();
            $jabatan = $pegawai->jabatan->nama;

            switch ($jabatan) {
                case 'Owner':
                    return redirect()->route('owner.dashboard');
                case 'Admin':
                    return redirect()->route('admin.dashboard');
                case 'Customer Service':
                    return redirect()->route('cs.dashboard');
                case 'Gudang':
                    return redirect()->route('gudang.dashboard');
                default:
                    Auth::guard('pegawai')->logout();
                    return back()->withErrors(['email' => 'Jabatan tidak dikenali.']);
            }
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        $guards = ['pembeli', 'penitip', 'organisasi', 'pegawai'];
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                Auth::guard($guard)->logout();
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }
}
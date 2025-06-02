<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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

        Log::info('Attempting login', [
            'email' => $credentials['email'],
            'session_id' => $request->session()->getId(),
            'session_data' => $request->session()->all(),
            'cookies' => $request->cookies->all(),
            'headers' => $request->headers->all()
        ]);

        if (Auth::guard('penitip')->attempt($credentials)) {
            $penitip = Auth::guard('penitip')->user();
            if ($penitip->status === 'inactive') {
                Auth::guard('penitip')->logout();
                Log::warning('Inactive Penitip account', ['email' => $credentials['email']]);
                return back()->withErrors(['email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
            }
            $request->session()->regenerate();
            $request->session()->put('guard', 'penitip');
            Log::info('Penitip login successful', [
                'user_id' => $penitip->id_penitip,
                'session_id' => $request->session()->getId(),
                'session_data' => $request->session()->all(),
                'cookies' => $request->cookies->all()
                // 'intended_url' => route('homepage') // Komentari atau hapus intendedUrl jika tidak digunakan
            ]);
            Auth::guard('pembeli')->logout();
            Auth::guard('organisasi')->logout();
            Auth::guard('pegawai')->logout();
            // PERUBAHAN DI SINI: Arahkan ke homepage
            return redirect()->route('homepage');
        }

        // Blok if (Auth::guard('penitip')->attempt($credentials)) yang kedua telah dihapus karena duplikat

        if (Auth::guard('organisasi')->attempt($credentials)) {
            $organisasi = Auth::guard('organisasi')->user();
            if ($organisasi->status === 'inactive') {
                Auth::guard('organisasi')->logout();
                Log::warning('Inactive Organisasi account', ['email' => $credentials['email']]);
                return back()->withErrors(['email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
            }
            $request->session()->regenerate();
            $request->session()->put('guard', 'organisasi');
            Log::info('Organisasi login successful', [
                'user_id' => $organisasi->id_organisasi,
                'session_id' => $request->session()->getId(),
                'session_data' => $request->session()->all(),
                'cookies' => $request->cookies->all()
            ]);
            Auth::guard('pembeli')->logout();
            Auth::guard('penitip')->logout();
            Auth::guard('pegawai')->logout();
            return redirect()->intended(route('organisasi.dashboard')); // Biarkan ini atau ubah ke homepage jika perlu
        }

        if (Auth::guard('pegawai')->attempt($credentials)) {
            $pegawai = Auth::guard('pegawai')->user();
            if ($pegawai->status === 'inactive') {
                Auth::guard('pegawai')->logout();
                Log::warning('Inactive Pegawai account', ['email' => $credentials['email']]);
                return back()->withErrors(['email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
            }
            $request->session()->regenerate();
            $request->session()->put('guard', 'pegawai');
            Log::info('Pegawai login successful', [
                'user_id' => $pegawai->id_pegawai,
                'jabatan' => $pegawai->jabatan->nama,
                'session_id' => $request->session()->getId(),
                'session_data' => $request->session()->all(),
                'cookies' => $request->cookies->all()
            ]);
            Auth::guard('pembeli')->logout();
            Auth::guard('penitip')->logout();
            Auth::guard('organisasi')->logout();
            $jabatan = $pegawai->jabatan->nama;

            switch ($jabatan) {
                case 'Owner':
                    return redirect()->intended(route('owner.dashboard'));
                case 'Admin':
                    return redirect()->intended(route('admin.dashboard'));
                case 'Customer Service':
                    return redirect()->intended(route('cs.dashboard'));
                case 'Gudang':
                    return redirect()->intended(route('gudang'));
                case 'Kurir':
                    return redirect()->intended(route('kurir'));
                case 'Hunter':
                    return redirect()->intended(route('hunter.dashboard'));
                default:
                    Auth::guard('pegawai')->logout();
                    Log::warning('Unrecognized jabatan', ['jabatan' => $jabatan]);
                    return back()->withErrors(['email' => 'Jabatan tidak dikenali.']);
            }
        }

        Log::warning('Login failed', [
            'email' => $credentials['email'],
            'session_id' => $request->session()->getId(),
            'session_data' => $request->session()->all(),
            'cookies' => $request->cookies->all()
        ]);
        return back()->with(['error' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        $guards = ['pembeli', 'penitip', 'organisasi', 'pegawai'];
        $sessionId = $request->session()->getId();
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                Log::info('Logging out', [
                    'guard' => $guard,
                    'user_id' => Auth::guard($guard)->id(),
                    'session_id' => $sessionId,
                    'session_data' => $request->session()->all(),
                    'cookies' => $request->cookies->all()
                ]);
                Auth::guard($guard)->logout();
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('Session invalidated after logout', [
            'session_id' => $sessionId,
            'new_session_id' => $request->session()->getId(),
            'session_data' => $request->session()->all()
        ]);

        return redirect()->route('login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }
}
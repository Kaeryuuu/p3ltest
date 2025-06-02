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
            // Consider reducing session_data, cookies, and headers in regular logs unless debugging
            // 'session_data' => $request->session()->all(),
            // 'cookies' => $request->cookies->all(),
            // 'headers' => $request->headers->all()
        ]);

        // Attempt to log in as Pembeli first
        if (Auth::guard('pembeli')->attempt($credentials)) {
            $pembeli = Auth::guard('pembeli')->user();
            // Asumsi model Pembeli memiliki field 'status' dan primary key 'id_pembeli'
            // Sesuaikan jika nama field berbeda
            if (property_exists($pembeli, 'status') && $pembeli->status === 'inactive') {
                Auth::guard('pembeli')->logout();
                Log::warning('Inactive Pembeli account', ['email' => $credentials['email']]);
                return back()->withErrors(['email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.']);
            }
            $request->session()->regenerate();
            $request->session()->put('guard', 'pembeli');
            Log::info('Pembeli login successful', [
                'user_id' => $pembeli->id_pembeli, // Pastikan ini adalah primary key yang benar
                'session_id' => $request->session()->getId(),
            ]);
            // Logout from other guards if any session was active
            Auth::guard('penitip')->logout();
            Auth::guard('organisasi')->logout();
            Auth::guard('pegawai')->logout();
            return redirect()->route('homepage'); // Arahkan ke homepage atau dashboard pembeli jika ada
        }

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
            ]);
            Auth::guard('pembeli')->logout(); // Tambahkan logout pembeli jika penitip login
            Auth::guard('organisasi')->logout();
            Auth::guard('pegawai')->logout();
            return redirect()->route('homepage');
        }

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
            ]);
            Auth::guard('pembeli')->logout();
            Auth::guard('penitip')->logout();
            Auth::guard('pegawai')->logout();
            return redirect()->intended(route('organisasi.dashboard'));
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
                'jabatan' => $pegawai->jabatan->nama, // Pastikan relasi jabatan ada dan nama jabatan benar
                'session_id' => $request->session()->getId(),
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
                    return redirect()->intended(route('gudang.dashboard'));
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
        ]);
        return back()->with(['error' => 'Email atau password salah.']);
    }

    public function logout(Request $request)
    {
        $guards = ['pembeli', 'penitip', 'organisasi', 'pegawai'];
        $sessionId = $request->session()->getId();
        $loggedOutGuard = null;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $loggedOutGuard = $guard;
                Log::info('Logging out', [
                    'guard' => $guard,
                    'user_id' => Auth::guard($guard)->id(),
                    'session_id' => $sessionId,
                ]);
                Auth::guard($guard)->logout();
                // Hanya logout dari satu guard yang aktif, lalu invalidate session
                break; 
            }
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Log::info('Session invalidated after logout', [
            'logged_out_guard' => $loggedOutGuard,
            'old_session_id' => $sessionId, // Ganti nama variabel agar lebih jelas
            'new_session_id' => $request->session()->getId(),
        ]);

        return redirect()->route('login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }
}
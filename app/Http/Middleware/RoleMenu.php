<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RoleMenu
{
    public function handle($request, Closure $next)
    {
        $user = null;
        $role = $request->session()->get('user_role');
        $jabatan = null;

        // Set user and jabatan based on role
        if ($role === 'pembeli' && Auth::guard('pembeli')->check()) {
            $user = Auth::guard('pembeli')->user();
        } elseif ($role === 'penitip' && Auth::guard('penitip')->check()) {
            $user = Auth::guard('penitip')->user();
        } elseif ($role === 'organisasi' && Auth::guard('organisasi')->check()) {
            $user = Auth::guard('organisasi')->user();
        } elseif ($role === 'pegawai' && Auth::guard('pegawai')->check()) {
            $user = Auth::guard('pegawai')->user();
            $jabatan = $user->jabatan->nama ?? null;
        }

        // Share data with views
        view()->share('currentUser', $user);
        view()->share('userRole', $role);
        view()->share('jabatan', $jabatan);

        return $next($request);
    }
}
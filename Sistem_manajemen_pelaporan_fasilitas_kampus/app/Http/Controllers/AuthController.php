<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;


class AuthController extends Controller
{
    public function login()
    {
        if (Auth::check()) { // jika sudah login, maka redirect ke halaman home
            $role = Auth::user()->peran;
            $redirectPath = match($role) {
                'admin' => '/admin/laporan',
                'teknisi' => '/teknisi/penugasan',
                'sarpras' => '/sarpras/laporan_masuk',
                'pelapor' => '/pelapor/profile'
            };
            return redirect($redirectPath);
        }
        return view('auth.login');
    }

    public function postlogin(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $credentials = $request->only('username', 'password');
            if (Auth::attempt($credentials)) {
                
                $role = Auth::user()->peran;
                $redirectPath = match ($role) {
                    'admin' => '/admin/laporan/',
                    'teknisi' => '/teknisi/penugasan',
                    'sarpras' => '/sarpras/laporan_masuk',
                    'pelapor' => '/pelapor/profile'
                };
            // dd(Auth::user(), $redirectPath);
                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'redirect' => url($redirectPath)
                ]);
            }
        }
    
        return redirect('login');
    }
    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('login');
    }
}

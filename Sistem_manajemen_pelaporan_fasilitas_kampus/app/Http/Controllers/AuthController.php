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
            $redirectPath = match ($role) {
                'admin' => '/admin/laporan_masuk',
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

            // Cek apakah username ada
            $user = UserModel::where('username', $credentials['username'])->first();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Username tidak ditemukan.'
                ]);
            }

            // Cek apakah password cocok
            if (!Hash::check($credentials['password'], $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Password salah.'
                ]);
            }

            // Login user jika username dan password cocok
            Auth::login($user, $request->remember);

            $role = $user->peran;
            $redirectPath = match ($role) {
                'admin' => '/admin/laporan_periodik',
                'teknisi' => '/teknisi/penugasan',
                'sarpras' => '/sarpras/laporan_masuk',
                'pelapor' => '/pelapor/laporan_saya',
                default => '/'
            };

            return response()->json([
                'status' => true,
                'message' => 'Login berhasil.',
                'redirect' => url($redirectPath)
            ]);
        }

        return redirect('login');
    }
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}

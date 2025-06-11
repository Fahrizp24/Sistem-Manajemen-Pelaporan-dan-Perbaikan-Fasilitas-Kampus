<?php

namespace App\Http\Controllers;

use App\Models\User;
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

    public function forgot_password()
    {
        return view('auth.forgot_password');
    }

    // ForgotPasswordController.php

    public function cek_username(Request $request)
    {
        $user = UserModel::where('username', $request->username)->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Username tidak ditemukan']);
        }

        if (!$user->pertanyaan_keluarga || !$user->pertanyaan_masa_kecil || !$user->pertanyaan_tempat || !$user->pertanyaan_pengalaman) {
            return response()->json([
                'status' => false,
                'message' => 'Data pertanyaan belum lengkap.<br>Silakan hubungi admin untuk mereset password.<br>WA: 08123456789'
            ]);
        }

        return response()->json([
            'status' => true,
            'data' => [
                'pertanyaan_masa_kecil' => $user->pertanyaan_masa_kecil,
                'pertanyaan_keluarga' => $user->pertanyaan_keluarga,
                'pertanyaan_tempat' => $user->pertanyaan_tempat,
                'pertanyaan_pengalaman' => $user->pertanyaan_pengalaman,
            ]
        ]);
    }

    public function validasi_pertanyaan(Request $request)
    {
        $user = UserModel::where('username', $request->username)->first();

        if (!$user) {
            return response()->json(['status' => false, 'message' => 'Username tidak ditemukan']);
        }

        $valid =
            strtolower(trim($request->jawaban_masa_kecil)) === strtolower(trim($user->jawaban_masa_kecil)) &&
            strtolower(trim($request->jawaban_keluarga)) === strtolower(trim($user->jawaban_keluarga)) &&
            strtolower(trim($request->jawaban_tempat)) === strtolower(trim($user->jawaban_tempat)) &&
            strtolower(trim($request->jawaban_pengalaman)) === strtolower(trim($user->jawaban_pengalaman));

        if ($valid) {
            return response()->json([
                'status' => true,
                'redirect' => url('/reset-password/' . $user->username)
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Satu atau lebih jawaban tidak cocok.'
        ]);
    }


    public function reset_password(Request $request)
    {
        $request->validate([
            'password_baru' => 'required|min:6',
            'konfirmasi_password_baru' => 'required|same:password_baru'
        ]);

        $username = $request->input('username');
        $user = UserModel::where('username', $username)->first();
        if (!$user) {
            return redirect()->back()->withErrors(['Username tidak ditemukan']);
        }

        // Update password
        $user->password = Hash::make($request->password_baru);
        if ($user->save()) {
            return response()->json([
                'status' => true,
                'message' => 'Password berhasil direset. Silakan login dengan password baru.'
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan password baru.'
            ]);
        }
    }
}

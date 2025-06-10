<?php

namespace App\Http\Controllers;

use App\Models\laporanModel;
use App\Models\RuanganModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class TeknisiController extends Controller
{
    public function profile()
    {
        $breadcrumb = (object) [
            'title' => 'Profile',
            'list' => ['Detail Profile']
        ];

        $page = (object) [
            'title' => 'Profile',
            'subtitle' => 'Detail Profile'
        ];

        $activeMenu = 'profile';

        $teknisi = UserModel::find(Auth::user()->pengguna_id);

        return view('teknisi.profile', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'teknisi' => $teknisi]);
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'prodi' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'no_telp' => 'nullable|string|max:20'
        ]);

        // Hanya update field yang diisi
        $user = UserModel::find(auth()->user()->pengguna_id);
        foreach ($validated as $key => $value) {
            if ($value !== null) {
                $user->$key = $value;
            }
        }
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'nullable',
            'new_password' => 'nullable|string|min:8|confirmed'
        ]);

        // Jika ada input password baru
        if ($request->filled('new_password')) {
            // Verifikasi password lama hanya jika diisi
            if ($request->filled('current_password') && !Hash::check($request->current_password, auth()->user()->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
            }

            $user = UserModel::find(auth()->user()->pengguna_id);
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
        }

        return back()->with('success', 'password berhasil diubah!');
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $user = UserModel::findOrFail(Auth::id());
            $file = $request->file('foto');

            // Nama file unik
            $filename = $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/foto_profil', $filename);

            // Hapus foto lama kalau ada
            if ($user->foto_profil && Storage::exists('public/foto_profil/' . $user->foto_profil)) {
                Storage::delete('public/foto_profil/' . $user->foto_profil);
            }

            // Simpan nama file ke DB
            $user->foto_profil = $filename;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Foto berhasil diupload',
                'foto_profil' => asset('storage/foto_profil/' . $filename),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Upload gagal',
                'msgField' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function penugasan()
    {
        $breadcrumb = (object) [
            'title' => 'Data Penugasan',
            'list' => ['Data Penugasan']
        ];

        $page = (object) [
            'title' => 'Data Penugasan',
            'subtitle' => 'Data Penugasan Yang Harus Dikerjakan'
        ];
        $teknisi_id = Auth::id();

        $activeMenu = 'penugasan';
        $penugasan = LaporanModel::where('teknisi_id', $teknisi_id)->where('status', 'diperbaiki')->get();
        $revisi = LaporanModel::where('teknisi_id', $teknisi_id)->where('status', 'revisi')->get();
        // $ruangan = RuanganModel::where('ruangan_id',39)->get();
        // dd($revisi,$ruangan);
        return view('teknisi.penugasan', compact('penugasan', 'revisi', 'breadcrumb', 'page', 'activeMenu'));
    }

    public function detail_penugasan($id)
    {
        $laporan = LaporanModel::findOrFail($id);

        $breadcrumb = (object) [
            'title' => 'Data Penugasan',
            'list' => ['Data Penugasan']
        ];

        $page = (object) [
            'title' => 'Detail Penugasan',
            'subtitle' => 'Informasi lengkap mengenai penugasan'
        ];
        return view('teknisi.detail_penugasan', compact('laporan', 'breadcrumb', 'page'));
    }

    public function ajukanKeSarpras(string $id, Request $request)
    {
        $request->validate([
            'foto_pengerjaan' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $laporan = LaporanModel::findOrFail($id);

            // Upload file
            if ($request->hasFile('foto_pengerjaan')) {
                $path = $request->file('foto_pengerjaan')->store('foto_pengerjaan', 'public');
                $laporan->foto_pengerjaan = $path;
            }

            $laporan->status = 'telah diperbaiki';
            $laporan->save();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil diajukan ke sarpras.'
                ]);
            }

            return redirect()->back()->with('success', 'Laporan berhasil diajukan ke sarpras.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal mengajukan laporan: ' . $e->getMessage());
        }
    }


    public function riwayat_penugasan()
    {
        $breadcrumb = (object) [
            'title' => 'Data Riwayat Penugasan',
            'list' => ['Data Riwayat Penugasan']
        ];
        $page = (object) [
            'title' => 'Data Riwayat Penugasan',
            'subtitle' => 'Data Riwayat Penugasan Yang Telah Dikerjakan'
        ];
        $activeMenu = 'riwayat_penugasan';
        $teknisi_id = Auth::id();

        $laporan = LaporanModel::whereIn('status', ['selesai', 'telah diperbaiki'])
            ->where('teknisi_id', $teknisi_id)
            ->get();
        return view('teknisi.riwayat_penugasan', compact('laporan', 'breadcrumb', 'page', 'activeMenu'));
    }

    public function detail_riwayat_penugasan($id)
    {
        $laporan = LaporanModel::findOrFail($id);

        $breadcrumb = (object) [
            'title' => 'Data Riwayat Penugasan',
            'list' => ['Data Riwayat Penugasan']
        ];

        $page = (object) [
            'title' => 'Detail Laporan',
            'subtitle' => 'Informasi lengkap mengenai riwayat penugasan'
        ];

        return view('teknisi.detail_riwayat_penugasan', compact('laporan', 'breadcrumb', 'page'));
    }
}

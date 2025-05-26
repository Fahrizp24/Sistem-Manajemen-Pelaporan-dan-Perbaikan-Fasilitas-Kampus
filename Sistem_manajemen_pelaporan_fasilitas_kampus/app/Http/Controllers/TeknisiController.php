<?php

namespace App\Http\Controllers;

use App\Models\laporanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;

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

    public function update_profile(Request $request)
    {
        $id = Auth::user()->pengguna_id;

        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email,' . $id . ',pengguna_id',
            'password' => 'required|string|max:15',
            'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $teknisi = UserModel::findOrFail($id);

        // Update field biasa
        $teknisi->nama = $request->nama;
        $teknisi->email = $request->email;
        $teknisi->password = bcrypt($request->password); // pastikan di-hash jika perlu

        // Jika ada upload file baru
        if ($request->hasFile('foto_profil')) {
            $filename = $teknisi->pengguna_id . '.' . $request->file('foto_profil')->getClientOriginalExtension();
            $newPath = 'public/foto_profil/' . $filename;

            // Jika sebelumnya bukan 'default', hapus file lama
            if ($teknisi->foto_profil && $teknisi->foto_profil !== 'default') {
                $oldPath = storage_path('app/public/foto_profil/' . $teknisi->foto_profil);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Simpan file baru
            $request->file('foto_profil')->storeAs('public/foto_profil', $filename);
            $teknisi->foto_profil = $filename;
        }

        $teknisi->save();

        return response()->json([
            'status' => true,
            'message' => 'Profil berhasil diubah',
            'data' => [
                'foto' => asset('storage/foto_profil/' . $teknisi->foto_profil)
            ]
        ]);
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
        $laporan = LaporanModel::where('teknisi_id', $teknisi_id)->where('status', 'diperbaiki')->get();

        return view('teknisi.penugasan', compact('laporan', 'breadcrumb', 'page', 'activeMenu'));
    }

    public function detail_penugasan($id)
    {
        $laporan = LaporanModel::findOrFail($id);

        $breadcrumb = (object) [
            'title' => 'Data Penugasan',
            'list' => ['Data Penugasan']
        ];

        $page = (object)[
            'title' => 'Detail Penugasan',
            'subtitle' => 'Informasi lengkap mengenai penugasan'
        ];
        return view('teknisi.detail_penugasan', compact('laporan', 'breadcrumb', 'page'));
    }
    
    public function ajukanKeSarpras($id)
{
        $laporan = LaporanModel::findOrFail($id);

        // Misalnya hanya ubah status
        $laporan->status = 'telah diperbaiki';
        $laporan->save();

        return redirect('/teknisi/penugasan')->with('success', 'Data berhasil disimpan.');
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

        $laporan = LaporanModel::whereIn('status', ['selesai','telah diperbaiki'])
            ->where('teknisi_id', $teknisi_id)
            ->get();
        return view('teknisi.riwayat_penugasan', compact('laporan', 'breadcrumb', 'page', 'activeMenu'));
    }

    public function detail_riwayat_penugasan($id)
    {
        $laporan = LaporanModel::findOrFail($id);

        $page = (object)[
            'title' => 'Detail Laporan',
            'subtitle' => 'Informasi lengkap mengenai laporan fasilitas'
        ];

        return view('teknisi.detail_riwayat_penugasan', compact('laporan', 'page'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UserModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\FasilitasModel;
use App\Models\GedungModel;
use App\Models\LaporanModel;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $admin = UserModel::all();
        return view('admin.index', compact('admin'));
    }

    public function laporan()
    {
        $laporan = DB::table('laporan')->get();
        $breadcrumb = (object) [
            'title' => 'Laporan',
            'list' => ['Admin Laporan']
        ];

        $page = (object) [
            'title' => 'Laporan',
            'subtitle' => 'List Laporan Fasilitas'
        ];

        $activeMenu = 'laporan';
        return view('admin.laporan', ['laporan' => $laporan, 'breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function profile()
    {
        $breadcrumb = (object) [
            'title' => 'Profile',
            'list' => ['Detail Profile']
        ];

        $page = (object) [
            'title' => 'Profile'
        ];

        $activeMenu = 'profile';

        return view('admin.profile', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
        // $admin = UserModel::all();
        // return view('admin.profile', compact('admin'));
    }

    function kelola_pengguna()
    {
        $breadcrumb = (object) [
            'title' => 'Pengguna',
            'list' => ['Kelola Pengguna']
        ];

        $page = (object) [
            'title' => 'Akun Pengguna',
            'subtitle' => 'Kelola List Akun Pengguna'
        ];

        $pengguna = UserModel::all();
        return view('admin.kelola_pengguna', compact('pengguna', 'breadcrumb', 'page'));
    }


    public function create_ajax()
    {
        return view('admin.pengguna.create_ajax');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_ajax(Request $request)
    {
        $rules = [
            'username' => 'required|string',
            'nama' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'peran' => 'required|in:admin,sarpras,pelapor,teknisi',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ], 422); // Tambahkan status code 422 untuk validation error
        }

        $pengguna = new UserModel();
        $pengguna->username = $request->username;
        $pengguna->nama = $request->nama;
        $pengguna->email = $request->email;
        $pengguna->password = Hash::make($request->password);
        $pengguna->peran = $request->peran;
        $pengguna->save();

        return response()->json([
            'status' => true,
            'message' => 'Pengguna berhasil ditambahkan.'
        ]);

    }


    /**
     * Display the specified resource.
     */
    public function show_pengguna()
    {
        $admin = UserModel::all();
        return view('admin.show_pengguna', compact('admin'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit_pengguna($id)
    {
        $user = UserModel::findOrFail($id);
        return view('admin.pengguna.edit_ajax', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_pengguna(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email,' . $id . ',pengguna_id',
            'password' => 'nullable|string|min:6',
            'peran' => 'required|string|in:admin,sarpras,teknisi',
            // 'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = UserModel::findOrFail($id);
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->identitas = $request->identitas;
        $user->peran = $request->peran;

        // Jika ada field password dikirim dan tidak kosong
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Data pengguna berhasil diperbarui.'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pengguna = UserModel::find($id); // Ganti UserModel dengan model yang sesuai

        if (!$pengguna) {
            return redirect()->route('admin.pengguna')->with('error', 'Pengguna tidak ditemukan.');
        }

        $pengguna->delete();

        return redirect()->route('admin.pengguna')->with('success', 'Pengguna berhasil dihapus.');
    }

    function kelola_fasilitas()
    {
        $breadcrumb = (object) [
            'title' => 'Fasilitas',
            'list' => ['Kelola Fasilitas']
        ];

        $page = (object) [
            'title' => 'Fasilitas',
            'subtitle' => 'Kelola List Fasilitas'
        ];

        $fasilitas = FasilitasModel::all();
        return view('admin.kelola_fasilitas', compact('fasilitas', 'breadcrumb', 'page'));
    }

    function kelola_gedung()
    {
        $breadcrumb = (object) [
            'title' => 'Gedung',
            'list' => ['Kelola Gedung']
        ];

        $page = (object) [
            'title' => 'Gedung',
            'subtitle' => 'Kelola List Gedung'
        ];

        $gedung = GedungModel::all();
        return view('admin.kelola_gedung', compact('gedung', 'breadcrumb', 'page'));
    }

    function sistem_rekomendasi()
    {
        $breadcrumb = (object) [
            'title' => 'Sistem Rekomendasi',
            'list' => ['Sistem Rekomendasi']
        ];

        $page = (object) [
            'title' => 'Sistem Rekomendasi',
            'subtitle' => 'Sistem Rekomendasi'
        ];

        $activeMenu = 'sistem_rekomendasi';
        return view('admin.sistem_rekomendasi', compact('breadcrumb', 'page', 'activeMenu'));
    }

    function laporan_periodik()
    {
        $breadcrumb = (object) [
            'title' => 'Laporan Periodik',
            'list' => ['Laporan Periodik']
        ];

        $page = (object) [
            'title' => 'Laporan Periodik',
            'subtitle' => 'Detail Laporan Periodik'
        ];

        $laporan = LaporanModel::where('status', 'selesai');

        $activeMenu = 'laporan_periodik';
        return view('admin.laporan_periodik', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function statistik()
    {
        $breadcrumb = (object) [
            'title' => 'Statistik',
            'list' => ['Statistik Laporan']
        ];

        $page = (object) [
            'title' => 'Statistik',
            'subtitle' => 'Statistik Laporan'
        ];

        $activeMenu = 'statistik';
        return view('admin.statistik', compact('breadcrumb', 'page', 'activeMenu'));
    }

}

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


    public function create_pengguna()
    {
        return view('admin.pengguna.create_ajax');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function ajaxStorePengguna(Request $request)
{
    $validator = Validator::make($request->all(), [
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:pengguna,email',
        'identitas' => 'required|string|max:50',
        'kata_sandi' => 'required|string|min:8',
        'peran' => 'required|in:admin,sarpras,teknisi',
    ]);

    if ($validator->fails()) {
        return response()->json([
            'status' => false,
            'message' => 'Validasi gagal!',
            'msgField' => $validator->errors()
        ]);
    }

    // Simpan data
    $pengguna = new UserModel();
    $pengguna->nama = $request->nama;
    $pengguna->email = $request->email;
    $pengguna->identitas = $request->identitas;
    $pengguna->password = bcrypt($request->kata_sandi);
    $pengguna->peran = $request->peran;
    $pengguna->save();

    return response()->json([
        'status' => true,
        'message' => 'Pengguna berhasil ditambahkan!'
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
        'nama' => 'required|string|max:255',
        'email' => 'required|email|unique:pengguna,email,' . $id . ',pengguna_id',
        'kata_sandi' => 'nullable|string|min:8',
        'peran' => 'required|string|in:admin,sarpras,teknisi',
        'foto_profil' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $pengguna = UserModel::findOrFail($id);

    // Update field satu per satu
    $pengguna->nama = $request->nama;
    $pengguna->email = $request->email;
    $pengguna->identitas = $request->identitas;
    $pengguna->peran = $request->peran;

    // Jika password diisi, update dan hash
    if ($request->filled('kata_sandi')) {
        $pengguna->kata_sandi = Hash::make($request->kata_sandi);
    }

    // Jika ada file foto yang diupload
    if ($request->hasFile('foto_profil')) {
        $file = $request->file('foto_profil');
        $filename = time() . '_' . $file->getClientOriginalName();
        $file->move(public_path('uploads/foto_profil'), $filename);
        $pengguna->foto_profil = $filename;
    }

    if ($pengguna->save()) {
        return redirect()->route('admin.pengguna')->with('success', 'Pengguna berhasil diperbarui.');
    } else {
        return redirect()->route('admin.pengguna')->with('error', 'Gagal memperbarui pengguna.');
    }
}


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $admin = UserModel::findOrFail($id);
        $admin->delete();

        return redirect()->route('admin.index')->with('success', 'UserModel deleted successfully.');
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

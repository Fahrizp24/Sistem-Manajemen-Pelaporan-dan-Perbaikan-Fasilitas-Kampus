<?php

namespace App\Http\Controllers;

use App\Models\FasilitasModel;
use App\Models\GedungModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UserModel;
use App\Models\LaporanModel;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PelaporController extends Controller
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

        $pelapor = UserModel::find(Auth::user()->pengguna_id);

        return view('pelapor.profile', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'pelapor' => $pelapor]);
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

        $pelapor = UserModel::findOrFail($id);

        // Update field biasa
        $pelapor->nama = $request->nama;
        $pelapor->email = $request->email;
        $pelapor->password = bcrypt($request->password); // pastikan di-hash jika perlu

        // Jika ada upload file baru
        if ($request->hasFile('foto_profil')) {
            $filename = $pelapor->pengguna_id . '.' . $request->file('foto_profil')->getClientOriginalExtension();
            $newPath = 'public/foto_profil/' . $filename;

            // Jika sebelumnya bukan 'default', hapus file lama
            if ($pelapor->foto_profil && $pelapor->foto_profil !== 'default') {
                $oldPath = storage_path('app/public/foto_profil/' . $pelapor->foto_profil);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Simpan file baru
            $request->file('foto_profil')->storeAs('public/foto_profil', $filename);
            $pelapor->foto_profil = $filename;
        }

        $pelapor->save();

        return response()->json([
            'status' => true,
            'message' => 'Profil berhasil diubah',
            'data' => [
                'foto' => asset('storage/foto_profil/' . $pelapor->foto_profil)
            ]
        ]);
    }

    public function laporkan_kerusakan()
    {
        $breadcrumb = (object) [
            'title' => 'Laporan Kerusakan',
            'list' => ['Formulir Laporan Kerusakan Fasilitas']
        ];

        $page = (object) [
            'title' => 'Laporan Kerusakan Fasilitas',
            'subtitle' => 'Formulir Laporan Kerusakan Fasilitas'
        ];

        $gedung = GedungModel::all();
        $activeMenu = 'Laporkan Kerusakan';
        return view('pelapor.laporkan_kerusakan', compact('gedung', 'breadcrumb', 'page', 'activeMenu'));
    }

    public function get_fasilitas_by_gedung(Request $request)
    {
        $request->validate([
            'gedung_id' => 'required|exists:gedung,gedung_id',
        ]);

        $fasilitas = FasilitasModel::where('gedung_id', $request->gedung_id)->get();
        return response()->json($fasilitas);
    }

    public function store_laporan(Request $request)
    {
        $request->validate([
            'gedung_id' => 'required|exists:gedung,gedung_id',
            'fasilitas_id' => 'required|exists:fasilitas,fasilitas_id',
            'deskripsi' => 'required|string|max:500',
            'foto' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        try {
            $file = $request->file('foto');
            $filename = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/foto_laporan', $filename);

            $laporan = new LaporanModel();
            $laporan->fasilitas_id = $request->fasilitas_id;
            $laporan->pelapor_id = auth()->user()->pengguna_id;
            $laporan->deskripsi = $request->deskripsi;
            $laporan->foto = $filename;
            $laporan->status = 'diajukan';
            $laporan->save();

            return response()->json([
                'status' => true,
                'message' => 'Laporan berhasil dibuat'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal membuat laporan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function laporan_saya()
    {
        $breadcrumb = (object) [
            'title' => 'Pelapor',
            'list' => ['Laporan Kerusakan Fasilitas']
        ];

        $page = (object) [
            'title' => 'Laporan Saya',
            'subtitle' => 'Riwayat Laporan Kerusakan Fasilitas'
        ];

        $activeMenu = 'Laporkan Kerusakan';

        $laporan_saya = LaporanModel::with('fasilitas.gedung')
            ->where('pelapor_id', auth()->user()->pengguna_id)
            ->get();

        return view('pelapor.laporan_saya', compact('laporan_saya', 'breadcrumb', 'page', 'activeMenu'));
    }

    public function show_laporan_saya(string $id)
    {
        $laporan = LaporanModel::with([
            'pelapor',          // Pengguna yang melapor
            'fasilitas.gedung', // Fasilitas + gedung terkait
            'sarpras',    // Admin/SARPRAS yang menugaskan
            'teknisi'           // Teknisi yang ditugaskan
        ])->find($id); // Ganti $id dengan ID laporan yang ingin ditampilkan
        
        return view('pelapor.show_detail_laporan', [
            'laporan' => $laporan,
            'page' => (object) ['title' => 'Detail Laporan']
        ]);
    }

}

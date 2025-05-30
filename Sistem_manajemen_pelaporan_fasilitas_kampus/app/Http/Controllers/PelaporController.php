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
use Illuminate\Support\Facades\Hash;

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
    $user = auth()->user();
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
        
        auth()->user()->update([
            'password' => Hash::make($request->new_password)
        ]);
    }

    return back()->with('success', 'Profil berhasil diperbarui!');
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
        $user = UserModel::find(Auth::user()->pengguna_id);
        $activeMenu = 'Laporkan Kerusakan';
        return view('pelapor.laporkan_kerusakan', compact('gedung', 'breadcrumb', 'page', 'activeMenu','user'));
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

<?php

namespace App\Http\Controllers;

use App\Models\FasilitasModel;
use App\Models\GedungModel;
use App\Models\LantaiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UserModel;
use App\Models\LaporanModel;
use App\Models\RuanganModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

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
        return view('pelapor.laporkan_kerusakan', compact('gedung', 'breadcrumb', 'page', 'activeMenu', 'user'));
    }

    public function get_lantai_by_gedung(Request $request)
    {
        $request->validate([
            'gedung_id' => 'required|exists:gedung,gedung_id',
        ]);

        $lantai = LantaiModel::where('gedung_id', $request->gedung_id)->get();
        return response()->json($lantai);
    }
    public function get_ruangan_by_lantai(Request $request)
    {
        $request->validate([
            'lantai_id' => 'required|exists:lantai,lantai_id',
        ]);

        $ruangan = RuanganModel::where('lantai_id', $request->lantai_id)->get();
        return response()->json($ruangan);
    }
    public function get_fasilitas_by_ruangan(Request $request)
    {
        $request->validate([
            'ruangan_id' => 'required|exists:ruangan,ruangan_id',
        ]);

        $fasilitas = FasilitasModel::where('ruangan_id', $request->ruangan_id)->get();
        return response()->json($fasilitas);
    }

    public function store_laporan(Request $request)
    {
        $request->validate([
            'gedung_id' => 'required|exists:gedung,gedung_id',
            'lantai_id' => 'required|exists:lantai,lantai_id',
            'ruangan_id' => 'required|exists:ruangan,ruangan_id',
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

        $laporan_saya = LaporanModel::with('fasilitas.ruangan.lantai.gedung')
            ->where('pelapor_id', auth()->user()->pengguna_id)
            ->orderBy('created_at', 'desc') // urutkan berdasarkan waktu terbaru
            ->get();


        $fasilitas = FasilitasModel::with('ruangan.lantai')->where('fasilitas_id', 2)->get();

        return view('pelapor.laporan_saya', compact('laporan_saya', 'breadcrumb', 'page', 'activeMenu'));
    }

    public function show_laporan_saya(string $id)
    {
        $laporan = LaporanModel::with([
            'pelapor',         
            'fasilitas', 
            'sarpras',  
            'teknisi',  
        ])->find($id); 

        return view('pelapor.show_detail_laporan', [
            'laporan' => $laporan,
            'page' => (object) ['title' => 'Detail Laporan']
        ]);
    }

    
}

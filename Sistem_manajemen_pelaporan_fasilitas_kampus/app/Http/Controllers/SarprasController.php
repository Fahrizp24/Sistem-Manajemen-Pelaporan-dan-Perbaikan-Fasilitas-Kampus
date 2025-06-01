<?php

namespace App\Http\Controllers;

use App\Models\CrispModel;
use App\Models\KriteriaModel;
use App\Models\LaporanModel;
use App\Models\TeknisiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\SpkModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SarprasController extends Controller
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

        $sarpras = UserModel::find(Auth::user()->pengguna_id);

        return view('sarpras.profile', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'sarpras' => $sarpras]);
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

    /**
     * Display a listing of the resource.
     */
    public function list_laporan()
    {
        $breadcrumb = (object) [
            'title' => 'Data Laporan',
            'list' => ['Data Laporan Masuk']
        ];

        $page = (object) [
            'title' => 'Data Laporan',
            'subtitle' => 'Data Laporan Masuk Dari Pelapor dan Admin'
        ];

        $activeMenu = 'penugasan';
        $laporan_masuk_pelapor = LaporanModel::where('status', 'diajukan')->get();
        $laporan_masuk_admin = LaporanModel::where('status', 'memilih teknisi')->get();
        $laporan_masuk_teknisi = LaporanModel::where('status', 'telah diperbaiki')->get();

        return view('sarpras.laporan_masuk', compact('breadcrumb', 'page', 'activeMenu', 'laporan_masuk_pelapor', 'laporan_masuk_admin', 'laporan_masuk_teknisi'));
    }

    public function show_laporan(string $id)
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

        $teknisi = UserModel::where('peran', 'teknisi')->get();

        $source = request()->query('source', 'default');
        $kriteria = KriteriaModel::orderBy('kriteria_id')->get();
        $crisp = CrispModel::orderBy('kriteria_id')->orderBy('poin')->get();
        return view('sarpras.detail_laporan', compact('laporan', 'breadcrumb', 'page', 'source', 'teknisi', 'kriteria', 'crisp'));
    }

    public function terima(string $id, Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'kriteria' => 'required|array',
                'kriteria.*' => 'required|numeric'
            ]);

            // Simpan data SPK 
            $spk = SpkModel::create([
                'laporan_id' => $id
            ]);

            // Siapkan data untuk relasi many-to-many
            $kriteriaData = [];
            foreach ($request->kriteria as $kriteriaId => $nilai) {
                $kriteriaData[$kriteriaId] = ['nilai' => $nilai];
            }

            // Gunakan sync() atau attach() setelah SPK tersimpan
            $spk->kriteria()->sync($kriteriaData);

            // Update status laporan
            LaporanModel::findOrFail($id)->update(['status' => 'diterima']);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil diterima.',
                    'data' => $spk->load('kriteria')
                ]);
            }

            return redirect()->back()->with('success', 'Laporan berhasil diterima.');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menerima laporan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal menerima laporan: ' . $e->getMessage());
        }
    }

    public function tolak(string $id, Request $request)
    {
        try {
            $laporan = LaporanModel::findOrFail($id);
            $laporan->status = 'tidak diterima';
            $laporan->save();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil ditolak.'
                ]);
            }

            return redirect()->back()->with('success', 'Laporan berhasil ditolak.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menolak laporan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal menolak laporan: ' . $e->getMessage());
        }
    }

    public function pilih_teknisi(string $id, Request $request)
    {
        try {
            $laporan = LaporanModel::findOrFail($id);
            $laporan->teknisi_id = $request->teknisi;
            $laporan->status = 'diperbaiki';
            $laporan->ditugaskan_oleh = Auth::user()->pengguna_id;
            $laporan->save();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Teknisi berhasil dipilih.'
                ]);
            }
            return redirect()->back()->with('success', 'Teknisi berhasil dipilih.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memilih teknisi: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal memilih teknisi: ' . $e->getMessage());
        }
    }

    public function selesaikan(string $id, Request $request)
    {
        try {
            $laporan = LaporanModel::findOrFail($id);
            if ($request->hasil === 'selesai') {
                $laporan->status = 'selesai';
                $laporan->save();

                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Laporan berhasil diselesaikan.'
                    ]);
                }
            } else if ($request->hasil === 'revisi') {
                $laporan->status = 'revisi';
                $laporan->save();

                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Laporan direvisi dan dikirimkan kembali ke teknisi.'
                    ]);
                }
            }
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyelesaikan laporan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Gagal menyelesaikan laporan: ' . $e->getMessage());
        }
    }

    public function sistem_pendukung_keputusan()
    {
        $breadcrumb = (object) [
            'title' => 'Sistem Pendukung Keputusan',
            'list' => ['Sistem Pendukung Keputusan']
        ];
        $page = (object) [
            'title' => 'Sistem Pendukung Keputusan',
            'subtitle' => 'Sistem Pendukung Keputusan'
        ];
        return view('sarpras.sistem_pendukung_keputusan', compact('breadcrumb', 'page'));
    }

    public function data_kriteria(Request $request)
    {
        if ($request->ajax()) {
            $data = KriteriaModel::select(['kriteria_id', 'kode', 'nama', 'bobot', 'jenis', 'deskripsi'])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $btn = '
                    <button type="button" class="btn btn-sm btn-primary btnEditKriteria" data-id="' . $row->kriteria_id . '">Edit</button>
                    <form action="' . url('sarpras.destroy_kriteria', $row->kriteria_id) . '" id="formDeleteKriteria" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }

    public function data_crisp(Request $request)
    {
        if ($request->ajax()) {
            $data = CrispModel::with('kriteria')->select(['crisp_id', 'kriteria_id', 'judul', 'deskripsi', 'poin'])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $btn = '
                    <button type="button" class="btn btn-sm btn-primary btnEditKriteria" data-id="' . $row->crisp_id . '">Edit</button>
                    <form action="' . route('sarpras.destroy_crisp', $row->crisp_id) . '" id="formDeleteKriteria" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }

    public function statistik()
    {
        $breadcrumb = (object) [
            'title' => 'Statistik',
            'list' => ['Statistik']
        ];
        $page = (object) [
            'title' => 'Statistik',
            'subtitle' => 'Statistik'
        ];
        return view('sarpras.statistik', compact('breadcrumb', 'page'));
    }

    public function ajukan_laporan()
    {
        $breadcrumb = (object) [
            'title' => 'Ajukan Laporan',
            'list' => ['Ajukan Laporan']
        ];

        $page = (object) [
            'title' => 'Ajukan Laporan',
            'subtitle' => 'Ajukan Laporan Kerusakan Sarana dan Prasarana'
        ];

        $activeMenu = 'laporan';

        $kriteria = KriteriaModel::all();

        return view('sarpras.ajukan_laporan', compact('breadcrumb', 'page', 'activeMenu', 'kriteria'));
    }

    public function data_laporan(Request $request)
    {
        $data = LaporanModel::with(['fasilitas.gedung', 'spk.kriteria'])->where('status', 'diterima')->get();
        $allKriteria = KriteriaModel::all();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('gedung', fn($row) => $row->fasilitas->gedung->nama ?? '-')
            ->addColumn('fasilitas', fn($row) => $row->fasilitas->nama ?? '-')
            ->addColumn('kriteria', function ($row) use ($allKriteria) {
                $columns = [];

                $spk = $row->spk;

                foreach ($allKriteria as $k) {
                    $nilai = '-';

                    if ($spk) {
                        $match = $spk->kriteria->where('pivot.kriteria_id', $k->kriteria_id)->first();
                        $nilai = $match ? $match->pivot->nilai : '-';
                    }

                    $columns['kriteria_' . $k->kriteria_id] = $nilai;
                }

                return $columns;
            })
            ->addColumn('aksi', function ($row) {
                return '<button onclick="modalAction(\'' . url('sarpras/laporan_masuk/' . $row->laporan_id) . '?source=ajukan\')" class="btn btn-sm btn-info">Detail</button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }



    private function normalisasiVektor(array $data): array
    {
        // Ambil semua nama kriteria dari alternatif pertama
        $kriteria = array_keys($data[0]['kriteria']);

        // 1. Hitung penyebut (akar jumlah kuadrat per kriteria)
        $penyebut = [];
        foreach ($kriteria as $k) {
            $penyebut[$k] = sqrt(array_sum(array_map(function ($d) use ($k) {
                return pow($d['kriteria'][$k], 2);
            }, $data)));
        }

        // 2. Hitung nilai normalisasi per alternatif
        $normalisasi = [];
        foreach ($data as $i => $alt) {
            $normalisasi[$i]['judul'] = $alt['judul'];
            $normalisasi[$i]['laporan_id'] = $alt['laporan_id']; // tambahkan laporan_id jika ada
            foreach ($kriteria as $k) {
                $normalisasi[$i]['kriteria'][$k] = $alt['kriteria'][$k] / ($penyebut[$k] ?: 1);
            }
        }
        return $normalisasi;
    }

    private function nilaiTerbobot(array $data, array $bobot): array
    {
        $hasil = [];

        foreach ($data as $i => $alt) {
            $hasil[$i]['judul'] = $alt['judul'];
            $hasil[$i]['laporan_id'] = $alt['laporan_id'] ?? null;
            foreach ($alt['kriteria'] as $namaKriteria => $nilaiNormal) {
                $bobotKriteria = $bobot[$namaKriteria] ?? 0;
                $hasil[$i]['kriteria'][$namaKriteria] = $nilaiNormal * $bobotKriteria;
            }
        }

        return $hasil;
    }

    private function solusiIdeal(array $data, array $jenis): array
    {
        $kriteriaList = array_keys($data[0]['kriteria']);
        $idealPositif = [];
        $idealNegatif = [];

        foreach ($kriteriaList as $kriteria) {
            $nilai = array_column(array_column($data, 'kriteria'), $kriteria);
            if (($jenis[$kriteria] ?? 'benefit') === 'benefit') {
                $idealPositif[$kriteria] = max($nilai);
                $idealNegatif[$kriteria] = min($nilai);
            } else {
                $idealPositif[$kriteria] = min($nilai);
                $idealNegatif[$kriteria] = max($nilai);
            }
        }
        return [$idealPositif, $idealNegatif];
    }

    private function jarakSolusiIdeal(array $data, array $idealPositif, array $idealNegatif): array
    {
        $result = [];

        foreach ($data as $i => $alt) {
            $dPlus = 0;
            $dMinus = 0;

            foreach ($alt['kriteria'] as $kriteria => $nilai) {
                $dPlus += pow($idealPositif[$kriteria] - $nilai, 2);
                $dMinus += pow($nilai - $idealNegatif[$kriteria], 2);
            }

            $result[] = [
                'laporan_id' => $alt['laporan_id'] ?? null,
                'judul' => $alt['judul'],
                'D_plus' => round(sqrt($dPlus), 4),
                'D_minus' => round(sqrt($dMinus), 4),
            ];
        }

        return $result;
    }

    private function hitungPreferensi(array $jarak): array
    {
        $hasil = [];

        foreach ($jarak as $item) {
            $Dplus = $item['D_plus'];
            $Dminus = $item['D_minus'];

            // Perhitungan nilai preferensi V berdasarkan rumus Vi = D- / (D+ + D-)
            $Vi = ($Dplus + $Dminus) == 0 ? 0 : $Dminus / ($Dplus + $Dminus);

            $hasil[] = [
                'laporan_id' => $item['laporan_id'],
                'judul' => $item['judul'],
                'D_plus' => round($Dplus, 4),
                'D_minus' => round($Dminus, 4),
                'V' => round($Vi, 4),
            ];
        }

        // Urutkan berdasarkan nilai V dari yang terkecil (semakin kecil V, semakin baik)
        usort($hasil, fn($a, $b) => $a['V'] <=> $b['V']);

        // Tambahkan peringkat
        foreach ($hasil as $i => &$r) {
            $r['ranking'] = $i + 1;
        }

        return $hasil;
    }



    public function proses_spk()
    {
        // Ambil semua laporan yang sudah diterima beserta relasi
        $laporans = LaporanModel::with(['fasilitas.gedung', 'spk.kriteria'])
            ->where('status', 'diterima')
            ->get();


        // Bentuk data matriks alternatif
        $data = [];
        $kriteria = [];

        foreach ($laporans as $laporan) {
            $judul = ($laporan->fasilitas->nama ?? '-') . ' - ' . ($laporan->fasilitas->gedung->nama ?? '-');
            $laporan_id = $laporan->laporan_id;
            $spk = $laporan->spk; // ambil SPK 

            if (!$spk) {
                continue; // skip laporan ini kalau tidak ada SPK-nya
            }

            $nilaiKriteria = [];

            foreach ($spk->kriteria as $item) {
                $nilaiKriteria[$item->nama] = (float) $item->pivot->nilai;

                if (!in_array($item->nama, $kriteria)) {
                    $kriteria[] = $item->nama;
                }
            }

            $data[] = [
                'laporan_id' => $laporan_id,
                'judul' => $judul,
                'kriteria' => $nilaiKriteria
            ];
        }

        $normalisasi = $this->normalisasiVektor($data);

        $bobotKriteria = [];
        foreach (KriteriaModel::all() as $k) {
            $bobotKriteria[$k->nama] = floatval($k->bobot);
            $jenisKriteria[$k->nama] = strtolower($k->jenis);
        }
        $terbobot = $this->nilaiTerbobot($normalisasi, $bobotKriteria);


        [$idealPositif, $idealNegatif] = $this->solusiIdeal($terbobot, $jenisKriteria);

        $jarak = $this->jarakSolusiIdeal($terbobot, $idealPositif, $idealNegatif);

        $hasilAkhir = $this->hitungPreferensi($jarak);

        return response()->json([
            'success' => true,
            'data' => $hasilAkhir
        ]);
    }

    public function proses_ajukan_laporan(string $id, Request $request)
    {
        try {
            $laporan = LaporanModel::findOrFail($id);
            $laporan->status = 'konfirmasi';
            $laporan->save();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil diajukan ke admin.'
                ]);
            }
            return redirect()->back()->with('success', 'Laporan berhasil diajukan ke admin.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengajukan laporan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal mengajukan laporan: ' . $e->getMessage());
        }
    }
}

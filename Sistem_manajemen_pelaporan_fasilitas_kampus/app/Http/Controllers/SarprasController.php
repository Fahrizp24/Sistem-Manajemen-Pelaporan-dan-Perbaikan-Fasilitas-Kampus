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

class SarprasController extends Controller
{
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
                return '<button onclick="modalAction(\'' . url('laporan.edit', $row->id) . '\')" class="btn btn-sm btn-info">Edit</button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
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
        
            $spk = $laporan->spk; // ambil SPK pertama jika ada
        
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
                'judul' => $judul,
                'kriteria' => $nilaiKriteria
            ];
        }
    
        // Hitung F* (nilai terbaik) dan F- (nilai terburuk)
        $f_star = [];
        $f_minus = [];
        foreach ($kriteria as $k) {
            $nilai = array_column(array_column($data, 'kriteria'), $k);
            $f_star[$k] = max($nilai);
            $f_minus[$k] = min($nilai);
        }
    
        // Hitung S, R, dan Q untuk setiap alternatif
        $S = $R = $Q = [];
        $v = 0.5; // parameter VIKOR (bisa diatur)
    
        foreach ($data as $i => $d) {
            $s = $r = 0;
            $temp = [];
    
            foreach ($d['kriteria'] as $k => $nilai) {
                $bobot = 1 / count($kriteria); // Bobot merata
                $f_star_val = $f_star[$k] ?: 1; // Hindari pembagian 0
    
                $temp[$k] = $bobot * (($f_star[$k] - $nilai) / ($f_star[$k] - $f_minus[$k] ?: 1));
    
                $s += $temp[$k];
                $r = max($r, $temp[$k]);
            }
    
            $S[$i] = $s;
            $R[$i] = $r;
        }
    
        // Hitung nilai Q
        $s_max = max($S); $s_min = min($S);
        $r_max = max($R); $r_min = min($R);
    
        foreach ($data as $i => $d) {
            $Q[$i] = $v * (($S[$i] - $s_min) / ($s_max - $s_min ?: 1)) + (1 - $v) * (($R[$i] - $r_min) / ($r_max - $r_min ?: 1));
        }
    
        // Gabungkan hasil ranking
        $result = [];
        foreach ($data as $i => $d) {
            $result[] = [
                'judul' => $d['judul'],
                'Q' => round($Q[$i], 4),
                'S' => round($S[$i], 4),
                'R' => round($R[$i], 4),
            ];
        }
    
        // Urutkan berdasarkan nilai Q terkecil (semakin kecil semakin baik)
        usort($result, fn($a, $b) => $a['Q'] <=> $b['Q']);
    
        // Tambahkan ranking
        foreach ($result as $i => &$r) {
            $r['ranking'] = $i + 1;
        }
    
         return response()->json([
            'success' => true,
            'data' => $result
        ]);
    }
    
}

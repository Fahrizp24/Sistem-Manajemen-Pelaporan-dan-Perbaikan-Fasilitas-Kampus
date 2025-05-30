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
        
        $teknisi = UserModel::where('peran','teknisi')->get();

        $source = request()->query('source', 'default');
        return view('sarpras.detail_laporan', compact('laporan', 'breadcrumb', 'page', 'source','teknisi'));
    }


    public function terima(string $id, Request $request)
    {
        try {
            $laporan = LaporanModel::findOrFail($id);
            $laporan->status = 'diterima';
            $laporan->save();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil diterima.'
                ]);
            }

            return redirect()->back()->with('success', 'Laporan berhasil diterima.');
        } catch (\Exception $e) {
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
            } else if($request->hasil === 'revisi') {
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
            $data = KriteriaModel::select(['kriteria_id','kode', 'nama', 'bobot', 'jenis', 'deskripsi'])->get();
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
            $data = CrispModel::with('kriteria')->select(['crisp_id','kriteria_id', 'judul', 'deskripsi', 'poin'])->get();
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

        $laporan = \App\Models\LaporanModel::where('status', 'diterima')->get();

        return view('sarpras.ajukan_laporan', compact('breadcrumb', 'page', 'activeMenu', 'laporan'));
    }

    public function proses_spk(Request $request)
{
    $laporanIds = $request->input('laporan_ids', []);

    // Validasi data
    if (empty($laporanIds)) {
        return response()->json(['error' => 'Tidak ada laporan yang dipilih.'], 400);
    }

    // Ambil laporan berdasarkan ID
    $laporan = LaporanModel::whereIn('id', $laporanIds)->get();

    // Proses SPK di sini (contoh dummy: skor random)
    $hasil = $laporan->map(function ($item) {
        return [
            'judul' => $item->judul,
            'skor' => rand(70, 100) / 100  // misal skor antara 0.70 s.d 1.00
        ];
    })->sortByDesc('skor')->values();

    return response()->json([
        'data' => $hasil
    ]);
}

    
}

<?php

namespace App\Http\Controllers;

use App\Models\LaporanModel;
use App\Models\TeknisiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;

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


    public function konfirmasi(string $id, Request $request)
    {
        try {
            $laporan = LaporanModel::findOrFail($id);
            $laporan->status = 'konfirmasi';
            $laporan->save();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil dikonfirmasi.'
                ]);
            }

            return redirect()->back()->with('success', 'Laporan berhasil dikonfirmasi.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengkonfirmasi laporan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal mengkonfirmasi laporan: ' . $e->getMessage());
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
}

<?php

namespace App\Http\Controllers;

use App\Models\LaporanModel;
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
        return view('sarpras.laporan_masuk', compact( 'breadcrumb', 'page', 'activeMenu', 'laporan_masuk_pelapor', 'laporan_masuk_admin', 'laporan_masuk_teknisi'));

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
        $source = request()->query('source', 'default');
        return view('sarpras.detail_laporan', compact('laporan', 'breadcrumb', 'page', 'source'));
    }

    
    public function konfirmasi(string $id, Request $request)
    {
        try {
            $laporan = LaporanModel::findOrFail($id);
            $laporan->status = 'konfirmasi';
            $laporan->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Laporan berhasil dikonfirmasi.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengkonfirmasi laporan: ' . $e->getMessage()
            ], 500);
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
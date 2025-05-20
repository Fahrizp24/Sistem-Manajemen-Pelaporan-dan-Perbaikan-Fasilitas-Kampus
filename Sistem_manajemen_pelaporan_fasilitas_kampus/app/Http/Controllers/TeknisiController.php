<?php

namespace App\Http\Controllers;

use App\Models\LaporanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;

class TeknisiController extends Controller
{
    /**
     * Display a listing of the resource.
    */
    public function list_laporan()
    {
        $breadcrumb = (object) [
            'title' => 'Data Penugasan',
            'list' => ['Data Penugasan']
        ];
    
        $page = (object) [
            'title' => 'Data Penugasan'
        ];
        $idTeknisi = Auth::id();

        $activeMenu = 'penugasan';
        $laporan = LaporanModel::where('status', 'dilaksanakan')
        ->where('teknisi_id', $idTeknisi)
        ->get();

        return view('teknisi.penugasan', compact('laporan', 'breadcrumb', 'page', 'activeMenu'));
    }

    public function riwayat_penugasan()
    {
        $idTeknisi = Auth::id();

        $laporan = LaporanModel::where('status', 'selesai')
        ->where('idTeknisi', $idTeknisi)
        ->get();        
        return view('pelapor.riwayat_penugasan',compact('laporan'));
    }

    public function edit($id)
    {
        $pelapor = UserModel::findOrFail($id);
        return view('pelapor.edit', compact('pelapor'));
    }

    public function updateLaporan()
    {
        $idTeknisi = Auth::id();
        $laporan = LaporanModel::where('status', 'dilaksanakan')
        ->where('idTeknisi', $idTeknisi)
        ->first();

        if ($laporan) {
            $laporan->status = 'selesai';
            $laporan->save();

            return response()->json([
                'status' => true,
                'message' => 'Status berhasil diubah',
            ]);
        } else {
            return response()->json([
                'status' => false,
                'message' => 'Laporan tidak ditemukan',
            ]);
        }
    }
}

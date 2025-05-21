<?php

namespace App\Http\Controllers;

use App\Models\laporanModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;

class TeknisiController extends Controller
{
    /**
     * Display a listing of the resource.
    */
    public function penugasan()
    {
        $breadcrumb = (object) [
            'title' => 'Data Penugasan',
            'list' => ['Data Penugasan']
        ];
    
        $page = (object) [
            'title' => 'Data Penugasan'
        ];
        $teknisi_id = Auth::id();

        $activeMenu = 'penugasan';
        $laporan = LaporanModel::where('status', 'sedang diperbaiki')
        ->where('teknisi_id', $teknisi_id)
        ->get();

        return view('teknisi.penugasan', compact('laporan', 'breadcrumb', 'page', 'activeMenu'));
    }

    public function riwayat_penugasan()
    {
        $teknisi_id = Auth::id();

        $laporan = LaporanModel::where('status', 'selesai')
        ->where('teknisi_id', $teknisi_id)
        ->get();        
        return view('teknisi.riwayat_penugasan',compact('laporan'));
    }

    public function edit($id)
    {
        $teknisi = UserModel::findOrFail($id);
        return view('teknisi.edit', compact('teknisi'));
    }

    public function update()
    {
        $teknisi_id = Auth::id();
        $laporan = LaporanModel::where('status', 'dilaksanakan')
        ->where('teknisi_id', $teknisi_id)
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
                'message' => 'laporan tidak ditemukan',
            ]);
        }
    }
}

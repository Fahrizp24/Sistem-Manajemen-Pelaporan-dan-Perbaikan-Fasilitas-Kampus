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
        ->where('idTeknisi', $idTeknisi)
        ->get();               return view('pelapor.penugasan', compact('laporan', 'breadcrumb', 'page', 'activeMenu', 'laporan'));

    }

    // public function profile()
    // {
    //     $breadcrumb = (object) [
    //         'title' => 'Profile',
    //         'list' => ['Detail Profile']
    //     ];
    
    //     $page = (object) [
    //         'title' => 'Profile'
    //     ];
    
    //     $activeMenu = 'profile';

    //     return view('pelapor.profile', ['breadcrumb' => $breadcrumb, 'page'=> $page,'activeMenu' => $activeMenu]);
    //     // $pelapor = UserModel::all();
    //     // return view('pelapor.profile', compact('pelapor'));
    // }

    /**
     * Display the specified resource.
     */
    public function riwayat_penugasan()
    {
        // Mengambil ID user yang sedang login
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
        ->find();

        if ($laporan) {
                $laporan->status = 'selesai';
                $laporan->save();

            return response()->json([
                'status' => true,
                'message' => 'Status berhasil diubah',
            ]);
        }else {
            return response()->json([
                'status' => false,
                'message' => 'Laporan tidak ditemukan',
            ]);
        }

    }
}
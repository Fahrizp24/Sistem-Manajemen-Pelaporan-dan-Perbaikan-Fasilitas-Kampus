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
        // $idTeknisi = Auth::id();

        $activeMenu = 'penugasan';
        // $laporan = LaporanModel::where('status', 'dilaksanakan')
        // ->where('idTeknisi', $idTeknisi)
        // ->get();              
        return view('sarpras.laporan_masuk', compact( 'breadcrumb', 'page', 'activeMenu'));

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
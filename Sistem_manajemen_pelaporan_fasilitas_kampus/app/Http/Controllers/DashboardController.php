<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UserModel;
use App\Models\LaporanModel;

class DashboardController extends Controller
{
    public function index()
    {
        

        $stats = [
            'laporan_diterima' => LaporanModel::where('status', 'diterima')->count(),
            'laporan_selesai' => LaporanModel::where('status', 'selesai')->count(),
            'teknisi_aktif' => UserModel::where('peran', 'teknisi')->count(),
            'total_pengguna' => UserModel::count()
        ];

        return view('dashboard', compact( 'stats'));
    }
}

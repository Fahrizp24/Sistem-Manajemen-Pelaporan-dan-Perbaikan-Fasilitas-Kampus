<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UserModel;
use App\Models\LaporanModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
    */
    public function index()
    {
        $pelapor = UserModel::all();
        return view('admin.index', compact('pelapor'));
    }

    public function laporan()
    {
        $breadcrumb = (object) [
            'title' => 'Laporan Masuk',
            'list' => ['Laporan', 'Masuk']
        ];

        $page = (object) [
            'title' => 'Laporan Masuk'
        ];

        $activeMenu = 'laporan';

        // Ambil data laporan berdasarkan status
        $laporan_masuk = LaporanModel::where('status', 'masuk')->get();
        $laporan_progress = LaporanModel::where('status', 'proses')->get();
        $laporan_selesai = LaporanModel::whereIn('status', ['selesai', 'ditolak'])->get();

        

        return view('admin.laporan', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'activeMenu' => $activeMenu,
            'laporan_masuk' => $laporan_masuk,
            'laporan_progress' => $laporan_progress,
            'laporan_selesai' => $laporan_selesai,
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\FasilitasModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UserModel;
use App\Models\LaporanModel;
use Illuminate\Support\Facades\Auth;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PelaporController extends Controller
{

    public function profile()
    {
        $breadcrumb = (object) [
            'title' => 'Profile',
            'list' => ['Detail Profile']
        ];
    
        $page = (object) [
            'title' => 'Profile',
            'subtitle' => 'Detail Profile'
        ];
    
        $activeMenu = 'profile';

        $pelapor = UserModel::find(Auth::user()->pengguna_id);

        return view('pelapor.profile', ['breadcrumb' => $breadcrumb, 'page'=> $page,'activeMenu' => $activeMenu,'pelapor' => $pelapor]);
    }

    public function update_profile(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:pelapor,email,' . $id,
            'phone' => 'required|string|max:15',
        ]);

        $pelapor = UserModel::findOrFail($id);
        $pelapor->update($request->all());

        return redirect()->route('pelapor.index')->with('success', 'UserModel updated successfully.');
    }   

    public function laporkan_kerusakan()
    {
        $breadcrumb = (object) [
            'title' => 'Laporan Kerusakan',
            'list' => ['Formulir Laporan Kerusakan Fasilitas']
        ];
    
        $page = (object) [
            'title' => 'Laporan Kerusakan Fasilitas',
            'subtitle' => 'Formulir Laporan Kerusakan Fasilitas'
        ];

        $fasilitas = FasilitasModel::all();
        $activeMenu = 'Laporkan Kerusakan';      
        return view('pelapor.laporkan_kerusakan', compact('fasilitas', 'breadcrumb', 'page', 'activeMenu'));  

    }

    public function laporan_saya()
    {
        $breadcrumb = (object) [
            'title' => 'Pelapor',
            'list' => ['Laporan Kerusakan Fasilitas']
        ];
    
        $page = (object) [
            'title' => 'Laporan Saya',
            'subtitle' => 'Riwayat Laporan Kerusakan Fasilitas'
        ];
        
        $activeMenu = 'Laporkan Kerusakan';      
        return view('pelapor.laporan_saya', ['breadcrumb' => $breadcrumb, 'page'=> $page,'activeMenu' => $activeMenu]);  

    }

    public function list_laporan_saya()
    {
        //mengambil data laporan dari database dengan eloquent idpelapor

        $laporan = DB::table('laporan')
            ->join('fasilitas', 'laporan.fasilitas_id', '=', 'fasilitas.id')
            ->select('laporan.*', 'fasilitas.nama_fasilitas')
            ->where('pelapor_id', auth()->user()->id)
            ->get();
    }
}
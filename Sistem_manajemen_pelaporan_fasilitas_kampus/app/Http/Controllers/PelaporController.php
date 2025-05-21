<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UserModel;
use App\Models\LaporanModel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class PelaporController extends Controller
{
    /**
     * Display a listing of the resource.
    */
    public function index()
    {
        $pelapor = UserModel::all();
        return view('pelapor.index', compact('pelapor'));
    }

    public function profile()
    {
        $breadcrumb = (object) [
            'title' => 'Profile',
            'list' => ['Detail Profile']
        ];
    
        $page = (object) [
            'title' => 'Profile'
        ];
    
        $activeMenu = 'profile';

        return view('pelapor.profile', ['breadcrumb' => $breadcrumb, 'page'=> $page,'activeMenu' => $activeMenu]);
        // $pelapor = UserModel::all();
        // return view('pelapor.profile', compact('pelapor'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_laporan_kerusakan()
    {
        return view('pelapor.laporan_kerusakan');
    }

    /**
     * menyimpan data laporan
     */
    public function store_laporan(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'fasilitas_id' => 'required|exists:fasilitas,id',
                'deskripsi' => 'required|string|max:255',
                'status' => 'required|string|max:255',
                'urgensi' => 'required|string|max:255',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // max 2MB
            ];

            $validator = Validator::make($request->all(), $rules);

            if ($validator->fails()) {
                return response()->json([
                    'status' => false, // response status, false: error/gagal, true: berhasil
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors(), // pesan error validasi
                ]);
            }

            LaporanModel::create([
                'pelapor_id' => auth()->user()->id,
                'fasilitas_id' => $request->fasilitas_id,
                'deskripsi' => $request->deskripsi,
                'urgensi' => $request->urgensi,
                'foto' => $request->file('foto')->store('laporan', 'public'),
            ]);
            return response()->json([
                'status' => true,
                'message' => 'Data Barang berhasil disimpan'
            ]);
        }
        return redirect()->route('pelapor.laporan_saya')->with('success', 'Laporan created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function laporan_saya()
    {
        // $pelapor = UserModel::findOrFail($id);
        return view('pelapor.laporan_saya');
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



    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $pelapor = UserModel::findOrFail($id);
        return view('pelapor.edit', compact('pelapor'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
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

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pelapor = UserModel::findOrFail($id);
        $pelapor->delete();

        return redirect()->route('pelapor.index')->with('success', 'UserModel deleted successfully.');
    }

    public function laporan_kerusakan()
    {
        // $laporan = DB::table('laporan')
        //     ->join('fasilitas', 'laporan.fasilitas_id', '=', 'fasilitas.id')
        //     ->select('laporan.*', 'fasilitas.nama_fasilitas')
        //     ->get();

        // return view('pelapor.laporan_kerusakan', compact('laporan'));
        return view('pelapor.laporan_kerusakan');

    }
}
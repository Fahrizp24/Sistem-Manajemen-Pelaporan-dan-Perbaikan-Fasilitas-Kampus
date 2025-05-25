<?php

namespace App\Http\Controllers;

use App\Models\LaporanModel;
use Illuminate\Http\Request;
use App\Models\FasilitasModel;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

        $laporan = LaporanModel::with(['pelapor', 'fasilitas'])->get();
        return view('pelapor.laporan_kerusakan');;

    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // $facilities = FasilitasModel::all();
        // return view('laporanKerusakan', compact('facilities'));
        return view('laporanKerusakan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'fasilitas_id' => 'required|exists:fasilitas,id',
            'deskripsi' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'tingkat_urgensi' => 'required|in:rendah,sedang,tinggi'
        ]);

        $photoPath = null;
        if ($request->hasFile('foto')) {
            $photoPath = $request->file('foto')->store('report-photos', 'public');
        }

        LaporanModel::create([
            'pelapor_id' => Auth::id(), // Now matches the model's field name
            'fasilitas_id' => $validated['fasilitas_id'],
            'deskripsi' => $validated['deskripsi'],
            'foto' => $photoPath,
            'status' => 'belum diproses',
            'tanggal_laporan' => now(),
            'tingkat_urgensi' => $validated['tingkat_urgensi'],
        ]);

        return redirect()->route('facility-reports.index')
            ->with('success', 'Laporan berhasil dikirim!');
    }


    /**
     * Display the specified resource.
     */
    public function show_laporan($id)   
    {
        $laporan = LaporanModel::with(['pelapor', 'fasilitas'])->findOrFail($id);
        $breadcrumb = (object) [
            'title' => 'Detail Laporan',
        ];

        $activeMenu = 'laporan';
    return view('admin.show_laporan', ['laporan' => $laporan, 'breadcrumb' => $breadcrumb, 'activeMenu' => $activeMenu ]);
    }
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

}

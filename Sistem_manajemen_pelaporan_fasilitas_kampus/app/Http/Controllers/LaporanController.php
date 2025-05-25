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
        return view('pelapor.laporan_kerusakan');
        ;

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


    public function show_laporan($id)
    {
        try{
        $laporan = LaporanModel::with(['pelapor', 'fasilitas'])->findOrFail($id);

        return response()->json([
            'pelapor' => [
                'nama' => $laporan->pelapor->nama ?? '-',
            ],
            'fasilitas' => [
                'nama' => $laporan->fasilitas->nama ?? '-',
            ],
            'deskripsi' => $laporan->deskripsi,
            'status' => $laporan->status,
            'urgensi' => $laporan->urgensi ?? '-', // Pastikan ini sesuai dengan nama field di database
        ]);
        } catch (\Exception $e) {
        return response()->json(['message' => 'Gagal mengambil data laporan'], 500);
    }
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
    public function update_laporan(Request $request, string $id)
    {
        $laporan = LaporanModel::findOrFail($id);
        $laporan->status = $request->status;
        $laporan->save();

        return redirect()->route('admin.laporan')->with('success', 'Status laporan berhasil diperbarui.');
    }

}

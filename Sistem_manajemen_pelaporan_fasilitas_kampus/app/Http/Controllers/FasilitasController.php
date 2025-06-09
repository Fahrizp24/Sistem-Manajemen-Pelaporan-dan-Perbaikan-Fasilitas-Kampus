<?php

namespace App\Http\Controllers;

use App\Models\FasilitasModel;
use App\Models\GedungModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class FasilitasController extends Controller
{
    public function data_fasilitas(Request $request)
    {
        if ($request->ajax()) {
            $data = FasilitasModel::with('ruangan.lantai.gedung')->select(['fasilitas_id', 'fasilitas_nama', 'fasilitas_deskripsi', 'kategori', 'ruangan_id', 'status']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $btn = '
                    <button type="button" class="btn btn-sm btn-primary btnEditFasilitas" data-id="' . $row->fasilitas_id . '">Edit</button>
                    <form action="' . route('admin.destroy_fasilitas', $row->fasilitas_id) . '" id="formDeleteFasilitas" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }
    public function index()
    {
        $fasilitas = FasilitasModel::all();
        return view('admin.kelola_fasilitas', compact('fasilitas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_ajax()
    {
        $gedung = GedungModel::all();
        return view('admin.fasilitas.create_ajax', compact('gedung'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_ajax(Request $request)
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'kategori' => 'required|in:Elektronik, Furniture, Pendingin, Alat Tulis',
            'gedung_id' => 'required|exists:gedung,gedung_id',
            'status' => 'required|in:normal,rusak',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ], 422);
        }

        $fasilitas = new FasilitasModel();
        $fasilitas->nama = $request->nama;
        $fasilitas->deskripsi = $request->deskripsi;
        $fasilitas->kategori = $request->kategori;
        $fasilitas->gedung_id = $request->gedung_id;
        $fasilitas->status = $request->status;
        $fasilitas->save();

        return response()->json([
            'status' => true,
            'message' => 'Fasilitas berhasil ditambahkan.'
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(GedungModel $gedungModel)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit_ajax($fasilitas_id)
    {
        $fasilitas = FasilitasModel::with('ruangan.lantai.gedung')->find($fasilitas_id);
        $gedung_id = $fasilitas->ruangan->lantai->gedung->gedung_id; 
        $gedung = GedungModel::all(); 
        return view('admin.fasilitas.edit_ajax', compact('fasilitas', 'gedung', 'gedung_id'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update_fasilitas(Request $request, $fasilitas_id)
    {
        $rules = [
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string|min:10',
            'kategori' => 'required',
            'gedung_id' => 'required',
            'status' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ], 422);
        }

        $fasilitas = FasilitasModel::findOrFail($fasilitas_id);
        $fasilitas->nama = $request->nama;
        $fasilitas->deskripsi = $request->deskripsi;
        $fasilitas->kategori = $request->kategori;
        $fasilitas->gedung_id = $request->gedung_id;
        $fasilitas->status = $request->status;
        $fasilitas->save();

        return response()->json([
            'status' => true,
            'message' => 'Fasilitas berhasil diperbarui.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($fasilitas_id)
    {
        $fasilitas = FasilitasModel::find($fasilitas_id);
        if (!$fasilitas) {
            return redirect()->route('admin.fasilitas')->with('error', 'Fasilitas tidak ditemukan.');
        }

        $fasilitas->delete();

        return redirect()->route('admin.fasilitas')->with('success', 'Fasilitas berhasil dihapus.');
    }

}

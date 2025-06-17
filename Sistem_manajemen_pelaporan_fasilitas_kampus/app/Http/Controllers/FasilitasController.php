<?php

namespace App\Http\Controllers;

use App\Models\FasilitasModel;
use App\Models\GedungModel;
use App\Models\RuanganModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\LantaiModel;

class FasilitasController extends Controller
{
    public function data_fasilitas(Request $request)
    {
        if ($request->ajax()) {
            $data = FasilitasModel::with('ruangan.lantai.gedung')->select(['fasilitas_id', 'fasilitas_nama', 'fasilitas_deskripsi', 'ruangan_id', 'status']);
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

    public function create_fasilitas()
    {
        $gedung = GedungModel::all();
        return view('admin.fasilitas.create_ajax', compact('gedung'));
    }

    public function store_fasilitas(Request $request)
    {
        $rules = [
            'gedung_id' => 'required|exists:gedung,gedung_id',
            'lantai_id' => 'required|exists:lantai,lantai_id',
            'ruangan_id' => 'required|exists:ruangan,ruangan_id',
            'fasilitas_nama' => 'required|string|max:255',
            'status' => 'required|in:normal,rusak',
            'fasilitas_deskripsi' => 'required|string|min:10',
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
        $fasilitas->ruangan_id = $request->ruangan_id;
        $fasilitas->fasilitas_nama = $request->fasilitas_nama;
        $fasilitas->fasilitas_deskripsi = $request->fasilitas_deskripsi;
        $fasilitas->status = $request->status;
        $fasilitas->save();

        return response()->json([
            'status' => true,
            'message' => 'Fasilitas berhasil ditambahkan.'
        ]);
    }

    // app/Http/Controllers/Admin/FasilitasController.php
    public function getLantaiByGedung($gedung_id)
    {
        $lantai = LantaiModel::where('gedung_id', $gedung_id)->get();
        return response()->json($lantai);
    }

    public function getRuanganByLantai($lantai_id)
    {
        $ruangan = RuanganModel::where('lantai_id', $lantai_id)->get();
        return response()->json($ruangan);
    }


    public function edit_fasilitas($fasilitas_id)
    {
        $fasilitas = FasilitasModel::with('ruangan.lantai.gedung')->find($fasilitas_id);
        $gedung_id = $fasilitas->ruangan->lantai->gedung->gedung_id;
        $gedung = GedungModel::all();
        return view('admin.fasilitas.edit_ajax', compact('fasilitas', 'gedung', 'gedung_id'));

    }

    public function update_fasilitas(Request $request, $fasilitas_id)
    {
        $rules = [
            'fasilitas_nama' => 'required|string|max:255',
            'fasilitas_deskripsi' => 'required|string|min:10',
            'ruangan_id' => 'required',
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
        $fasilitas->ruangan_id = $request->ruangan_id;
        $fasilitas->status = $request->status;
        $fasilitas->save();

        return response()->json([
            'status' => true,
            'message' => 'Fasilitas berhasil diperbarui.'
        ]);
    }

    public function destroy_fasilitas($fasilitas_id)
    {
        $fasilitas = FasilitasModel::find($fasilitas_id);

        if (!$fasilitas) {
            return response()->json([
                'success' => false,
                'message' => 'Fasilitas tidak ditemukan.'
            ], 404);
        }

        $fasilitas->delete();

        return response()->json([
            'success' => true,
            'message' => 'Fasilitas berhasil dihapus.'
        ]);
    }

}

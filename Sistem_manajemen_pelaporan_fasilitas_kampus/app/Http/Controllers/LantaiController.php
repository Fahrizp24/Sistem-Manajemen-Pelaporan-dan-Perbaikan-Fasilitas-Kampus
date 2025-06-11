<?php

namespace App\Http\Controllers;

use App\Models\LantaiModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;
use App\Models\GedungModel;

class LantaiController extends Controller
{

    public function data_lantai(Request $request)
    {
        if ($request->ajax()) {
            $data = LantaiModel::select(['lantai_id', 'lantai_nama', 'lantai_deskripsi']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $btn = '
                    <button type="button" class="btn btn-sm btn-primary btnEditlantai" data-id="' . $row->lantai_id . '">Edit</button>
                    <form action="' . route('admin.destroy_lantai', $row->lantai_id) . '" id="formDeleteLantai" method="POST" style="display:inline;">
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
        $lantai = LantaiModel::all();
        return view('admin.kelola_lantai', compact('lantai'));
    }

    public function create_lantai()
    {
        $gedung = GedungModel::all();
        return view('admin.lantai.create_ajax', compact('gedung'));
    }

    public function store_lantai(Request $request)
    {
        $rules = [
            'lantai_nama' => 'required|string',
            'lantai_deskripsi' => 'required|string|min:10',
            'gedung_id' => 'required|exists:gedung,gedung_id',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ], 422); // Tambahkan status code 422 untuk validation error
        }

        $lantai = new LantaiModel();
        $lantai->lantai_nama = $request->lantai_nama;
        $lantai->lantai_deskripsi = $request->lantai_deskripsi;
        $lantai->gedung_id = $request->gedung_id;
        $lantai->save();

        return response()->json([
            'status' => true,
            'message' => 'Lantai berhasil ditambahkan.'
        ]);
    }

    public function edit_lantai($lantai_id)
    {
        $lantai = LantaiModel::findOrFail($lantai_id);
        return view('admin.lantai.edit_ajax', compact('lantai'));
    }

    public function update_lantai(Request $request, $lantai_id)
    {
        $request->validate([
            'gedung_id' => 'required|exists:gedung,gedung_id',
            'lantai_nama' => 'required|string|max:255',
            'lantai_deskripsi' => 'required|string',
        ]);

        $lantai = LantaiModel::findOrFail($lantai_id);
        $lantai->gedung_id = $request->gedung_id;
        $lantai->lantai_nama = $request->lantai_nama;
        $lantai->lantai_deskripsi = $request->lantai_deskripsi;
        $lantai->save();


        return response()->json([
            'status' => true,
            'message' => 'Data Lantai berhasil diperbarui.'
        ]);
    }

    public function destroy_lantai($lantai_id)
    {
        $lantai = LantaiModel::find($lantai_id);

        if (!$lantai) {
            return redirect()->route('admin.lantai')->with('error', 'Lantai tidak ditemukan.');
        }

        $lantai->delete();

        return redirect()->route('admin.lantai')->with('success', 'Lantai berhasil dihapus.');
    }
}

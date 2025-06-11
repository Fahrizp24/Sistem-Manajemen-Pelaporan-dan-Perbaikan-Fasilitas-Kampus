<?php

namespace App\Http\Controllers;

use App\Models\RuanganModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class RuanganController extends Controller
{

    public function data_ruangan(Request $request)
    {
        if ($request->ajax()) {
            $data = RuanganModel::select(['ruangan_id', 'ruangan_nama', 'ruangan_deskripsi']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $btn = '
                    <button type="button" class="btn btn-sm btn-primary btnEditruangan" data-id="' . $row->ruangan_id . '">Edit</button>
                    <form action="' . route('admin.destroy_ruangan', $row->ruangan_id) . '" id="formDeleteruangan" method="POST" style="display:inline;">
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
        $ruangan = RuanganModel::all();
        return view('admin.kelola_ruangan', compact('ruangan'));
    }

    public function create_ruangan()
    {
        return view('admin.ruangan.create_ajax');
    }

    public function store_ruangan(Request $request)
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

        $ruangan = new RuanganModel();
        $ruangan->ruangan_nama = $request->ruangan_nama;
        $ruangan->ruangan_deskripsi = $request->ruangan_deskripsi;
        $ruangan->gedung_id = $request->gedung_id;
        
        $ruangan->save();

        return response()->json([
            'status' => true,
            'message' => 'Ruangan berhasil ditambahkan.'
        ]);
    }

    public function edit_ruangan($ruangan_id)
    {
        $ruangan = RuanganModel::findOrFail($ruangan_id);
        return view('admin.ruangan.edit_ajax', compact('ruangan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_ruangan(Request $request, $ruangan_id)
    {
        $request->validate([
            'ruangan_nama' => 'required|string|max:255',
            'ruangan_deskripsi' => 'required|string',
        ]);

        $ruangan = RuanganModel::findOrFail($ruangan_id);
        $ruangan->ruangan_nama = $request->ruangan_nama;
        $ruangan->ruangan_deskripsi = $request->ruangan_deskripsi;
        $ruangan->save();


        return response()->json([
            'status' => true,
            'message' => 'Data Ruangan berhasil diperbarui.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_ruangan($ruangan_id)
    {
        $ruangan = RuanganModel::find($ruangan_id);

        if (!$ruangan) {
            return redirect()->route('admin.ruangan')->with('error', 'Ruangan tidak ditemukan.');
        }

        $ruangan->delete();

        return redirect()->route('admin.ruangan')->with('success', 'Ruangan berhasil dihapus.');
    }
}

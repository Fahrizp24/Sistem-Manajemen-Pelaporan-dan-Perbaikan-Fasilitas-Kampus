<?php

namespace App\Http\Controllers;

use App\Models\GedungModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Yajra\DataTables\Facades\DataTables;

class GedungController extends Controller
{

    public function data_gedung(Request $request)
    {
        if ($request->ajax()) {
            $data = GedungModel::select(['gedung_id', 'gedung_nama', 'gedung_deskripsi']);
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $btn = '
                    <button type="button" class="btn btn-sm btn-primary btnEditGedung" data-id="' . $row->gedung_id . '">Edit</button>
                    <form action="' . route('admin.destroy_gedung', $row->gedung_id) . '" id="formDeleteGedung" method="POST" style="display:inline;">
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
        $gedung = GedungModel::all();
        return view('admin.kelola_gedung', compact('gedung'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create_gedung()
    {
        return view('admin.gedung.create_ajax');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store_gedung(Request $request)
    {
        $rules = [
            'nama' => 'required|string',
            'deskripsi' => 'required|string|min:10',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ], 422); // Tambahkan status code 422 untuk validation error
        }

        $gedung = new GedungModel();
        $gedung->gedung_nama = $request->nama;
        $gedung->gedung_deskripsi = $request->deskripsi;
        $gedung->save();

        return response()->json([
            'status' => true,
            'message' => 'Gedung berhasil ditambahkan.'
        ]);
    }

    public function edit_gedung($gedung_id)
    {
        $gedung = GedungModel::findOrFail($gedung_id);
        return view('admin.gedung.edit_ajax', compact('gedung'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update_gedung(Request $request, $gedung_id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        $gedung = GedungModel::findOrFail($gedung_id);
        $gedung->gedung_nama = $request->nama;
        $gedung->gedung_deskripsi = $request->deskripsi;
        $gedung->save();


        return response()->json([
            'status' => true,
            'message' => 'Data Gedung berhasil diperbarui.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy_gedung($gedung_id)
    {
        $gedung = GedungModel::find($gedung_id);

        if (!$gedung) {
            return response()->json([
                'success' => false,
                'message' => 'Gedung tidak ditemukan.'
            ], 404);
        }

        $gedung->delete();

        return response()->json([
            'success' => true,
            'message' => 'Gedung berhasil dihapus.'
        ]);
    }

}

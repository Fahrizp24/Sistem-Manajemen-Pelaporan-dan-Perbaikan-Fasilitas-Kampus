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
    public function create()
    {
        return view('admin.gedung.create_ajax');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
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
        $gedung->nama = $request->nama;
        $gedung->deskripsi = $request->deskripsi;
        $gedung->save();

        return response()->json([
            'status' => true,
            'message' => 'Gedung berhasil ditambahkan.'
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
    public function edit($gedung_id)
    {
        $gedung = GedungModel::findOrFail($gedung_id);
        return view('admin.gedung.edit_ajax', compact('gedung'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $gedung_id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'deskripsi' => 'required|string',
        ]);

        $gedung = GedungModel::findOrFail($gedung_id);
        $gedung->nama = $request->nama;
        $gedung->deskripsi = $request->deskripsi;
        $gedung->save();


        return response()->json([
            'status' => true,
            'message' => 'Data Gedung berhasil diperbarui.'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($gedung_id)
    {
        $gedung = GedungModel::find($gedung_id);

        if (!$gedung) {
            return redirect()->route('admin.gedung')->with('error', 'Gedung tidak ditemukan.');
        }

        $gedung->delete();

        return redirect()->route('admin.gedung')->with('success', 'Gedung berhasil dihapus.');
    }
}

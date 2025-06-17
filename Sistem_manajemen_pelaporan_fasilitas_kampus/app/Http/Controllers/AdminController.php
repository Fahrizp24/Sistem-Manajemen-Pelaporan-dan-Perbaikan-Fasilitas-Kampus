<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\UserModel;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use App\Models\FasilitasModel;
use App\Models\GedungModel;
use App\Models\LaporanModel;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;
use App\Models\KriteriaModel;
use App\Models\CrispModel;


class AdminController extends Controller
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

        $admin = UserModel::find(Auth::user()->pengguna_id);

        return view('admin.profile', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'admin' => $admin]);
    }

    public function updateProfile(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'prodi' => 'nullable|string|max:255',
            'jurusan' => 'nullable|string|max:255',
            'no_telp' => 'nullable|string|max:20'
        ]);

        // Hanya update field yang diisi
        $user = UserModel::find(auth()->user()->pengguna_id);
        foreach ($validated as $key => $value) {
            if ($value !== null) {
                $user->$key = $value;
            }
        }
        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui!');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'nullable',
            'new_password' => 'nullable|string|min:8|confirmed'
        ]);

        // Jika ada input password baru
        if ($request->filled('new_password')) {
            // Verifikasi password lama hanya jika diisi
            if ($request->filled('current_password') && !Hash::check($request->current_password, auth()->user()->password)) {
                return back()->withErrors(['current_password' => 'Password saat ini tidak sesuai']);
            }

            $user = UserModel::find(auth()->user()->pengguna_id);
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);
        }

        return back()->with('success', 'password berhasil diubah!');
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpeg,png,jpg|max:2048'
        ]);

        try {
            $user = UserModel::findOrFail(Auth::id());
            $file = $request->file('foto');

            // Nama file unik
            $filename = $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('public/foto_profil', $filename);

            // Hapus foto lama kalau ada
            if ($user->foto_profil && Storage::exists('public/foto_profil/' . $user->foto_profil)) {
                Storage::delete('public/foto_profil/' . $user->foto_profil);
            }

            // Simpan nama file ke DB
            $user->foto_profil = $filename;
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Foto berhasil diupload',
                'foto_profil' => asset('storage/foto_profil/' . $filename),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Upload gagal',
                'msgField' => [],
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function data_pengguna(Request $request)
    {
        if ($request->ajax()) {
            $data = UserModel::select(['pengguna_id', 'username', 'nama', 'email', 'no_telp', 'peran']);
            // dd($data);
            return DataTables::of($data)
                ->addIndexColumn() // untuk DT_RowIndex
                ->addColumn('aksi', function ($row) {
                    $btn = '
                <button type="button" class="btn btn-sm btn-primary btnEditPengguna" data-id="' . $row->pengguna_id . '">Edit</button>
                <form action="' . route('admin.destroy', $row->pengguna_id) . '" id="formDeletePengguna" method="POST" style="display:inline;">
                    ' . csrf_field() . method_field('DELETE') . '
                    <button class="btn btn-sm btn-danger">Hapus</button>
                </form>';
                    return $btn;
                })
                ->rawColumns(['aksi']) // supaya tombol tidak di-escape HTML-nya
                ->make(true);
        }
    }

    public function laporan_masuk()
    {
        $laporan = LaporanModel::with('fasilitas.ruangan.lantai.gedung')->where('status', 'konfirmasi')->get();

        $breadcrumb = (object) [
            'title' => 'Laporan',
            'list' => ['Admin Laporan']
        ];

        $page = (object) [
            'title' => 'Laporan',
            'subtitle' => 'List Laporan Fasilitas'
        ];

        $activeMenu = 'laporan masuk';
        return view('admin.laporan_masuk', ['laporan' => $laporan, 'breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    public function konfirmasi_laporan(string $id, Request $request)
    {
        try {
            LaporanModel::where('fasilitas_id', $id)
                ->where('status', 'konfirmasi')
                ->update(['status' => 'memilih teknisi']);

            if ($request->ajax()) {
                return response()->json([
                    // 'success' => true,
                    'status' =>  true,
                    'message' => 'Laporan berhasil dikonfirmasi.'
                ]);
            }

            return redirect()->back()->with('success', 'Laporan berhasil dikonfirmasi.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Gagal mengkonfirmasi laporan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal mengkonfirmasi laporan: ' . $e->getMessage());
        }
    }

    public function data_laporan(Request $request)
    {
        $fasilitas = FasilitasModel::whereHas('laporan', function ($query) {
            $query->where('status', 'konfirmasi');
        })->with([
            'laporan' => function ($query) {
                $query->where('status', 'konfirmasi')->with('pelapor');
            },
            'ruangan.lantai.gedung'
        ])->get();

        // dd($fasilitas);

        return datatables()->of($fasilitas)
            ->addIndexColumn()
            ->addColumn('gedung', fn($row) => $row->ruangan->lantai->gedung->gedung_nama ?? '-')
            ->addColumn('lantai', fn($row) => $row->ruangan->lantai->lantai_nama ?? '-')
            ->addColumn('ruangan', fn($row) => $row->ruangan->ruangan_nama ?? '-')
            ->addColumn('fasilitas', fn($row) => $row->fasilitas_nama ?? '-')
            ->addColumn('status', function ($row) {
                return match ($row->status) {
                    'konfirmasi' => '<span class="badge bg-secondary">Menunggu Konfirmasi</span>',
                    'proses' => '<span class="badge bg-info">Dalam Proses</span>',
                    'selesai' => '<span class="badge bg-success">Selesai</span>',
                    default => '<span class="badge bg-secondary">Tidak Diketahui</span>'
                };
            })
            ->addColumn('aksi', function ($row) {
                return '<button onclick="modalAction(\'' . url('admin/laporan_masuk/show_laporan/' . $row->fasilitas_id) . '\')" class="btn btn-sm btn-info">Detail</button>';
            })
            ->rawColumns(['aksi', 'status'])
            ->make(true);
    }

    public function show_laporan(string $id)
    {
        $breadcrumb = (object) [
            'title' => 'Data Penugasan',
            'list' => ['Data Penugasan']
        ];

        $page = (object) [
            'title' => 'Detail Penugasan',
            'subtitle' => 'Informasi lengkap mengenai penugasan'
        ];

        $source = request()->query('source', 'default');

        $fasilitas = FasilitasModel::with(['laporan.pelapor', 'laporan.teknisi', 'laporan.sarpras'])->findOrFail($id);

        $laporan = $fasilitas->laporan
            ->map(function ($item) {
                return [
                    'id' => $item->pelapor->pengguna_id,
                    'nama' => $item->pelapor->nama,
                    'foto' => $item->pelapor->foto_profil,
                    'created_at' => $item->created_at->toDateString(),
                    'foto_pengerjaan' => $item->foto_pengerjaan,
                ];
            })
            ->unique('id');

        $jumlahPelapor = $fasilitas->laporan
            ->pluck('pelapor.pengguna_id')
            ->unique()
            ->count();

        return view('admin.detail_laporan', compact('laporan', 'fasilitas', 'jumlahPelapor', 'breadcrumb', 'page', 'source'));
    }

    public function laporan2()
    {
        $laporan2 = LaporanModel::with('fasilitas.gedung')->get();

        $breadcrumb = (object) [
            'title' => 'Laporan',
            'list' => ['Admin Laporan']
        ];

        $page = (object) [
            'title' => 'Laporan',
            'subtitle' => 'List Laporan Fasilitas'
        ];

        $activeMenu = 'laporan2';
        return view('admin.laporan2', ['laporan2' => $laporan2, 'breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu]);
    }

    function kelola_pengguna()
    {
        $breadcrumb = (object) [
            'title' => 'Pengguna',
            'list' => ['Kelola Pengguna']
        ];

        $page = (object) [
            'title' => 'Akun Pengguna',
            'subtitle' => 'Kelola List Akun Pengguna'
        ];

        $pengguna = UserModel::all();
        return view('admin.kelola_pengguna', compact('pengguna', 'breadcrumb', 'page'));
    }

    public function create_pengguna()
    {
        return view('admin.pengguna.create_ajax');
    }

    public function store_pengguna(Request $request)
    {
        $rules = [
            'username' => 'required|string',
            'nama' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'no_telp' => 'nullable|digits_between:10,15',
            'password' => 'required|min:6',
            'peran' => 'required|in:admin,sarpras,pelapor,teknisi',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ], 422); // Tambahkan status code 422 untuk validation error
        }

        $pengguna = new UserModel();
        $pengguna->username = $request->username;
        $pengguna->nama = $request->nama;
        $pengguna->email = $request->email;
        $pengguna->no_telp = $request->no_telp;
        $pengguna->password = Hash::make($request->password);
        $pengguna->peran = $request->peran;
        $pengguna->save();

        return response()->json([
            'status' => true,
            'message' => 'Pengguna berhasil ditambahkan.'
        ]);
    }

    public function show_pengguna()
    {
        $admin = UserModel::all();
        return view('admin.show_pengguna', compact('admin'));
    }

    public function edit_pengguna($id)
    {
        $user = UserModel::findOrFail($id);
        return view('admin.pengguna.edit_ajax', compact('user'));
    }

    public function update_pengguna(Request $request, $id)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'nama' => 'required|string|max:255',
            'email' => 'required|email|unique:pengguna,email,' . $id . ',pengguna_id',
            'no_telp' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6',
            'peran' => 'required|string|in:admin,sarpras,pelapor, teknisi',
        ]);

        $user = UserModel::findOrFail($id);
        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->email = $request->email;
        $user->no_telp = $request->no_telp;
        $user->peran = $request->peran;

        // Jika ada field password dikirim dan tidak kosong
        if ($request->filled('password')) {
            $request->validate(['password' => 'min:6']);
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'Data pengguna berhasil diperbarui.'
        ]);
    }

    public function destroy_pengguna($id)
    {
        $pengguna = UserModel::find($id);

        if (!$pengguna) {
            return redirect()->route('admin.pengguna')->with('error', 'Pengguna tidak ditemukan.');
        }

        $pengguna->delete();

        return redirect()->route('admin.pengguna')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function reset_password_pengguna($id)
    {
        $pengguna = UserModel::findOrFail($id);

        // Reset password = username
        $pengguna->password = Hash::make($pengguna->username);
        $pengguna->save();

        return response()->json([
            'status' => true,
            'message' => 'Password berhasil direset menjadi username.'
        ]);
    }

    public function import_pengguna()
    {
        return view('admin.pengguna.import_pengguna');
    }

    public function import_pengguna_store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_pengguna' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_pengguna');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris == 1)
                        continue; // Lewati header

                    // Pastikan minimal username dan email tidak kosong
                    if (!empty($value['A']) && !empty($value['C'])) {
                        $insert[] = [
                            'username' => $value['A'],
                            'nama' => $value['B'] ?? null, // Gunakan null jika kosong
                            'email' => $value['C'],
                            'password' => Hash::make($value['D']),
                            'peran' => $value['E'] ?? 'pelapor', // Beri nilai default jika kosong
                        ];
                    }
                }

                if (count($insert) > 0) {
                    UserModel::insertOrIgnore($insert);
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil diimport',
                        'total_data' => count($insert)
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Tidak ada data valid yang ditemukan'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'File kosong atau format tidak sesuai'
                ]);
            }
        }
        return redirect('/admin/kelola_pengguna');
    }

    function kelola_fasilitas()
    {
        $breadcrumb = (object) [
            'title' => 'Fasilitas',
            'list' => ['Kelola Fasilitas']
        ];

        $page = (object) [
            'title' => 'Fasilitas',
            'subtitle' => 'Kelola List Fasilitas'
        ];

        $fasilitas = FasilitasModel::all();
        return view('admin.kelola_fasilitas', compact('fasilitas', 'breadcrumb', 'page'));
    }

    public function import_fasilitas()
    {
        return view('admin.fasilitas.import_fasilitas');
    }


    public function import_fasilitas_store(Request $request)
    {
        if ($request->ajax() || $request->wantsJson()) {
            $rules = [
                'file_fasilitas' => ['required', 'mimes:xlsx', 'max:1024']
            ];
            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return response()->json([
                    'status' => false,
                    'message' => 'Validasi Gagal',
                    'msgField' => $validator->errors()
                ]);
            }

            $file = $request->file('file_fasilitas');
            $reader = IOFactory::createReader('Xlsx');
            $reader->setReadDataOnly(true);
            $spreadsheet = $reader->load($file->getRealPath());
            $sheet = $spreadsheet->getActiveSheet();
            $data = $sheet->toArray(null, false, true, true);

            $insert = [];
            if (count($data) > 1) {
                foreach ($data as $baris => $value) {
                    if ($baris == 1)
                        continue; // Lewati header

                    // Pastikan minimal username dan email tidak kosong
                    if (!empty($value['A']) && !empty($value['C'])) {
                        $insert[] = [
                            'nama' => $value['A'],
                            'deskripsi' => $value['B'] ?? null, // Gunakan null jika kosong
                            'kategori' => $value['C'],
                            'gedung_id' => $value['D'],
                        ];
                    }
                }
                if (count($insert) > 0) {
                    FasilitasModel::insertOrIgnore($insert);
                    return response()->json([
                        'status' => true,
                        'message' => 'Data berhasil diimport',
                        'total_data' => count($insert)
                    ]);
                } else {
                    return response()->json([
                        'status' => false,
                        'message' => 'Tidak ada data valid yang ditemukan'
                    ]);
                }
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'File kosong atau format tidak sesuai'
                ]);
            }
        }
        return redirect('/admin/kelola_pengguna');
    }

    function kelola_gedung()
    {
        $breadcrumb = (object) [
            'title' => 'Gedung',
            'list' => ['Kelola Gedung']
        ];

        $page = (object) [
            'title' => 'Gedung',
            'subtitle' => 'Kelola List Gedung'
        ];

        $gedung = GedungModel::all();
        return view('admin.kelola_gedung', compact('gedung', 'breadcrumb', 'page'));
    }

    function sistem_rekomendasi()
    {
        $breadcrumb = (object) [
            'title' => 'Sistem Rekomendasi',
            'list' => ['Sistem Rekomendasi']
        ];

        $page = (object) [
            'title' => 'Sistem Rekomendasi',
            'subtitle' => 'Sistem Rekomendasi'
        ];

        $activeMenu = 'sistem_rekomendasi';
        return view('admin.sistem_rekomendasi', compact('breadcrumb', 'page', 'activeMenu'));
    }

    public function laporan_periodik(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Laporan Periodik',
            'list' => ['Laporan Periodik']
        ];

        $page = (object) [
            'title' => 'Laporan Periodik',
            'subtitle' => 'Detail Laporan Periodik'
        ];

        $activeMenu = 'laporan_periodik';

        // Default values
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // Query untuk status perbaikan
        $statusPerbaikan = LaporanModel::select('status', DB::raw('COUNT(*) as total'))
            ->whereYear('created_at', $tahun)
            ->when($bulan != 'all', function ($query) use ($bulan) {
                return $query->whereMonth('created_at', $bulan);
            })
            ->groupBy('status')
            ->get();

        // Query untuk kepuasan pengguna
        $kepuasan = DB::table('umpan_balik')
            ->select('penilaian as rating', DB::raw('COUNT(*) as total'))
            ->whereYear('created_at', $tahun)
            ->when($bulan != 'all', function ($query) use ($bulan) {
                return $query->whereMonth('created_at', $bulan);
            })
            ->groupBy('penilaian')
            ->get();

        // Hitung rata-rata rating
        $averageRating = DB::table('umpan_balik')
            ->whereYear('created_at', $tahun)
            ->when($bulan != 'all', function ($query) use ($bulan) {
                return $query->whereMonth('created_at', $bulan);
            })
            ->avg('penilaian');

        // Query untuk tren kerusakan per bulan
        $kerusakanBulan = LaporanModel::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('created_at', $tahun)
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('bulan')
            ->get();

        // Data untuk line chart tren kerusakan (12 bulan terakhir)
        $trenKerusakan = LaporanModel::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('YEAR(created_at) as tahun'),
            DB::raw('COUNT(*) as total')
        )
            ->whereBetween('created_at', [now()->subMonths(11)->startOfMonth(), now()->endOfMonth()])
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('YEAR(created_at)'))
            ->orderBy(DB::raw('MONTH(created_at)'))
            ->get();

        // Format data untuk chart
        $trenLabels = [];
        $trenData = [];

        for ($i = 11; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $month = $date->month;
            $year = $date->year;
            $label = $date->format('M Y');

            $record = $trenKerusakan->first(function ($item) use ($month, $year) {
                return $item->bulan == $month && $item->tahun == $year;
            });

            $trenLabels[] = $label;
            $trenData[] = $record ? $record->total : 0;
        }

        $kerusakanPerGedung = DB::table('laporan')
            ->join('fasilitas', 'laporan.fasilitas_id', '=', 'fasilitas.fasilitas_id')
            ->join('ruangan', 'fasilitas.ruangan_id', '=', 'ruangan.ruangan_id')
            ->join('lantai', 'ruangan.lantai_id', '=', 'lantai.lantai_id')
            ->join('gedung', 'lantai.gedung_id', '=', 'gedung.gedung_id')
            ->select('gedung_nama as nama_gedung', DB::raw('COUNT(*) as total'))
            ->whereYear('laporan.created_at', $tahun)
            ->when($bulan != 'all', function ($query) use ($bulan) {
                return $query->whereMonth('laporan.created_at', $bulan);
            })
            ->groupBy('gedung_nama')
            ->orderBy('gedung_nama')
            ->get();

        return view('admin.laporan_periodik', compact(
            'breadcrumb',
            'page',
            'activeMenu',
            'statusPerbaikan',
            'kepuasan',
            'averageRating',
            'kerusakanBulan',
            'bulan',
            'tahun',
            'trenLabels',
            'trenData',
            'kerusakanPerGedung'
        ));
    }

    public function export_laporan_periodik(Request $request)
    {
        $bulan_awal = $request->input('bulan_awal', 1);
        $bulan_akhir = $request->input('bulan_akhir', 12);
        $tahun = $request->input('tahun', date('Y'));

        $data = LaporanModel::select(
            DB::raw('MONTH(created_at) as bulan'),
            DB::raw('COUNT(*) as total')
        )
            ->whereYear('created_at', $tahun)
            ->whereBetween(DB::raw('MONTH(created_at)'), [$bulan_awal, $bulan_akhir])
            ->groupBy(DB::raw('MONTH(created_at)'))
            ->orderBy('bulan')
            ->get();

        $kerusakanPerGedung = LaporanModel::select('gedung.gedung_nama', DB::raw('COUNT(*) as total'))
            ->join('fasilitas', 'laporan.fasilitas_id', '=', 'fasilitas.fasilitas_id')
            ->join('ruangan', 'fasilitas.ruangan_id', '=', 'ruangan.ruangan_id')
            ->join('lantai', 'ruangan.lantai_id', '=', 'lantai.lantai_id')
            ->join('gedung', 'lantai.gedung_id', '=', 'gedung.gedung_id')
            ->whereYear('laporan.created_at', $tahun)
            ->whereBetween(DB::raw('MONTH(laporan.created_at)'), [$bulan_awal, $bulan_akhir])
            ->groupBy('gedung.gedung_nama')
            ->orderBy('gedung.gedung_nama')
            ->get();

        $pdf = PDF::loadView('admin.laporan_periodik_pdf', [
            'data' => $data,
            'kerusakanPerGedung' => $kerusakanPerGedung,
            'bulan_awal' => $bulan_awal,
            'bulan_akhir' => $bulan_akhir,
            'tahun' => $tahun
        ]);

        return $pdf->download('laporan_periodik_' . $tahun . '.pdf');
    }

    public function statistik()
    {
        $breadcrumb = (object) [
            'title' => 'Statistik',
            'list' => ['Statistik Laporan']
        ];

        $page = (object) [
            'title' => 'Statistik',
            'subtitle' => 'Statistik Laporan'
        ];

        $activeMenu = 'statistik';

        // Data untuk chart status laporan (line chart)
        $statusLaporan = DB::table('laporan')
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->get();

        $statusLabels = $statusLaporan->pluck('status');
        $statusData = $statusLaporan->pluck('total');

        // Data untuk chart tingkat urgensi (bar chart)
        $kerusakan = DB::table('laporan')
            ->select('urgensi', DB::raw('COUNT(*) as total'))
            ->groupBy('urgensi')
            ->orderByRaw("FIELD(urgensi, 'tinggi', 'sedang', 'rendah')")
            ->get();

        $urgensiLabels = $kerusakan->pluck('urgensi');
        $urgensiData = $kerusakan->pluck('total');

        return view('admin.statistik', compact(
            'breadcrumb',
            'page',
            'activeMenu',
            'statusLabels',
            'statusData',
            'urgensiLabels',
            'urgensiData'
        ));
    }
}

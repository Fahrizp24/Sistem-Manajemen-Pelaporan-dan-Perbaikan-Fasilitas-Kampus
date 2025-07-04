<?php

namespace App\Http\Controllers;

use App\Models\CrispModel;
use App\Models\KriteriaModel;
use App\Models\LaporanModel;
use App\Models\TeknisiModel;
use App\Models\FasilitasModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\UserModel;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;
use App\Models\SpkModel;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class SarprasController extends Controller
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

        $sarpras = UserModel::find(Auth::user()->pengguna_id);

        return view('sarpras.profile', ['breadcrumb' => $breadcrumb, 'page' => $page, 'activeMenu' => $activeMenu, 'sarpras' => $sarpras]);
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

    public function list_laporan()
    {
        $breadcrumb = (object) [
            'title' => 'Data Laporan',
            'list' => ['Data Laporan Masuk']
        ];

        $page = (object) [
            'title' => 'Data Laporan',
            'subtitle' => 'Data Laporan Masuk Dari Pelapor dan Admin'
        ];

        $activeMenu = 'penugasan';

        $laporan_masuk_pelapor = LaporanModel::with('fasilitas.ruangan.lantai.gedung')
            ->where('status', 'diajukan')
            ->orderBy('updated_at', 'desc')
            ->get();

        $fasilitas_memilih_teknisi = FasilitasModel::whereHas('laporan', function ($query) {
            $query->where('status', 'memilih teknisi');
        })->with([
            'laporan' => function ($query) {
                $query->where('status', 'memilih teknisi')
                    ->with('pelapor')
                    ->orderBy('updated_at', 'desc'); 
            },
            'ruangan.lantai.gedung'
        ])
        ->orderBy('updated_at', 'desc') 
        ->get();

        $fasilitas_telah_diperbaiki = FasilitasModel::whereHas('laporan', function ($query) {
            $query->where('status', 'telah diperbaiki');
        })->with([
            'laporan' => function ($query) {
                $query->where('status', 'telah diperbaiki')
                    ->with('pelapor')
                    ->orderBy('updated_at', 'desc'); 
            },
            'ruangan.lantai.gedung'
        ])
        ->orderBy('updated_at', 'desc')
        ->get();

        return view('sarpras.laporan_masuk', compact(
            'breadcrumb', 'page', 'activeMenu',
            'laporan_masuk_pelapor',
            'fasilitas_memilih_teknisi',
            'fasilitas_telah_diperbaiki'
        ));
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

        $teknisi = UserModel::where('peran', 'teknisi')->get();

        $source = request()->query('source', 'default');

        $kriteria = KriteriaModel::orderBy('kriteria_id')->get();

        $crisp = CrispModel::orderBy('kriteria_id')->orderBy('poin')->get();

        if ($source === 'pelapor') {
            $laporan = LaporanModel::findOrFail($id);
            $spk = SpkModel::with('kriteria')
                ->where('fasilitas_id', $laporan->fasilitas_id)
                ->first();

            $penilaian = $spk
                ? $spk->kriteria->mapWithKeys(function ($item) {
                    return [$item->pivot->kriteria_id => $item->pivot];
                })
                : collect(); // supaya tidak null
            return view('sarpras.detail_laporan', compact('laporan', 'breadcrumb', 'page', 'source', 'teknisi', 'kriteria', 'crisp', 'penilaian'));
        } else {
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

            return view('sarpras.detail_fasilitas', compact('fasilitas', 'laporan', 'jumlahPelapor', 'breadcrumb', 'page', 'source', 'teknisi', 'kriteria', 'crisp'));
        }
    }

    public function terima(string $id, Request $request)
    {
        DB::beginTransaction();

        try {
            $validated = $request->validate([
                'kriteria' => 'required|array',
                'kriteria.*' => 'required|numeric'
            ]);

            $fasilitas = FasilitasModel::findOrFail($request->fasilitas_id);

            // Cek apakah sudah ada SPK untuk fasilitas ini
            $spk = SpkModel::where('fasilitas_id', $request->fasilitas_id)->first();
            // Hitung jumlah pelapor unik untuk laporan "diterima"
            $jumlahPelapor = $fasilitas->laporan
                ->where('status', 'diterima')
                ->pluck('pelapor.pengguna_id')
                ->unique()
                ->count();

            $nilaiKriteria7 = $jumlahPelapor + 1;

            if ($spk) {
                // Jika SPK sudah ada, update atau tambahkan nilai kriteria ke-7
                $kriteriaData = [];
                $data = $request->all();
                $data['kriteria'][7] = $nilaiKriteria7;
                foreach ($data['kriteria'] as $kriteriaId => $nilai) {
                    $kriteriaData[$kriteriaId] = ['nilai' => $nilai];
                }
                $spk->kriteria()->syncWithoutDetaching([
                    7 => ['nilai' => $nilaiKriteria7]
                ]);
            } else {
                // Jika belum ada, buat SPK baru
                $spk = SpkModel::create([
                    'fasilitas_id' => $request->fasilitas_id
                ]);

                $data = $request->all();
                $data['kriteria'][7] = $nilaiKriteria7;

                // Siapkan data untuk relasi many-to-many
                $kriteriaData = [];
                foreach ($data['kriteria'] as $kriteriaId => $nilai) {
                    $kriteriaData[$kriteriaId] = ['nilai' => $nilai];
                }

                $spk->kriteria()->sync($kriteriaData);
            }

            // Update status laporan
            LaporanModel::findOrFail($id)->update(['status' => 'diterima']);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil diterima.',
                    'data' => $spk->load('kriteria')
                ]);
            }

            return redirect()->back()->with('success', 'Laporan berhasil diterima.');
        } catch (\Exception $e) {
            DB::rollBack();

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menerima laporan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal menerima laporan: ' . $e->getMessage());
        }
    }


    public function tolak(string $id, Request $request)
    {
        try {
            $laporan = LaporanModel::findOrFail($id);
            $laporan->status = 'tidak diterima';
            $laporan->alasan_penolakan = $request->alasan_ditolak ?? 'Tidak ada alasan yang diberikan';
            $laporan->ditolak_oleh = Auth::user()->pengguna_id;
            $laporan->save();
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil ditolak.'
                ]);
            }

            return redirect()->back()->with('success', 'Laporan berhasil ditolak.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menolak laporan: ' . $e->getMessage()
                ], 500);
            }

            return dd('error', 'Gagal menolak laporan: ' . $e->getMessage());
        }
    }

    public function pilih_teknisi(string $id, Request $request)
    {
        try {
            LaporanModel::where('fasilitas_id', $id)
                ->where('status', 'memilih teknisi')
                ->update(['status' => 'diperbaiki', 'teknisi_id' =>  $request->teknisi, 'ditugaskan_oleh' => Auth::user()->pengguna_id]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Teknisi berhasil dipilih.'
                ]);
            }
            return redirect()->back()->with('success', 'Teknisi berhasil dipilih.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal memilih teknisi: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal memilih teknisi: ' . $e->getMessage());
        }
    }

    public function selesaikan(string $id, Request $request)
    {
        try {
            LaporanModel::where('fasilitas_id', $id)
                ->where('status', 'telah diperbaiki')
                ->update(['status' => 'selesai']);

            DB::table('spk_kriteria')
                ->whereIn('spk_id', function ($query) use ($id) {
                    $query->select('spk_id')
                        ->from('spk')
                        ->where('fasilitas_id', $id);
                })
                ->delete();
                
            DB::table('spk')
                ->where('fasilitas_id', $id)
                ->delete();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil diselesaikan.'
                ]);
            }

            return redirect()->back()->with('success', 'Laporan berhasil diselesaikan.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal menyelesaikan laporan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal menyelesaikan laporan: ' . $e->getMessage());
        }
    }

    public function revisi(string $id, Request $request)
    {
        try {
            LaporanModel::where('fasilitas_id', $id)
                ->where('status', 'telah diperbaiki')
                ->update([
                    'status' => 'revisi',
                    'alasan_revisi' => $request->alasan_revisi ?? 'Tidak ada alasan yang diberikan'
                ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true, // Ubah 'status' menjadi 'success'
                    'message' => 'Laporan direvisi dan dikirimkan kembali ke teknisi.'
                ]);
            }
            return redirect()->back()->with('success', 'Laporan direvisi dan dikirimkan kembali ke teknisi.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false, // Ubah 'status' menjadi 'success'
                    'message' => 'Gagal menyelesaikan laporan: ' . $e->getMessage()
                ], 500);
            }
            return redirect()->back()->with('error', 'Gagal menyelesaikan laporan: ' . $e->getMessage());
        }
    }

    public function sistem_pendukung_keputusan()
    {
        $breadcrumb = (object) [
            'title' => 'Sistem Pendukung Keputusan',
            'list' => ['Sistem Pendukung Keputusan']
        ];
        $page = (object) [
            'title' => 'Sistem Pendukung Keputusan',
            'subtitle' => 'Sistem Pendukung Keputusan'
        ];
        return view('sarpras.sistem_pendukung_keputusan', compact('breadcrumb', 'page'));
    }

    public function data_kriteria(Request $request)
    {
        if ($request->ajax()) {
            $data = KriteriaModel::select(['kriteria_id', 'kode', 'nama', 'bobot', 'jenis', 'deskripsi'])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $btn = '
                    <button type="button" class="btn btn-sm btn-primary btnEditKriteria" data-id="' . $row->kriteria_id . '">Edit</button>';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }

    public function edit_kriteria($id)
    {
        $breadcrumb = (object) [
            'title' => 'Edit Kriteria',
            'list' => ['Edit Kriteria']
        ];
        $page = (object) [
            'title' => 'Edit Kriteria',
            'subtitle' => 'Edit Data Kriteria'
        ];

        $kriteria = KriteriaModel::findOrFail($id);
        return view('sarpras.edit_kriteria', compact('kriteria', 'breadcrumb', 'page'));
    }

    public function update_kriteria(Request $request)
    {
        $rules = [
            'kode' => 'required|string|max:50|unique:kriteria,kode,' . $request->id . ',kriteria_id',
            'nama' => 'required|string|max:255',
            'bobot' => 'required|numeric|min:0|max:1',
            'jenis' => 'required|in:Benefit,Cost',
            'deskripsi' => 'nullable|string|max:500'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ], 422); // Tambahkan status code 422 untuk validation error
        }

        try {
            $check = KriteriaModel::find($request->id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    'status' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage(),
                'msgField' => []
            ], 500);
        }
    }

    public function destroy_kriteria($id)
    {
        $kriteria = KriteriaModel::find($id);

        if (!$kriteria) {
            return redirect()->route('sarpras.sistem_pendukung_keputusan')->with('error', 'Kategori tidak ditemukan.');
        }

        $kriteria->delete();

        return redirect()->route('sarpras.sistem_pendukung_keputusan')->with('success', 'Kategori berhasil dihapus.');
    }

    public function data_crisp(Request $request)
    {
        if ($request->ajax()) {
            $data = CrispModel::with('kriteria')->select(['crisp_id', 'kriteria_id', 'judul', 'deskripsi', 'poin'])->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $btn = '
                    <button type="button" class="btn btn-sm btn-primary btnEditCrisp" data-id="' . $row->crisp_id . '">Edit</button>
                    <form action="' . route('sarpras.destroy_crisp', $row->crisp_id) . '" id="formDeleteCrisp" method="POST" style="display:inline;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button class="btn btn-sm btn-danger">Hapus</button>
                    </form>';
                    return $btn;
                })
                ->rawColumns(['aksi'])
                ->make(true);
        }
    }

    public function create_crisp()
    {
        $breadcrumb = (object) [
            'title' => 'Tambah Crisp',
            'list' => ['Tambah Crisp']
        ];
        $page = (object) [
            'title' => 'Tambah Crisp',
            'subtitle' => 'Tambah Data Crisp'
        ];

        $kriteria = KriteriaModel::all();

        return view('sarpras.create_crisp', compact('kriteria', 'breadcrumb', 'page'));
    }

    public function store_crisp(Request $request)
    {
        $rules = [
            'kriteria_id' => 'required|exists:kriteria,kriteria_id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
            'poin' => 'required|numeric|min:0'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ], 422); // Tambahkan status code 422 untuk validation error
        }
        try {
            CrispModel::create([
                'kriteria_id' => $request->kriteria_id,
                'judul' => $request->judul,
                'deskripsi' => $request->deskripsi,
                'poin' => $request->poin
            ]);
            return response()->json([
                'success' => true,
                // 'status' => true,
                'message' => 'Crisp berhasil ditambahkan.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage(),
                'msgField' => []
            ], 500);
        }
    }

    public function edit_crisp($id)
    {
        $breadcrumb = (object) [
            'title' => 'edit Crisp',
            'list' => ['edit Crisp']
        ];
        $page = (object) [
            'title' => 'edit Crisp',
            'subtitle' => 'edit Data Crisp'
        ];

        $crisp = CrispModel::with('kriteria')->findOrFail($id);
        return view('sarpras.edit_crisp', compact('crisp', 'breadcrumb', 'page'));
    }

    public function update_crisp(Request $request, $id)
    {
        $rules = [
            'kriteria_id' => 'required|exists:kriteria,kriteria_id',
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string|max:500',
            'poin' => 'required|numeric|min:0'
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validasi Gagal',
                'msgField' => $validator->errors()
            ], 422); // Tambahkan status code 422 untuk validation error
        }

        try {
            $check = CrispModel::find($id);
            if ($check) {
                $check->update($request->all());
                return response()->json([
                    // 'status' => true,
                    'success' => true,
                    'message' => 'Data berhasil diupdate'
                ]);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tidak ditemukan'
                ]);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menyimpan data: ' . $e->getMessage(),
                'msgField' => []
            ], 500);
        }
    }

    public function destroy_crisp($id)
    {
        $crisp = CrispModel::find($id);

        if (!$crisp) {
            return redirect()->route('sarpras.sistem_pendukung_keputusan')->with('error', 'Crisp tidak ditemukan.');
        }

        $crisp->delete();

        return redirect()->route('sarpras.sistem_pendukung_keputusan')->with('success', 'Crisp berhasil dihapus.');
    }

    public function statistik(Request $request)
    {
        $breadcrumb = (object) [
            'title' => 'Statistik',
            'list' => ['Statistik']
        ];

        $page = (object) [
            'title' => 'Statistik',
            'subtitle' => 'Statistik'
        ];

        $activeMenu = 'Statistik';

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

        return view('sarpras.statistik', compact(
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

    public function ajukan_laporan()
    {
        $breadcrumb = (object) [
            'title' => 'Ajukan Laporan',
            'list' => ['Ajukan Laporan']
        ];

        $page = (object) [
            'title' => 'Ajukan Laporan',
            'subtitle' => 'Ajukan Laporan Kerusakan Sarana dan Prasarana'
        ];

        $activeMenu = 'laporan';

        $kriteria = KriteriaModel::all();

        return view('sarpras.ajukan_laporan', compact('breadcrumb', 'page', 'activeMenu', 'kriteria'));
    }

    public function data_laporan(Request $request)
    {
        $data = FasilitasModel::whereHas('laporan', function ($query) {
            $query->where('status', 'diterima');
        })->with([
            'laporan' => function ($query) {
                $query->where('status', 'diterima')->with('pelapor');
            },
            'ruangan.lantai.gedung',
            'spk'
        ])->get();

        $allKriteria = KriteriaModel::all();

        return datatables()->of($data)
            ->addIndexColumn()
            ->addColumn('gedung', fn($row) => $row->ruangan->lantai->gedung->gedung_nama . ' - ' . $row->ruangan->lantai->lantai_nama ?? '-')
            ->addColumn('lantai', fn($row) => $row->ruangan->lantai->lantai_nama ?? '-')
            ->addColumn('ruangan', fn($row) => $row->ruangan->ruangan_nama ?? '-')
            ->addColumn('fasilitas', fn($row) => $row->fasilitas_nama ?? '-')
            ->addColumn('kriteria', function ($row) use ($allKriteria) {
                $columns = [];

                $spk = $row->spk;

                foreach ($allKriteria as $k) {
                    $nilai = '-';

                    if ($spk) {
                        $match = $spk->kriteria->where('pivot.kriteria_id', $k->kriteria_id)->first();
                        $nilai = $match ? $match->pivot->nilai : '-';
                    }

                    $columns['kriteria_' . $k->kriteria_id] = $nilai;
                }

                return $columns;
            })
            ->addColumn('aksi', function ($row) {
                return '<button onclick="modalAction(\'' . url('sarpras/laporan_masuk/' . $row->fasilitas_id) . '?source=ajukan\')" class="btn btn-sm btn-info">Detail</button>';
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }

    private function normalisasiVektor(array $data): array
    {
        // Ambil semua nama kriteria dari alternatif pertama
        $kriteria = array_keys($data[0]['kriteria']);

        // 1. Hitung penyebut (akar jumlah kuadrat per kriteria)
        $penyebut = [];
        foreach ($kriteria as $k) {
            $penyebut[$k] = sqrt(array_sum(array_map(function ($d) use ($k) {
                return pow($d['kriteria'][$k], 2);
            }, $data)));
        }

        // 2. Hitung nilai normalisasi per alternatif
        $normalisasi = [];
        foreach ($data as $i => $alt) {
            $normalisasi[$i]['judul'] = $alt['judul'];
            $normalisasi[$i]['laporan_id'] = $alt['laporan_id']; // tambahkan laporan_id jika ada
            foreach ($kriteria as $k) {
                $normalisasi[$i]['kriteria'][$k] = $alt['kriteria'][$k] / ($penyebut[$k] ?: 1);
            }
        }
        return $normalisasi;
    }

    private function nilaiTerbobot(array $data, array $bobot): array
    {
        $hasil = [];

        foreach ($data as $i => $alt) {
            $hasil[$i]['judul'] = $alt['judul'];
            $hasil[$i]['laporan_id'] = $alt['laporan_id'] ?? null;
            foreach ($alt['kriteria'] as $namaKriteria => $nilaiNormal) {
                $bobotKriteria = $bobot[$namaKriteria] ?? 0;
                $hasil[$i]['kriteria'][$namaKriteria] = $nilaiNormal * $bobotKriteria;
            }
        }

        return $hasil;
    }

    private function solusiIdeal(array $data, array $jenis): array
    {
        $kriteriaList = array_keys($data[0]['kriteria']);
        $idealPositif = [];
        $idealNegatif = [];

        foreach ($kriteriaList as $kriteria) {
            $nilai = array_column(array_column($data, 'kriteria'), $kriteria);
            if (($jenis[$kriteria] ?? 'benefit') === 'benefit') {
                $idealPositif[$kriteria] = max($nilai);
                $idealNegatif[$kriteria] = min($nilai);
            } else {
                $idealPositif[$kriteria] = min($nilai);
                $idealNegatif[$kriteria] = max($nilai);
            }
        }
        return [$idealPositif, $idealNegatif];
    }

    private function jarakSolusiIdeal(array $data, array $idealPositif, array $idealNegatif): array
    {
        $result = [];

        foreach ($data as $i => $alt) {
            $dPlus = 0;
            $dMinus = 0;

            foreach ($alt['kriteria'] as $kriteria => $nilai) {
                $dPlus += pow($idealPositif[$kriteria] - $nilai, 2);
                $dMinus += pow($nilai - $idealNegatif[$kriteria], 2);
            }

            $result[] = [
                'laporan_id' => $alt['laporan_id'] ?? null,
                'judul' => $alt['judul'],
                'D_plus' => round(sqrt($dPlus), 4),
                'D_minus' => round(sqrt($dMinus), 4),
            ];
        }

        return $result;
    }

    private function hitungPreferensi(array $jarak): array
    {
        $hasil = [];

        foreach ($jarak as $item) {
            $Dplus = $item['D_plus'];
            $Dminus = $item['D_minus'];

            // Perhitungan nilai preferensi V berdasarkan rumus Vi = D- / (D+ + D-)
            $Vi = ($Dplus + $Dminus) == 0 ? 0 : $Dminus / ($Dplus + $Dminus);

            $hasil[] = [
                'laporan_id' => $item['laporan_id'],
                'judul' => $item['judul'],
                'D_plus' => round($Dplus, 4),
                'D_minus' => round($Dminus, 4),
                'V' => round($Vi, 4),
            ];
        }

        // Urutkan berdasarkan nilai V dari yang terkecil (semakin kecil V, semakin baik)
        usort($hasil, fn($a, $b) => $b['V'] <=> $a['V']);

        // Tambahkan peringkat
        foreach ($hasil as $i => &$r) {
            $r['ranking'] = $i + 1;
        }

        return $hasil;
    }

    public function proses_spk()
    {

        $fasilitas = FasilitasModel::whereHas('laporan', function ($query) {
            $query->where('status', 'diterima');
        })->with([
            'laporan' => function ($query) {
                $query->where('status', 'diterima')->with('pelapor');
            },
            'ruangan.lantai.gedung',
            'spk'
        ])->get();

        // Bentuk data matriks alternatif
        $data = [];
        $kriteria = [];

        foreach ($fasilitas as $f) {
            $judul = ($f->ruangan->lantai->gedung->gedung_nama ?? '-') . ' ' . ($f->ruangan->ruangan_nama ?? '-') . ' - ' . ($f->fasilitas_nama ?? '-');
            $fasilitas_id = $f->fasilitas_id;
            $spk = $f->spk; // ambil SPK 

            if (!$spk) {
                continue; // skip laporan ini kalau tidak ada SPK-nya
            }

            $nilaiKriteria = [];

            foreach ($spk->kriteria as $item) {
                $nilaiKriteria[$item->nama] = (float) $item->pivot->nilai;

                if (!in_array($item->nama, $kriteria)) {
                    $kriteria[] = $item->nama;
                }
            }

            $data[] = [
                'laporan_id' => $fasilitas_id,
                'judul' => $judul,
                'kriteria' => $nilaiKriteria
            ];
        }

        $normalisasi = $this->normalisasiVektor($data);

        $bobotKriteria = [];
        foreach (KriteriaModel::all() as $k) {
            $bobotKriteria[$k->nama] = floatval($k->bobot);
            $jenisKriteria[$k->nama] = strtolower($k->jenis);
        }
        $terbobot = $this->nilaiTerbobot($normalisasi, $bobotKriteria);


        [$idealPositif, $idealNegatif] = $this->solusiIdeal($terbobot, $jenisKriteria);

        $jarak = $this->jarakSolusiIdeal($terbobot, $idealPositif, $idealNegatif);

        $hasilAkhir = $this->hitungPreferensi($jarak);

        return response()->json([
            'success' => true,
            'data' => $hasilAkhir
        ]);
    }

    public function proses_ajukan_laporan(string $id, Request $request)
    {
        try {
            // Update semua laporan dengan fasilitas_id = $id dan status 'diterima'
            LaporanModel::where('fasilitas_id', $id)
                ->where('status', 'diterima')
                ->update(['status' => 'konfirmasi']);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Laporan berhasil diajukan ke admin.'
                ]);
            }

            return redirect()->back()->with('success', 'Laporan berhasil diajukan ke admin.');
        } catch (\Exception $e) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengajukan laporan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal mengajukan laporan: ' . $e->getMessage());
        }
    }
}
<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Admin;
use App\Models\Dosen;
use App\Models\Semester;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\KelasKuliah;
use Illuminate\Http\Request;
use App\Models\NilaiMahasiswa;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function dashboard()
    {
        $jml_mahasiswa = Mahasiswa::count();
        $jml_dosen = Dosen::count();
        $jml_matkul = MataKuliah::count();
        $jml_kelas = KelasKuliah::count();
        $mahasiswa = Mahasiswa::select('angkatan')->distinct()->orderBy('angkatan')->get();
        $semesters = Semester::all();
        $defaultAngkatan = \App\Models\Mahasiswa::max('angkatan');
        $listProdi = Mahasiswa::select('program_studi')->distinct()->orderBy('program_studi')->get();
        // dd($semester);
        $title = 'Angkatan';
        return view('pages-admin.dashboard', compact('jml_mahasiswa', 'jml_dosen', 'jml_matkul', 'jml_kelas', 'mahasiswa', 'title',  'semesters', 'defaultAngkatan', 'listProdi'));
    }

    public function index(Request $request)
    {
        $query = Admin::join('auth', 'admin.id_auth', '=', 'auth.id')
            ->select('admin.*');

        // Cek apakah ada parameter pencarian
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($query) use ($searchTerm) {
                $query->where('admin.nama', 'like', '%' . $searchTerm . '%');
            });
        }

        $admin = $query->paginate(20);

        $startNumber = ($admin->currentPage() - 1) * $admin->perPage() + 1;

        return view('pages-admin.admin.admins', [
            'data' => $admin,
            'startNumber' => $startNumber,
        ])->with('success', 'Data Admin Ditemukan');
    }

    public function create()
    {
        return view('pages-admin.admin.tambah_admin');
    }

    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'nama' => 'required|string',
            'email' => 'required|email',
            'telp' => 'required',
        ]);
        // dd($validate);
        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->first(),
            ], 422);
        }

        try {
            $password = 'tekkim123';
            $auth = User::create([
                'username' => $request->email,
                'password' => Hash::make($password),
                'role' => 'admin',
            ]);

            $id_auth = $auth->id;

            Admin::create([
                'nama' => $request->nama,
                'email' => $request->email,
                'telp' => $request->telp,
                'id_auth' => $id_auth
            ]);

            // return redirect()->route('admin.admins')->with('success', 'Data Berhasil Ditambahkan');
            return response()->json(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal dihapus: ' . $e->getMessage()], 500);
            // return redirect()->back()->withErrors(['errors' => 'Data Gagal Ditambahkan: '.$e->getMessage()])->withInput();
        }
    }

    public function detailUser()
    {
        return view('pages-admin.admin.detail_user');
    }

    public function edit($id)
    {
        // dd($id);
        $admin = Admin::join('auth', 'admin.id_auth', '=', 'auth.id')
            ->where('admin.id', $id)
            ->select('admin.*', 'auth.username') // Sesuaikan dengan kolom-kolom yang Anda butuhkan dari tabel auth
            ->first();
        // dd($admin);
        if (!$admin) {
            return redirect()->route('admin.admins')->withErrors(['error' => 'Admin not found']);
        }
        return view('pages-admin.admin.edit_admin', [
            'success' => 'Data Found',
            'data' => $admin,
        ]);
    }

    public function update(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'nama' => 'required|string',
            'email' => 'required|email',
            'telp' => 'required',
        ]);
        // dd($validate);
        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->first(),
            ], 422);
        }

        try {
            // Update data produk berdasarkan ID
            $admin = Admin::where('id', $id)->first();
            $admin->update([
                'nama' => $request->nama,
                'telp' => $request->telp,
                'email' => $request->email,
            ]);

            // return redirect()->route('admin.admins')->with([
            //     'success' => 'User updated successfully.',
            //     'data' => $admin
            // ]);
            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate', 'data' => $admin]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal diupdate: ' . $e->getMessage()], 500);
            // return redirect()->route('admin.admins.edit', $id)->with('error', 'Error updating user: ' . $e->getMessage())->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $admin = Admin::where('id_auth', $id)->delete();
            if ($admin) {
                User::where('id', $id)->delete();
                return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal dihapus: ' . $e->getMessage()], 500);
        }
    }

    public function resetPassword(Request $request)
    {
        $id = $request->id;
        //  dd($id);

        try {
            $admin = Admin::findOrFail($id);
            $auth = User::findOrFail($admin->id_auth);
            $auth->password = Hash::make('admin123');
            $auth->save();

            return response()->json(['status' => 'success', 'message' => 'Berhasil reset password']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Gagal reset password: ' . $e->getMessage()], 500);
        }
    }

    public function chartCplByAngkatan(Request $request)
    {
        $angkatan = $request->angkatan;

        $data = NilaiMahasiswa::join('mahasiswa', 'nilai_mahasiswa.mahasiswa_id', '=', 'mahasiswa.id')
            ->join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', '=', 'soal_sub_cpmk.id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', '=', 'sub_cpmk.id')
            ->join('cpmk', 'sub_cpmk.cpmk_id', '=', 'cpmk.id')
            ->join('cpl', 'cpmk.cpl_id', '=', 'cpl.id')
            ->where('mahasiswa.angkatan', $angkatan)
            // ->selectRaw('mahasiswa.program_studi as prodi, cpl.kode_cpl, ROUND(SUM(nilai_mahasiswa.nilai * soal_sub_cpmk.bobot_soal) / SUM(soal_sub_cpmk.bobot_soal), 1) as rata_rata_cpl')
            ->selectRaw('mahasiswa.program_studi as prodi, cpl.kode_cpl, ROUND(AVG(nilai_mahasiswa.nilai), 1) as rata_rata_cpl')
            ->groupBy('prodi', 'cpmk.id','cpl.id')
            ->get();

        // Pisahkan per prodi
        $hasil = $data->groupBy('prodi')->map(function ($itemsPerProdi) {
            // Kelompokkan per kode CPL, lalu hitung rata-rata antar indikatornya
            $nilaiPerCPL = $itemsPerProdi->groupBy('kode_cpl')->map(function ($indikatorItems) {
                return round($indikatorItems->avg('rata_rata_cpl'), 1);
            });

            return [
                'labels' => $nilaiPerCPL->keys(),
                'values' => $nilaiPerCPL->values()
            ];
        });

        return response()->json($hasil);
    }

    public function chartCplKelasDashboard(Request $request)
    {
        $angkatan = $request->input('angkatan');
        $prodi = $request->input('prodi');

        if (!$angkatan || !$prodi) {
            return response()->json(['error' => 'angkatan dan prodi harus diisi'], 400);
        }

        $query = NilaiMahasiswa::join('mahasiswa', 'nilai_mahasiswa.mahasiswa_id', 'mahasiswa.id')
            ->join('matakuliah_kelas', 'nilai_mahasiswa.matakuliah_kelasid', 'matakuliah_kelas.id')
            ->join('kelas', 'matakuliah_kelas.kelas_id', '=', 'kelas.id')
            ->join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->join('cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
            ->join('cpl', 'cpmk.cpl_id', 'cpl.id')
            ->join('rps', 'cpmk.rps_id', 'rps.id')
            ->join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
            // ->selectRaw('kelas.nama_kelas as kelas, cpl.kode_cpl, ROUND(SUM(nilai_mahasiswa.nilai * soal_sub_cpmk.bobot_soal) / SUM(soal_sub_cpmk.bobot_soal), 1) as rata_rata_cpl')
            ->selectRaw('kelas.nama_kelas as kelas, cpl.kode_cpl, ROUND(AVG(nilai_mahasiswa.nilai), 1) as rata_rata_cpl')
            ->where('mahasiswa.angkatan', $angkatan)
            ->where('mahasiswa.program_studi', $prodi)
            ->groupBy('cpl.id','cpmk.id', 'kelas.nama_kelas');

        $averageCPL = $query->get();
        // dd($averageCPL);

        $hasil = $averageCPL->groupBy('kelas')->map(function ($groupedItems) {
            $nilaiPerCPL = $groupedItems->groupBy('kode_cpl')->map(function ($indikatorItems) {
                return round($indikatorItems->avg('rata_rata_cpl'), 1);
            });

             return [
                'kelas' => $groupedItems->first()->kelas,
                'labels' => $nilaiPerCPL->keys(),
                'values' => $nilaiPerCPL->values()
            ];
        })->values();


        return response()->json($hasil);
    }


    public function chartCplDashboard(Request $request)
    {
        // Ambil tahun sekarang
        $currentYear = date('Y');
        $startYear = $currentYear - 3;

        // Cek jika request mengandung rentang angkatan
        if ($request->has('angkatan_start') && $request->has('angkatan_end')) {
            $startYear = $request->input('angkatan_start');
            $endYear = $request->input('angkatan_end');
        } else {
            // Jika tidak ada filter, gunakan default (tahun sekarang dan 3 tahun ke belakang)
            $endYear = $currentYear;
        }

        $resultsByYear = [];

        // Loop untuk tiap angkatan dalam rentang yang diberikan
        for ($year = $endYear; $year >= $startYear; $year--) {
            $query = NilaiMahasiswa::join('mahasiswa', 'nilai_mahasiswa.mahasiswa_id', 'mahasiswa.id')
                ->join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
                ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
                ->join('cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
                ->join('cpl', 'cpmk.cpl_id', 'cpl.id')
                ->join('rps', 'cpmk.rps_id', 'rps.id')
                ->join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
                ->selectRaw('cpl.kode_cpl, ROUND(SUM(nilai_mahasiswa.nilai * soal_sub_cpmk.bobot_soal) / SUM(soal_sub_cpmk.bobot_soal), 1) as rata_rata_cpl')
                ->where('mahasiswa.angkatan', $year)
                ->groupBy('cpl.id','mata_kuliah.id');

            $averageCPL = $query->get();

            $results = $averageCPL->groupBy('kode_cpl')->map(function ($group) {
                return $group->avg('rata_rata_cpl');
            });

            $labels = $results->keys()->toArray(); // Ambil kode CPL sebagai label
            $values = $results->values()->toArray();

            $resultsByYear[] = [
                'angkatan' => $year,
                'labels' => $labels,
                'values' => $values
            ];
        }

        return response()->json($resultsByYear);
    }

    public function chartCplSmtDashboard(Request $request)
    {
        // Ambil semester yang dipilih dari request, jika tidak ada gunakan semester aktif
        $semesterId = $request->input('semester_id', null);

        if ($semesterId) {
            // Jika semester dipilih melalui filter
            $selectedSemester = Semester::find($semesterId);
        } else {
            // Jika tidak ada semester yang dipilih, gunakan semester aktif
            $selectedSemester = Semester::where('is_active', "1")->first();
        }
        $query = NilaiMahasiswa::join('mahasiswa', 'nilai_mahasiswa.mahasiswa_id', 'mahasiswa.id')
            ->join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->join('cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
            ->join('cpl', 'cpmk.cpl_id', 'cpl.id')
            ->join('rps', 'cpmk.rps_id', 'rps.id')
            ->join('matakuliah_kelas', 'nilai_mahasiswa.matakuliah_kelasid', 'matakuliah_kelas.id')
            ->join('semester', 'matakuliah_kelas.semester_id', 'semester.id')
            ->join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
            ->selectRaw('cpl.kode_cpl, ROUND(SUM(nilai_mahasiswa.nilai * soal_sub_cpmk.bobot_soal) / SUM(soal_sub_cpmk.bobot_soal), 1) as rata_rata_cpl')
            ->groupBy('cpl.id','mata_kuliah.id')
            ->where('semester.id', $selectedSemester->id);

        // $sql = $query->toSql();

        $averageCPL = $query->get();

        $results = $averageCPL->groupBy('kode_cpl')->map(function ($group) {
            return $group->avg('rata_rata_cpl');
        });

        $labels = $results->keys()->toArray(); // Ambil kode CPL sebagai label
        $values = $results->values()->toArray();

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }
}

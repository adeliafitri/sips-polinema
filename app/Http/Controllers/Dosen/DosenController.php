<?php

namespace App\Http\Controllers\Dosen;

use App\Models\Semester;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\KelasKuliah;
use Illuminate\Http\Request;
use App\Models\NilaiMahasiswa;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class DosenController extends Controller
{
    public function dashboard(Request $request) {
        $mahasiswa = Mahasiswa::select('angkatan')->distinct()->orderBy('angkatan')->get();
        $getSemesterAktif = Semester::where('is_active', '1')->first();
        $semesters = Semester::all();
        $mata_kuliah = MataKuliah::all();
        $listProdi = Mahasiswa::select('program_studi')->distinct()->orderBy('program_studi')->get();
        $defaultAngkatan = \App\Models\Mahasiswa::max('angkatan');

        // Ambil nilai matkul_id dari request
        $matkulId = $request->input('matkul_id');

        // Query kelas_kuliah dengan filter matkul_id jika ada
        $kelas_kuliah = KelasKuliah::join('kelas', 'matakuliah_kelas.kelas_id', '=', 'kelas.id')
            ->join('rps', 'matakuliah_kelas.rps_id', 'rps.id')
            ->join('mata_kuliah', 'rps.matakuliah_id', '=', 'mata_kuliah.id')
            ->join('dosen', 'matakuliah_kelas.dosen_id', '=', 'dosen.id')
            ->join('semester', 'matakuliah_kelas.semester_id', '=', 'semester.id')
            ->leftJoin('nilaiakhir_mahasiswa', 'matakuliah_kelas.id', '=', 'nilaiakhir_mahasiswa.matakuliah_kelasid')
            ->select(
                'rps.id as id_rps', 'matakuliah_kelas.id as id_kelas', 'mata_kuliah.id as id_matkul',
                'mata_kuliah.nama_matkul as nama_matkul', 'mata_kuliah.kode_matkul', 'semester.tahun_ajaran',
                'semester.semester', 'kelas.nama_kelas'
            )
            ->where('dosen.id_auth', Auth::user()->id)
            ->where('semester.is_active', '1');

        // Tambahkan filter jika matkul_id ada
        if ($matkulId) {
            $kelas_kuliah = $kelas_kuliah->where('mata_kuliah.id', $matkulId);
        }else{
            $kelas_kuliah = $kelas_kuliah->where('mata_kuliah.id', 1);
        }

        $kelas_kuliah = $kelas_kuliah->distinct()
            ->orderBy('mata_kuliah.id', 'asc')
            ->get();

        return view('pages-dosen.dashboard', [
            'data' => $kelas_kuliah,
            'semester' => $getSemesterAktif,
            'mahasiswa' => $mahasiswa,
            'semesters' => $semesters,
            'listProdi' => $listProdi,
            'mataKuliah' => $mata_kuliah,
            'selectedMatkul' => $matkulId,
            'defaultAngkatan' => $defaultAngkatan,
        ]);
    }


    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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
            ->groupBy('prodi', 'cpmk.id', 'cpl.id')
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
            ->groupBy('cpl.id', 'cpmk.id', 'kelas.nama_kelas');

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

        // dd($hasil);

        // $results = $averageCPL->groupBy('kode_cpl')->map(function ($group) {
        //     return $group->avg('rata_rata_cpl');
        // });

        // $labels = $results->keys()->toArray();
        // $values = $results->values()->toArray();

        // return response()->json([
        //     'angkatan' => $angkatan,
        //     'prodi' => $prodi,
        //     'labels' => $labels,
        //     'values' => $values
        // ]);
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
            $selectedSemester = Semester::where('is_active', '1')->first();
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

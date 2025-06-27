<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Models\Cpmk;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use Illuminate\Http\Request;
use App\Models\NilaiMahasiswa;
use App\Models\NilaiAkhirMahasiswa;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class MahasiswaController extends Controller
{
    public function dashboard() {
        $mahasiswa = Mahasiswa::where('id_auth', Auth::user()->id)->first();
        $total_sks = 144;
        $total_kelas_kuliah = NilaiAkhirMahasiswa::join('mahasiswa', 'nilaiakhir_mahasiswa.mahasiswa_id', '=', 'mahasiswa.id')
        ->where('mahasiswa.id_auth', Auth::user()->id)->count('matakuliah_kelasid');
        $data = NilaiAkhirMahasiswa::join('mahasiswa', 'nilaiakhir_mahasiswa.mahasiswa_id', '=', 'mahasiswa.id')
        ->join('matakuliah_kelas', 'nilaiakhir_mahasiswa.matakuliah_kelasid', 'matakuliah_kelas.id')
        ->join('rps', 'matakuliah_kelas.rps_id', 'rps.id')
        ->join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
        ->select('mata_kuliah.*','nilaiakhir_mahasiswa.id as id_nilai', 'nilaiakhir_mahasiswa.nilai_akhir as nilai_akhir')
        ->where('mahasiswa.id_auth', Auth::user()->id)
        ->get();
        // dd($total_sks);
        // $keterangan = [];
        $total_sks_lulus = 0;

        foreach ($data as $datas) {
            $nilai_akhir = $datas->nilai_akhir;

            if ($nilai_akhir >= 61) {
                $total_sks_lulus += $datas->sks;
            }
        }

        // $total_sks_diambil = $count = NilaiAkhirMahasiswa::join('mahasiswa', 'nilaiakhir_mahasiswa.mahasiswa_id', '=', 'mahasiswa.id')
        // ->join('matakuliah_kelas', 'nilaiakhir_mahasiswa.matakuliah_kelasid', 'matakuliah_kelas.id')
        // ->join('rps', 'matakuliah_kelas.rps_id', 'rps.id')
        // ->join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
        // ->where('mahasiswa.id_auth', Auth::user()->id)
        // ->sum('mata_kuliah.sks');

        $query = NilaiMahasiswa::join('mahasiswa', 'nilai_mahasiswa.mahasiswa_id', 'mahasiswa.id')
            ->join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->join('cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
            ->join('cpl', 'cpmk.cpl_id', 'cpl.id')
            ->join('rps', 'cpmk.rps_id', 'rps.id')
            ->join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
            // ->selectRaw('cpl.id, cpl.kode_cpl, ROUND(SUM(nilai_mahasiswa.nilai * soal_sub_cpmk.bobot_soal) / SUM(soal_sub_cpmk.bobot_soal), 1) as rata_rata_cpl')
            ->selectRaw('cpl.id, cpl.kode_cpl, ROUND(AVG(nilai_mahasiswa.nilai), 1) as rata_rata_cpl')
            ->groupBy('cpl.id', 'cpmk.id')
            ->where('mahasiswa.id_auth', Auth::user()->id);

        // $sql = $query->toSql();

        $averageCPL = $query->get();

        // dd($averageCPL);

        $totalMataKuliah = Cpmk::join('cpl', 'cpmk.cpl_id', 'cpl.id')
        ->join('rps', 'cpmk.rps_id', 'rps.id')
        ->join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
        ->selectRaw('COUNT(distinct mata_kuliah.id) as total_matkul, cpl_id, cpl.kode_cpl')->groupBy('cpl_id')->get();
            // dd($totalMataKuliah);
        $results = [];

        foreach ($totalMataKuliah as $total) {
            $cplId = $total->kode_cpl;
            $totalMataKuliahCPL = $total->total_matkul;

            $totalNilaiCPL = $averageCPL->where('kode_cpl', $cplId)->sum('rata_rata_cpl');
            // dd($totalNilaiCPL);
            $percentage = $totalMataKuliahCPL != 0 ? round(($totalNilaiCPL / ($totalMataKuliahCPL*100)) * 100, 2) : 0;
            // dd($percentage);
            $results[] = [
                'kode_cpl' => $cplId,
                'persentase' => $percentage,
            ];
        }

        // dd($results);

        // dd($sql, $results);

        // dd($totalAverageCPL);
        return view('pages-mahasiswa.dashboard', [
            'total_sks' => $total_sks,
            // 'total_sks_diambil' => $total_sks_diambil,
            'total_sks_lulus' => $total_sks_lulus,
            'total_kelas_kuliah' => $total_kelas_kuliah,
            'data' => $results,
            'mahasiswa' => $mahasiswa
        ]);
    }

    public function chartDashboard()
    {
        $query = NilaiMahasiswa::join('mahasiswa', 'nilai_mahasiswa.mahasiswa_id', 'mahasiswa.id')
            ->join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->join('cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
            ->join('cpl', 'cpmk.cpl_id', 'cpl.id')
            ->join('rps', 'cpmk.rps_id', 'rps.id')
            ->join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
            // ->selectRaw('cpl.kode_cpl, ROUND(SUM(nilai_mahasiswa.nilai * soal_sub_cpmk.bobot_soal) / SUM(soal_sub_cpmk.bobot_soal), 1) as rata_rata_cpl')
            ->selectRaw('cpl.kode_cpl, ROUND(AVG(nilai_mahasiswa.nilai), 1) as rata_rata_cpl')
            ->groupBy('cpl.id', 'cpmk.id')
            ->where('mahasiswa.id_auth', Auth::user()->id);

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

    public function chartCplDashboard(Request $request)
    {
        $mahasiswa = Mahasiswa::where('id_auth', Auth::user()->id)->first();
        $query = NilaiMahasiswa::join('mahasiswa', 'nilai_mahasiswa.mahasiswa_id', 'mahasiswa.id')
            ->join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->join('cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
            ->join('cpl', 'cpmk.cpl_id', 'cpl.id')
            ->join('rps', 'cpmk.rps_id', 'rps.id')
            ->join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
            // ->selectRaw('cpl.kode_cpl, ROUND(SUM(nilai_mahasiswa.nilai * soal_sub_cpmk.bobot_soal) / SUM(soal_sub_cpmk.bobot_soal), 1) as rata_rata_cpl')
            ->selectRaw('cpl.kode_cpl, ROUND(AVG(nilai_mahasiswa.nilai), 1) as rata_rata_cpl')
            ->groupBy('cpl.id', 'cpmk.id')
            ->where('mahasiswa.angkatan', $mahasiswa->angkatan)
            ->where('mahasiswa.program_studi', $mahasiswa->program_studi);

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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {

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
}

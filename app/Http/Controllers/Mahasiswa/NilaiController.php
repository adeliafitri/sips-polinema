<?php

namespace App\Http\Controllers\Mahasiswa;

use App\Http\Controllers\Controller;
use App\Models\KelasKuliah;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\NilaiAkhirMahasiswa;
use App\Models\NilaiMahasiswa;
use App\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NilaiController extends Controller
{
    public function index(Request $request)
    {
        $semester = Semester::all();
        $activeSemester = $semester->where('is_active', true)->first();
        $query = KelasKuliah::join('kelas', 'matakuliah_kelas.kelas_id', '=', 'kelas.id')
        ->join('rps', 'matakuliah_kelas.rps_id', 'rps.id')
        ->join('mata_kuliah', 'rps.matakuliah_id', '=', 'mata_kuliah.id')
        ->join('dosen', 'matakuliah_kelas.dosen_id', '=', 'dosen.id')
        ->join('semester', 'matakuliah_kelas.semester_id', '=', 'semester.id')
        ->leftJoin('nilaiakhir_mahasiswa', 'matakuliah_kelas.id', '=', 'nilaiakhir_mahasiswa.matakuliah_kelasid')
        ->join('mahasiswa', 'mahasiswa.id', '=', 'nilaiakhir_mahasiswa.mahasiswa_id')
        ->select('matakuliah_kelas.id as id_kelas', 'nilaiakhir_mahasiswa.nilai_akhir','matakuliah_kelas.*', 'semester.tahun_ajaran', 'semester.semester', 'kelas.nama_kelas as kelas', 'mata_kuliah.kode_matkul as kode_matkul', 'mata_kuliah.nama_matkul as nama_matkul', 'dosen.nama as nama_dosen')
        // ->selectRaw('COUNT(nilaiakhir_mahasiswa.mahasiswa_id) as jumlah_mahasiswa')
        ->where('mahasiswa.id_auth', Auth::user()->id);

        $angkatan = Mahasiswa::where('mahasiswa.id_auth', Auth::user()->id)
            ->select('mahasiswa.angkatan')
            ->first()
            ->angkatan;

        // Cari semester yang dimulai dari tahun angkatan dengan semester ganjil dan hingga 4 tahun ke depan
        $tahunAjaranAwal = $angkatan;
        $tahunAjaranAkhir = $angkatan + 4;

        // Ambil data semester dengan tahun ajaran dari tahun angkatan hingga 4 tahun ke depan
        $semesters = Semester::whereBetween('tahun_ajaran', [$tahunAjaranAwal, $tahunAjaranAkhir])
            ->orderBy('tahun_ajaran')
            ->orderBy('semester')
            ->get();


        if ($request->has('tahun_ajaran')) {
            $tahunAjaranTerm = $request->input('tahun_ajaran');
            $query->where('semester.id', $tahunAjaranTerm);
            $reqTahunAjaran = $semester->where('id', $tahunAjaranTerm)->first();
            $title = $reqTahunAjaran->tahun_ajaran ." ". $reqTahunAjaran->semester;
        } else {
            // If no semester filter is provided, use the active semester
            $query->where('semester.id', $activeSemester->id ?? null);
            $title = $activeSemester->tahun_ajaran ." ". $activeSemester->semester;
        }
        // Cek apakah ada parameter pencarian
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($query) use ($searchTerm) {
                $query->where('kelas.nama_kelas', 'like', '%' . $searchTerm . '%')
                    ->orWhere('mata_kuliah.nama_matkul', 'like', '%' . $searchTerm . '%');
            });
        }

        $query->groupBy('matakuliah_kelas.id');

        $nilai = $query->paginate(20);
        // dd($nilai);
        $startNumber = ($nilai->currentPage() - 1) * $nilai->perPage() + 1;

        return view('pages-mahasiswa.perkuliahan.nilai', [
            'data' => $nilai,
            'semester' => $semesters,
            'title' => $title,
            'startNumber' => $startNumber,
        ])->with('success', 'Data Ditemukan');
    }

    public function show(Request $request, $id)
    {
        $id_mahasiswa = Mahasiswa::where('mahasiswa.id_auth', Auth::user()->id)->first();

        $nilai_mahasiswa = NilaiAkhirMahasiswa::join('mahasiswa', 'nilaiakhir_mahasiswa.mahasiswa_id', '=', 'mahasiswa.id')
            ->join('matakuliah_kelas', 'nilaiakhir_mahasiswa.matakuliah_kelasid', '=', 'matakuliah_kelas.id')
            ->join('rps', 'matakuliah_kelas.rps_id', 'rps.id')
            ->join('mata_kuliah', 'rps.matakuliah_id', '=', 'mata_kuliah.id')
            ->join('semester', 'matakuliah_kelas.semester_id', 'semester.id')
            ->join('dosen', 'matakuliah_kelas.dosen_id', 'dosen.id')
            ->join('kelas', 'matakuliah_kelas.kelas_id', 'kelas.id')
            ->select( 'rps.id as id_rps', 'mata_kuliah.id as id_matkul', 'mata_kuliah.kode_matkul as kode_matkul', 'mata_kuliah.nama_matkul', 'dosen.nama', 'kelas.nama_kelas', 'semester.tahun_ajaran', 'semester.semester', 'nilaiakhir_mahasiswa.*')
            // ->distinct()
            ->where('nilaiakhir_mahasiswa.matakuliah_kelasid', $id)
            ->where('nilaiakhir_mahasiswa.mahasiswa_id', $id_mahasiswa->id)
            ->first();
        // dd($nilai_mahasiswa);

        $query = NilaiMahasiswa::join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', '=', 'soal_sub_cpmk.id')
            ->select('soal_sub_cpmk.*', 'nilai_mahasiswa.nilai as nilai')
            // ->distinct()
            ->where('nilai_mahasiswa.matakuliah_kelasid', $id)
            ->where('nilai_mahasiswa.mahasiswa_id', $id_mahasiswa->id);

        // Cek apakah ada parameter pencarian
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($query) use ($searchTerm) {
                $query->where('sub_cpmk.kode_subcpmk', 'like', '%' . $searchTerm . '%')
                    ->orWhere('sub_cpmk.bentuk_soal', 'like', '%' . $searchTerm . '%');
            });
        }
        // $query->distinct();
        $nilai_subcpmk = $query->paginate(20);

        $startNumber = ($nilai_subcpmk->currentPage() - 1) * $nilai_subcpmk->perPage() + 1;

        // $query_sub = NilaiMahasiswa::join('sub_cpmk', 'nilai_mahasiswa.subcpmk_id', '=', 'sub_cpmk.id')
        //     ->join('cpmk', 'sub_cpmk.cpmk_id', '=', 'cpmk.id')
        //     ->join('cpl', 'cpmk.cpl_id', '=', 'cpl.id')
        //     ->select(
        //         'sub_cpmk.kode_subcpmk',
        //         'cpmk.kode_cpmk',
        //         'cpl.kode_cpl',
        //         DB::raw('SUM(sub_cpmk.bobot_subcpmk) as bobot'),
        //         DB::raw('AVG(nilai_mahasiswa.nilai) as nilai')
        //     )
        //     ->where('nilai_mahasiswa.matakuliah_kelasid', $id)
        //     ->where('nilai_mahasiswa.mahasiswa_id', $id_mahasiswa)
        //     ->groupBy('cpl.kode_cpl', 'cpmk.kode_cpmk', 'sub_cpmk.kode_subcpmk')
        //     ->paginate(20);

        // $subNumber = ($query_sub->currentPage() - 1) * $query_sub->perPage() + 1;

        // $sql_cpmk = DB::table('nilai_mahasiswa as n')
        // ->join('sub_cpmk as s', 'n.subcpmk_id', '=', 's.id')
        // ->join('cpmk as ck', 's.cpmk_id', '=', 'ck.id')
        // ->join('cpl as c', 'ck.cpl_id', '=', 'c.id')
        // ->join(DB::raw('(SELECT
        //                     ck.kode_cpmk,
        //                     s.bentuk_soal,
        //                     AVG(CAST(n.nilai AS DECIMAL(10,2))) AS avg_nilai
        //                 FROM
        //                     nilai_mahasiswa n
        //                     INNER JOIN sub_cpmk s ON n.subcpmk_id = s.id
        //                     INNER JOIN cpmk ck ON s.cpmk_id = ck.id
        //                 WHERE
        //                     n.matakuliah_kelasid = ? AND n.mahasiswa_id = ?
        //                 GROUP BY
        //                     ck.kode_cpmk, s.bentuk_soal) as subquery'), function($join) {
        //     $join->on('ck.kode_cpmk', '=', 'subquery.kode_cpmk');
        // })
        // ->select('c.kode_cpl', 'ck.kode_cpmk')
        // ->selectRaw('SUM(s.bobot_subcpmk)/COUNT(DISTINCT s.bentuk_soal) AS bobot')
        // ->selectRaw('AVG(subquery.avg_nilai) AS avg_nilai')
        // ->where('n.matakuliah_kelasid', '=', $id)
        // ->where('n.mahasiswa_id', '=', $id_mahasiswa)
        // ->groupBy('c.kode_cpl', 'ck.kode_cpmk');
        // $result=$sql_cpmk->get(['id'=> $id, 'id_mahasiswa' => $id_mahasiswa]);

        // $cpmkNumber = ($sql_cpmk->currentPage() - 1) * $sql_cpmk->perPage() + 1;
        $query_sub = [];
        $subNumber = [];
        // dd($nilai_mahasiswa);
        return view('pages-mahasiswa.perkuliahan.detail_nilai', [
            'data' => $nilai_mahasiswa,
            'nilai_subcpmk' => $nilai_subcpmk,
            'startNumber' => $startNumber,
            'sub_cpmk' => $query_sub,
            'subNumber' => $subNumber,
            // 'cpmk' => $result,
            // 'cpmkNumber' => $cpmkNumber
        ]);
    }

    public function nilaiCPL(Request $request)
    {
        // $mahasiswa_id = $request->mahasiswa_id;
        $mahasiswa = Mahasiswa::where('mahasiswa.id_auth', Auth::user()->id)->first();
        $matakuliah_kelasid = $request->matakuliah_kelasid;
        $data = NilaiMahasiswa::join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('soal', 'soal.id', 'soal_sub_cpmk.soal_id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->join('cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
            ->join('cpl', 'cpmk.cpl_id', 'cpl.id')
            ->groupBy('cpl.id')
            ->selectRaw('SUM(nilai_mahasiswa.nilai * soal_sub_cpmk.bobot_soal) / SUM(soal_sub_cpmk.bobot_soal) as total_nilai, SUM(soal_sub_cpmk.bobot_soal) AS bobot_soal, sub_cpmk.kode_subcpmk, cpmk.kode_cpmk as kode_cpmk, cpl.kode_cpl as kode_cpl')
            // ->selectRaw('SUM(nilai_mahasiswa.nilai) AS nilai, SUM(soal_sub_cpmk.bobot_soal) AS bobot_soal, cpl.kode_cpl as kode_cpl')
            ->orderBy('cpl.id')
            ->where('nilai_mahasiswa.mahasiswa_id', $mahasiswa->id)
            ->where('nilai_mahasiswa.matakuliah_kelasid', $matakuliah_kelasid)
            ->paginate(20);

        $startNumber = ($data->currentPage() - 1) * $data->perPage() + 1;
            // dd($data);

            // foreach ($data as $item) {
            //     dd($item->getAttributes());
            // }
        // $data = $nilai_cpl->toArray();
        // $dataIndexed = array_values($data);
        // $startNumber = [];

        if ($request->ajax()) {
            return view('pages-mahasiswa.perkuliahan.partials.nilai_cpl', [
                'data' => $data,
                'startNumber' => $startNumber,
            ])->with('success', 'Data Mata Kuliah Ditemukan');
        }
        return response()->json($data);
    }

    public function nilaiCpmk(Request $request)
    {
        $mahasiswa = Mahasiswa::where('mahasiswa.id_auth', Auth::user()->id)->first();
        $matakuliah_kelasid = $request->matakuliah_kelasid;
        $data = NilaiMahasiswa::join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('soal', 'soal.id', 'soal_sub_cpmk.soal_id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->join('cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
            ->join('cpl', 'cpmk.cpl_id', 'cpl.id')
            ->groupBy('cpmk.id')
            ->selectRaw('SUM(nilai_mahasiswa.nilai * soal_sub_cpmk.bobot_soal) / SUM(soal_sub_cpmk.bobot_soal) as total_nilai, SUM(soal_sub_cpmk.bobot_soal) AS bobot_soal, sub_cpmk.kode_subcpmk, cpmk.kode_cpmk as kode_cpmk, cpl.kode_cpl as kode_cpl')
            // ->selectRaw('SUM(nilai_mahasiswa.nilai) AS nilai, SUM(soal_sub_cpmk.bobot_soal) AS bobot_soal, cpmk.kode_cpmk as kode_cpmk, cpl.kode_cpl as kode_cpl')
            ->orderBy('cpmk.id')
            ->where('nilai_mahasiswa.mahasiswa_id', $mahasiswa->id)
            ->where('nilai_mahasiswa.matakuliah_kelasid', $request->matakuliah_kelasid)
            ->paginate(20);

        // $startNumber = [];
        $startNumber = ($data->currentPage() - 1) * $data->perPage() + 1;

        if ($request->ajax()) {
            return view('pages-mahasiswa.perkuliahan.partials.nilai_cpmk', [
                'data' => $data,
                'startNumber' => $startNumber,
            ])->with('success', 'Data Mata Kuliah Ditemukan');
        }
        return response()->json($data);
    }

    public function nilaiTugas(Request $request)
    {
        $mahasiswa = Mahasiswa::where('mahasiswa.id_auth', Auth::user()->id)->first();
        $data = NilaiMahasiswa::join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('soal', 'soal.id', 'soal_sub_cpmk.soal_id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->join('cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
            ->join('cpl', 'cpmk.cpl_id', 'cpl.id')
            ->select('soal_sub_cpmk.*', 'soal.bentuk_soal as bentuk_soal', 'nilai_mahasiswa.nilai as nilai', 'nilai_mahasiswa.id as id_nilai', 'sub_cpmk.kode_subcpmk as kode_subcpmk', 'cpmk.kode_cpmk', 'cpl.kode_cpl')
            ->where('nilai_mahasiswa.mahasiswa_id', $mahasiswa->id)
            ->where('nilai_mahasiswa.matakuliah_kelasid', $request->matakuliah_kelasid)
            ->get();
        // $startNumber = [];
        $groupedData = $data->groupBy(function ($item) {
            return $item->bentuk_soal . '|' . $item->nilai;
        })->map(function ($items, $key) {
            return [
                'bentuk_soal' => $items->first()->bentuk_soal,
                'nilai' => $items->first()->nilai,
                'bobot_soal' => $items->sum('bobot_soal'),
                'waktu_pelaksanaan' => $items->first()->waktu_pelaksanaan,
                'minggu_sort' => (int) filter_var($items->first()->waktu_pelaksanaan, FILTER_SANITIZE_NUMBER_INT),
                'kode_cpl' => $items->pluck('kode_cpl')->unique()->implode(', '),
                'kode_cpmk' => $items->pluck('kode_cpmk')->unique()->implode(', '),
                'kode_subcpmk' => $items->pluck('kode_subcpmk')->unique()->implode(', '),
            ];
        })->sortBy('minggu_sort')->values();

        // Pagination manual setelah dikelompokkan
        $perPage = 20;
        $page = $request->get('page', 1);
        $pagedData = new \Illuminate\Pagination\LengthAwarePaginator(
            $groupedData->forPage($page, $perPage),
            $groupedData->count(),
            $perPage,
            $page,
            ['path' => $request->url(), 'query' => $request->query()]
        );

        $startNumber = ($pagedData->currentPage() - 1) * $pagedData->perPage() + 1;

        if ($request->ajax()) {
            return view('pages-mahasiswa.perkuliahan.partials.nilai_tugas', [
                // 'groupedData' => $groupedData,
                'data' => $pagedData,
                'startNumber' => $startNumber,
            ])->with('success', 'Data Mata Kuliah Ditemukan');
        }
        return response()->json($pagedData);
    }

    public function chartCPL(Request $request)
    {
        // $mahasiswa_id = $request->mahasiswa_id;
        $mahasiswa = Mahasiswa::where('mahasiswa.id_auth', Auth::user()->id)->first();
        $matakuliah_kelasid = $request->matakuliah_kelasid;
        $data = NilaiMahasiswa::join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->join('cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
            ->join('cpl', 'cpmk.cpl_id', 'cpl.id')
            ->groupBy('cpl.id')
            ->selectRaw('SUM(nilai_mahasiswa.nilai * soal_sub_cpmk.bobot_soal) / SUM(soal_sub_cpmk.bobot_soal) as total_nilai, SUM(soal_sub_cpmk.bobot_soal) AS bobot_soal, cpl.kode_cpl as kode_cpl')
            // ->selectRaw('SUM(nilai_mahasiswa.nilai) AS nilai, SUM(soal_sub_cpmk.bobot_soal) AS bobot_soal, cpl.kode_cpl as kode_cpl')
            ->orderBy('cpl.id')
            ->where('nilai_mahasiswa.mahasiswa_id', $mahasiswa->id)
            ->where('nilai_mahasiswa.matakuliah_kelasid', $matakuliah_kelasid)
            ->get();

            // dd($data);

        // $startNumber = [];

        $labels = $data->pluck('kode_cpl')->toArray();
        $values = $data->pluck('total_nilai')->toArray();

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }

    public function chartCpmk(Request $request)
    {
        $mahasiswa = Mahasiswa::where('mahasiswa.id_auth', Auth::user()->id)->first();
        $matakuliah_kelasid = $request->matakuliah_kelasid;
        $data = NilaiMahasiswa::join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->join('cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
            ->groupBy('cpmk.id')
            ->selectRaw('SUM(nilai_mahasiswa.nilai * soal_sub_cpmk.bobot_soal) / SUM(soal_sub_cpmk.bobot_soal) as total_nilai, SUM(soal_sub_cpmk.bobot_soal) AS bobot_soal, cpmk.kode_cpmk as kode_cpmk')
            // ->selectRaw('SUM(nilai_mahasiswa.nilai) AS nilai, SUM(soal_sub_cpmk.bobot_soal) AS bobot_soal, cpmk.kode_cpmk as kode_cpmk, cpl.kode_cpl as kode_cpl')
            ->orderBy('cpmk.id')
            ->where('nilai_mahasiswa.mahasiswa_id', $mahasiswa->id)
            ->where('nilai_mahasiswa.matakuliah_kelasid', $matakuliah_kelasid)
            ->get();


        $labels = $data->pluck('kode_cpmk')->toArray();
        $values = $data->pluck('total_nilai')->toArray();

        return response()->json([
            'labels' => $labels,
            'values' => $values,
        ]);
    }
}


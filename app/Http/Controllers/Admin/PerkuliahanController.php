<?php

namespace App\Http\Controllers\Admin;

use App\Models\Rps;
use App\Models\Cpmk;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\KelasKuliah;
use Illuminate\Http\Request;
use App\Models\NilaiMahasiswa;
use App\Exports\KelasFormatExcel;
use App\Imports\KelasImportExcel;
use Illuminate\Support\Facades\DB;
use App\Models\NilaiAkhirMahasiswa;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Exports\DaftarMahasiswaFormatExcel;
use App\Imports\DaftarMahasiswaImportExcel;
use Illuminate\Pagination\LengthAwarePaginator;

class PerkuliahanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $getSemesters = Semester::select('id','tahun_ajaran', 'semester')->get();
        // dd($semester);
        $query = KelasKuliah::join('kelas', 'matakuliah_kelas.kelas_id', '=', 'kelas.id')
            ->join('rps', 'matakuliah_kelas.rps_id', '=', 'rps.id')
            ->join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
            ->join('dosen', 'matakuliah_kelas.dosen_id', '=', 'dosen.id')
            ->join('semester', 'matakuliah_kelas.semester_id', '=', 'semester.id')
            ->leftJoin('nilaiakhir_mahasiswa', 'matakuliah_kelas.id', '=', 'nilaiakhir_mahasiswa.matakuliah_kelasid')
            ->select('matakuliah_kelas.id','semester.id as id_smt', 'semester.tahun_ajaran', 'semester.semester', 'kelas.nama_kelas as kelas','mata_kuliah.id as id_matkul', 'mata_kuliah.nama_matkul as nama_matkul', 'mata_kuliah.kode_matkul as kode_matkul','dosen.nama as nama_dosen', 'rps.tahun_rps')
            ->selectRaw('COUNT(nilaiakhir_mahasiswa.mahasiswa_id) as jumlah_mahasiswa');

            if ($request->has('tahun_ajaran')) {
                $tahunAjaranTerm = $request->input('tahun_ajaran');
                $query->where('semester.id', $tahunAjaranTerm);
                $reqTahunAjaran = $getSemesters->where('id', $tahunAjaranTerm)->first();
                $title = $reqTahunAjaran->tahun_ajaran ." ". $reqTahunAjaran->semester;
            }else{
                $title = 'Tahun Ajaran';
            }
        // Cek apakah ada parameter pencarian
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($query) use ($searchTerm) {
                $query->where('kelas.nama_kelas', 'like', '%' . $searchTerm . '%')
                    ->orWhere('dosen.nama', 'like', '%' . $searchTerm . '%')
                    ->orWhere('mata_kuliah.nama_matkul', 'like', '%' . $searchTerm . '%');
            });
        }

        $query->groupBy('matakuliah_kelas.id')
            ->orderBy('semester.tahun_ajaran', 'Desc')
            ->orderBy('semester.semester', 'asc')
            ->orderBy('mata_kuliah.nama_matkul', 'ASC')
            ->orderBy('rps.tahun_rps','ASC')
            ->orderBy('kelas.nama_kelas', 'ASC');

        $kelas_kuliah = $query->get();
        // dd($kelas_kuliah);

        // $startNumber = ($kelas_kuliah->currentPage() - 1) * $kelas_kuliah->perPage() + 1;

        $data = [];
        foreach ($kelas_kuliah as $item) {
            $tahun_ajaran = $item->tahun_ajaran;
            $semester = $item->semester;
            $nama_matkul = $item->nama_matkul;

            if (!isset($data[$tahun_ajaran])) {
                $data[$tahun_ajaran] = [];
            }
            if (!isset($data[$tahun_ajaran][$semester])) {
                $data[$tahun_ajaran][$semester] = [];
            }
            if (!isset($data[$tahun_ajaran][$semester][$nama_matkul])) {
                $data[$tahun_ajaran][$semester][$nama_matkul] = [
                    'id_smt' => $item->id_smt,
                    'id_matkul' => $item->id_matkul,
                    'kode_matkul' => $item->kode_matkul,
                    'info_kelas' => []
                ];
            }

            $data[$tahun_ajaran][$semester][$nama_matkul]['info_kelas'][] = [
                'id_kelas' => $item->id,
                'nama_kelas' => $item->kelas,
                'jumlah_mahasiswa' => $item->jumlah_mahasiswa,
                'nama_dosen' => $item->nama_dosen,
                // 'koordinator' => $item->koordinator,
                'tahun_rps' => $item->tahun_rps
            ];
        }

        // Flatten the data structure for easier use in the view
        $flatData = [];
        foreach ($data as $tahun_ajaran => $semesters) {
            foreach ($semesters as $semester => $mata_kuliah) {
                foreach ($mata_kuliah as $nama_matkul => $info) {
                    $flatData[] = [
                        'id_smt' => $info['id_smt'],
                        'id_matkul' => $info['id_matkul'],
                        'tahun_ajaran' => $tahun_ajaran,
                        'semester' => $semester,
                        'kode_matkul' => $info['kode_matkul'],
                        'nama_matkul' => $nama_matkul,
                        'info_kelas' => $info['info_kelas'],
                    ];
                }
            }
        }

        // Perform pagination on flatData
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 4;
        $currentItems = array_slice($flatData, ($currentPage - 1) * $perPage, $perPage);
        $paginatedData = new LengthAwarePaginator($currentItems, count($flatData), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => $request->query(),
        ]);

        $startNumber = ($paginatedData->currentPage() - 1) * $paginatedData->perPage() + 1;
        // dd($paginatedData);
        return view('pages-admin.perkuliahan.kelas_perkuliahan', [
            'data' => $paginatedData,
            'startNumber' => $startNumber,
            'getSemesters' => $getSemesters,
            'title' => $title,
        ])->with('success', 'Data CPMK Ditemukan');
        // return view('pages-admin.perkuliahan.kelas_perkuliahan', [
        //     'data' => $kelas_kuliah,
        //     'startNumber' => $startNumber,
        // ])->with('success', 'Data CPMK Ditemukan');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $kelas = Kelas::pluck('nama_kelas', 'id');
        // $mata_kuliah = MataKuliah::pluck('nama_matkul', 'id');
        $dosen = Dosen::pluck('nama', 'id');
        $idMatkul = $request->query('id_matkul');
        $rps = Rps::join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
        ->select('rps.id as id_rps', 'mata_kuliah.kode_matkul','mata_kuliah.nama_matkul', 'rps.tahun_rps')
        ->where('mata_kuliah.id', $idMatkul)->get();
        // $semester = Semester::all();
        $idSemester = $request->query('id_smt');
        $tahunAjaran = $request->query('tahun_ajaran');
        $semester = $request->query('semester');
        $namaMatkul = $request->query('nama_matkul');
        return view('pages-admin.perkuliahan.tambah_kelas', compact('rps', 'kelas', 'dosen','idSemester','idMatkul', 'tahunAjaran', 'semester', 'namaMatkul'));
    }

    public function createKelas(Request $request)
    {
        $kelas = Kelas::pluck('nama_kelas', 'id');
        $rps = Rps::join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
        ->select('rps.id','mata_kuliah.kode_matkul', 'mata_kuliah.nama_matkul', 'rps.tahun_rps')->get();
        $dosen = Dosen::pluck('nama', 'id');
        $semester = Semester::all();

        return view('pages-admin.perkuliahan.tambah_kelas_perkuliahan', compact('kelas', 'dosen', 'semester', 'rps'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'kelas' => 'required|exists:kelas,id',
            'rps' => 'required|exists:rps,id',
            'dosen' => 'required|exists:dosen,id',
            'semester' => 'required',
        ]);
        // dd($validate);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->first(),
            ], 422);
        }

        try {
            // $existingRecord = KelasKuliah::where('rps_id', $request->rps)
            //     ->where('dosen_id', $request->dosen)
            //     ->first();

            // $koordinatorValue = $existingRecord ? $existingRecord->koordinator : "0";

            KelasKuliah::create([
                'kelas_id' => $request->kelas,
                'rps_id' => $request->rps,
                'dosen_id' => $request->dosen,
                'semester_id' => $request->semester,
                // 'koordinator' => $koordinatorValue,
            ]);
            // dd($request->semester);
            // return redirect()->route('admin.kelaskuliah')->with('success', 'Data Berhasil Ditambahkan');
            return response()->json(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal ditambahkan: ' . $e->getMessage()], 500);
            // return redirect()->back()->withErrors(['errors' => 'Data Gagal Ditambahkan: ' . $e->getMessage()])->withInput();
        }
    }

    public function updateKoordinator(Request $request, int $id)
    {
        try {
            // dd($request->koordinator);
            $kelas_kuliah = KelasKuliah::where('id', $id)->first();

            $oldKoordinatorValue = $kelas_kuliah->koordinator;
            // dd($kelas_kuliah->koordinator);

            $kelas_kuliah->update([
                'koordinator' => $request->koordinator
            ]);

            KelasKuliah::where('rps_id', $kelas_kuliah->rps_id)
                ->where('semester_id', $kelas_kuliah->semester_id)
                ->update([
                    'koordinator' => '0'
                ]);

            // dd($query->toSql(), $query->getBindings());

            if ($oldKoordinatorValue != $request->koordinator) {
                $this->updateOtherData($kelas_kuliah->dosen_id, $kelas_kuliah->rps_id, $kelas_kuliah->semester_id, $request->koordinator);
            }

            return response()->json(['status' => 'success', 'message' => 'Data koordinator berhasil diupdate']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data koordinator gagal diupdate: ' . $e->getMessage()], 500);
        }
    }

    private function updateOtherData($dosenID, $rpsID, $tahunAjaran, $newKoordinatorValue)
    {
        try {
            KelasKuliah::where('dosen_id', $dosenID)
                ->where('rps_id', $rpsID)
                ->where('semester_id', $tahunAjaran)
                ->update([
                    'koordinator' => $newKoordinatorValue
                ]);

            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, $id)
    {
        $kelas_kuliah = KelasKuliah::join('kelas', 'matakuliah_kelas.kelas_id', '=', 'kelas.id')
            ->join('rps', 'matakuliah_kelas.rps_id', '=', 'rps.id')
            ->join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
            ->join('dosen', 'matakuliah_kelas.dosen_id', '=', 'dosen.id')
            ->join('semester', 'matakuliah_kelas.semester_id', '=', 'semester.id')
            ->select(
                'matakuliah_kelas.*',
                'semester.tahun_ajaran',
                'semester.semester',
                'kelas.nama_kelas as kelas',
                'mata_kuliah.kode_matkul as kode_matkul',
                'mata_kuliah.nama_matkul as nama_matkul',
                'dosen.nama as nama_dosen',
                'rps.tahun_rps'
            )
            ->where('matakuliah_kelas.id', $id)
            ->first();

        $jumlah_mahasiswa = NilaiAkhirMahasiswa::selectRaw('COUNT(nilaiakhir_mahasiswa.mahasiswa_id) as jumlah_mahasiswa')->where('nilaiakhir_mahasiswa.matakuliah_kelasid', $id)->first();
        // dd($jumlah_mahasiswa);

        $query = NilaiAkhirMahasiswa::join('mahasiswa', 'nilaiakhir_mahasiswa.mahasiswa_id', '=', 'mahasiswa.id')
            ->select('mahasiswa.*', 'nilaiakhir_mahasiswa.nilai_akhir as nilai_akhir')
            // ->distinct()
            ->where('nilaiakhir_mahasiswa.matakuliah_kelasid', $id);

        // Cek apakah ada parameter pencarian
        if ($request->has('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($query) use ($searchTerm) {
                $query->where('mahasiswa.nim', 'like', '%' . $searchTerm . '%')
                    ->orWhere('mahasiswa.nama', 'like', '%' . $searchTerm . '%');
            });
        }
        // $query->distinct();
        $mahasiswa = $query->orderBy('nama', 'asc')->distinct()->paginate(20);

        $startNumber = ($mahasiswa->currentPage() - 1) * $mahasiswa->perPage() + 1;

        $keterangan = [];
        $huruf = [];

        foreach ($mahasiswa as $mhs) {
            $nilai_akhir = $mhs->nilai_akhir;

            if ($nilai_akhir > 80 && $nilai_akhir <= 100) {
                $keterangan[$mhs->id] = "Sangat Baik";
            }elseif ($nilai_akhir > 73 && $nilai_akhir <= 80) {
                $keterangan[$mhs->id] = "Lebih dari Baik";
            }elseif ($nilai_akhir > 65 && $nilai_akhir <= 73) {
                $keterangan[$mhs->id] = "Baik";
            }elseif ($nilai_akhir > 60 && $nilai_akhir <= 65) {
                $keterangan[$mhs->id] = "Lebih dari Cukup";
            }elseif ($nilai_akhir > 50 && $nilai_akhir <= 60) {
                $keterangan[$mhs->id] = "Cukup";
            }elseif ($nilai_akhir > 39 && $nilai_akhir <= 50) {
                $keterangan[$mhs->id] = "Kurang";
            }else{
                $keterangan[$mhs->id] = "Gagal";
            }

            if ($nilai_akhir > 80 && $nilai_akhir <= 100) {
                $huruf[$mhs->id] = "A";
            } elseif ($nilai_akhir > 73 && $nilai_akhir <= 80) {
                $huruf[$mhs->id] = "B+";
            } elseif ($nilai_akhir > 65 && $nilai_akhir <= 73) {
                $huruf[$mhs->id] = "B";
            } elseif ($nilai_akhir > 60 && $nilai_akhir <= 65) {
                $huruf[$mhs->id] = "C+";
            } elseif ($nilai_akhir > 50 && $nilai_akhir <= 60) {
                $huruf[$mhs->id] = "C";
            } elseif ($nilai_akhir > 39 && $nilai_akhir <= 50) {
                $huruf[$mhs->id] = "D";
            } else {
                $huruf[$mhs->id] = "E";
            }
        }

        // dd($keterangan);

        return view('pages-admin.perkuliahan.detail_kelas_perkuliahan', [
            'success' => 'Data Ditemukan',
            'data' => $kelas_kuliah,
            'jumlah_mahasiswa' => $jumlah_mahasiswa,
            'huruf' => $huruf,
            'keterangan' => $keterangan,
            'mahasiswa' => $mahasiswa,
            'startNumber' => $startNumber,
        ]);
    }

    public function createMahasiswa($id)
    {
        $mahasiswa = Mahasiswa::pluck('nama', 'id');
        $kelas_kuliah = KelasKuliah::find($id);
        $matakuliah_kelas = KelasKuliah::join('kelas', 'matakuliah_kelas.kelas_id', '=', 'kelas.id')
            ->join('rps', 'matakuliah_kelas.rps_id', 'rps.id')
            ->join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
            ->select('matakuliah_kelas.*', 'kelas.nama_kelas as kelas', 'mata_kuliah.nama_matkul as nama_matkul')
            ->get();

        return view('pages-admin.perkuliahan.tambah_daftar_mahasiswa', compact('kelas_kuliah', 'mahasiswa', 'matakuliah_kelas'));
    }

    public function storeMahasiswa(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'mahasiswa' => 'required|exists:mahasiswa,id',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->first(),
            ], 422);
        }

        try {
            $rps_id = KelasKuliah::where('id', $id)->value('rps_id');
            // $get_rps = "SELECT `matakuliah_id` FROM `matakuliah_kelas` WHERE `id`= '$id'";
            // $result = DB::select($get_rps);

            if (empty($rps_id)) {
                return redirect()->back()->withErrors(['errors' => 'Invalid RPS ID'])->withInput();
            }

            // $id_rps = $result[0]->matakuliah_i                                                                                                                                                                     d;

            // Your existing SQL query
            $soal_sub_cpmk = Cpmk::join('sub_cpmk', 'cpmk.id', 'sub_cpmk.cpmk_id')
                ->join('soal_sub_cpmk', 'sub_cpmk.id', 'soal_sub_cpmk.subcpmk_id')
                ->where('cpmk.rps_id', $rps_id)->select('soal_sub_cpmk.id as soal_id')->get();

            // $sql_get = "SELECT `s`.`id` FROM `sub_cpmk` `s`
            // INNER JOIN `cpmk` `c` ON `s`.`cpmk_id` = `c`.`id`
            // INNER JOIN `mata_kuliah` `m` ON `c`.`matakuliah_id` = `m`.`id`
            // WHERE `m`.`id` = $id_matkul";

            // $results = DB::select($sql_get);
            foreach ($soal_sub_cpmk as $data) {
                // dd($data);
                // $soal_id = $data->soal_id;
                try {
                    NilaiMahasiswa::create([
                        'mahasiswa_id' => $request->mahasiswa,
                        'matakuliah_kelasid' => $id,
                        'soal_id' => $data->soal_id,
                    ]);
                } catch (\Exception $e) {
                    return response()->json(['status' => 'error', 'message' => 'Data gagal ditambahkan: ' . $e->getMessage()], 500);
                    // return redirect()->back()->withErrors(['errors' => 'Data Gagal Ditambahkan: ' . $e->getMessage()])->withInput();
                }
            }

            NilaiAkhirMahasiswa::create([
                'mahasiswa_id' => $request->mahasiswa,
                'matakuliah_kelasid' => $id,
            ]);

            return response()->json(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
            // return redirect()->route('admin.kelaskuliah.show', $id)->with('success', 'Data Berhasil Ditambahkan');
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal ditambahkan: ' . $e->getMessage()], 500);
            // return redirect()->back()->withErrors(['errors' => 'Data Gagal Ditambahkan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kelas_kuliah = KelasKuliah::join('kelas', 'matakuliah_kelas.kelas_id', '=', 'kelas.id')
            ->join('rps', 'matakuliah_kelas.rps_id', 'rps.id')
            ->join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
            ->join('dosen', 'matakuliah_kelas.dosen_id', '=', 'dosen.id')
            // ->join('semester', 'matakuliah_kelas.semester_id', '=', 'semester.id')
            ->select(
                'matakuliah_kelas.*',
                'kelas.nama_kelas as kelas',
                'mata_kuliah.nama_matkul as nama_matkul',
                'dosen.nama as nama_dosen'
            )
            ->where('matakuliah_kelas.id', $id)->first();

        $kelas = Kelas::pluck('nama_kelas', 'id');
        // $mata_kuliah = MataKuliah::pluck('nama_matkul', 'id');
        $rps = Rps::join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
        ->select('rps.id', 'mata_kuliah.nama_matkul', 'rps.tahun_rps')->get();
        $dosen = Dosen::pluck('nama', 'id');
        $semester = Semester::all();
        return view('pages-admin.perkuliahan.edit_kelas_perkuliahan', [
            'success' => 'Data Ditemukan',
            'data' => $kelas_kuliah,
            'kelas' => $kelas,
            'rps' => $rps,
            'dosen' => $dosen,
            'semester' => $semester
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validate = Validator::make($request->all(), [
            'kelas' => 'required|exists:kelas,id',
            'rps' => 'required|exists:rps,id',
            'dosen' => 'required|exists:dosen,id',
            'semester' => 'required',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->first(),
            ], 422);
        }

        try {
            // $kelas_kuliah = KelasKuliah::where('id', $id)->first();
            $kelas_kuliah = KelasKuliah::find($id);
            // $oldRpsValue = $kelas_kuliah->rps_id;
            // $oldKoordinatorValue = $kelas_kuliah->koordinator;

            // if ($oldRpsValue != $request->rps) {
            //     if ($oldKoordinatorValue === '1'){
            //         $kelas_kuliah->update([
            //             'koordinator' => '0',
            //         ]);
            //     }
            // }

            $kelas_kuliah->update([
                'kelas_id' => $request->kelas,
                'rps_id' => $request->rps,
                'dosen_id' => $request->dosen,
                'semester_id' => $request->semester,
            ]);

            // return redirect()->route('admin.kelaskuliah')->with([
            //     'success' => 'Data Berhasil Diupdate',
            //     'data' => $kelas_kuliah
            // ]);
            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate', 'data' => $kelas_kuliah]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal diupdate: ' . $e->getMessage()], 500);
            // dd($e->getMessage(), $e->getTrace()); // Tambahkan ini untuk melihat pesan kesalahan
            // return redirect()->route('admin.kelaskuliah.edit', $id)->with('error', 'Data Gagal Diupdate: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            NilaiMahasiswa::where('matakuliah_kelasid', $id)->delete();
            NilaiAkhirMahasiswa::where('matakuliah_kelasid', $id)->delete();
            KelasKuliah::where('id', $id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
            // return redirect()->route('admin.kelaskuliah')
            //     ->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal dihapus: ' . $e->getMessage()], 500);
            // return redirect()->route('admin.kelaskuliah')
            //     ->with('error', 'Data gagal dihapus: ' . $e->getMessage());
        }
    }

    public function destroyMahasiswa($id, $id_mahasiswa)
    {
        try {
            NilaiMahasiswa::where('mahasiswa_id', $id_mahasiswa)
                ->where('matakuliah_kelasid', $id)
                ->delete();

            NilaiAkhirMahasiswa::where('mahasiswa_id', $id_mahasiswa)
                ->where('matakuliah_kelasid', $id)
                ->delete();

            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
            // return redirect()->route('admin.kelaskuliah')
            //     ->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal dihapus: ' . $e->getMessage()], 500);
            // return redirect()->route('admin.kelaskuliah')
            //     ->with('error', 'Data gagal dihapus: ' . $e->getMessage());
        }
    }

    public function destroyMahasiswaMultiple(Request $request, $id)
    {
        $data = json_decode($request->getContent(), true);
        $ids = $data['ids'] ?? null; // Array ID mahasiswa

        if (is_array($ids) && !empty($ids)) {
            try {
                foreach ($ids as $id_mahasiswa) {
                    // Hapus data mahasiswa terkait
                    NilaiMahasiswa::where('mahasiswa_id', $id_mahasiswa)
                        ->where('matakuliah_kelasid', $id)
                        ->delete();

                    NilaiAkhirMahasiswa::where('mahasiswa_id', $id_mahasiswa)
                        ->where('matakuliah_kelasid', $id)
                        ->delete();
                }

                return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus.']);
            } catch (\Exception $e) {
                return response()->json(['status' => 'error', 'message' => 'Data gagal dihapus: ' . $e->getMessage()], 500);
            }
        } else {
            return response()->json(['status' => 'error', 'message' => 'Gagal menghapus data. Data mahasiswa tidak valid atau kosong.']);
        }
    }

    public function downloadExcel($id)
    {
        $kelas_kuliah = KelasKuliah::join('kelas', 'matakuliah_kelas.kelas_id', '=', 'kelas.id')
        ->join('rps', 'matakuliah_kelas.rps_id', '=', 'rps.id')
        ->join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
        ->join('dosen', 'matakuliah_kelas.dosen_id', '=', 'dosen.id')
        ->join('semester', 'matakuliah_kelas.semester_id', '=', 'semester.id')
        ->select(
            // 'matakuliah_kelas.*',
            'semester.tahun_ajaran',
            'semester.semester',
            'kelas.nama_kelas as kelas',
            'mata_kuliah.nama_matkul as nama_matkul',
        )
        ->where('matakuliah_kelas.id', $id)
        ->first();
        return Excel::download(new DaftarMahasiswaFormatExcel(), 'daftar-mahasiswa-kelas '. $kelas_kuliah->kelas.'-'.$kelas_kuliah->nama_matkul.'.xlsx');
    }

    public function importExcel(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $file = $request->file('file');

        Excel::import(new DaftarMahasiswaImportExcel($id), $file);

        return response()->json(['status' => 'success', 'message' => 'Data berhasil diimpor']);

        // return redirect()->back()->with('success', 'Data imported successfully.');
    }

    public function downloadExcelKelas()
    {
        return Excel::download(new KelasFormatExcel(), 'kelas-excel.xlsx');
    }

    public function importExcelKelas(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        $file = $request->file('file');

        Excel::import(new KelasImportExcel(), $file);

        return response()->json(['status' => 'success', 'message' => 'Data berhasil diimpor']);

        // return redirect()->back()->with('success', 'Data imported successfully.');
    }
}


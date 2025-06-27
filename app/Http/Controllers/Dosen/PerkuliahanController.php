<?php

namespace App\Http\Controllers\Dosen;

use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\Cpmk;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\Mahasiswa;
use App\Models\MataKuliah;
use App\Models\KelasKuliah;
use Illuminate\Http\Request;
use App\Models\NilaiMahasiswa;
use App\Models\NilaiAkhirMahasiswa;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;
use App\Exports\DaftarMahasiswaFormatExcel;
use App\Imports\DaftarMahasiswaImportExcel;
use Illuminate\Pagination\LengthAwarePaginator;

class PerkuliahanController extends Controller
{
    public function index(Request $request)
    {
        $getSemesters = Semester::select('id','tahun_ajaran', 'semester')->get();
        $query = KelasKuliah::join('kelas', 'matakuliah_kelas.kelas_id', '=', 'kelas.id')
            ->join('rps', 'matakuliah_kelas.rps_id', 'rps.id')
            ->join('mata_kuliah', 'rps.matakuliah_id', '=', 'mata_kuliah.id')
            ->join('dosen', 'matakuliah_kelas.dosen_id', '=', 'dosen.id')
            ->join('semester', 'matakuliah_kelas.semester_id', '=', 'semester.id')
            ->leftJoin('nilaiakhir_mahasiswa', 'matakuliah_kelas.id', '=', 'nilaiakhir_mahasiswa.matakuliah_kelasid')
            ->select('matakuliah_kelas.*', 'semester.tahun_ajaran', 'semester.semester', 'kelas.nama_kelas as kelas', 'mata_kuliah.nama_matkul as nama_matkul', 'dosen.nama as nama_dosen')
            ->selectRaw('COUNT(nilaiakhir_mahasiswa.mahasiswa_id) as jumlah_mahasiswa')
            ->where('dosen.id_auth', Auth::user()->id);

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
                    ->orWhere('mata_kuliah.nama_matkul', 'like', '%' . $searchTerm . '%');
            });
        }

        $query->groupBy('matakuliah_kelas.id')
            ->orderBy('semester.tahun_ajaran', 'Desc')
            ->orderBy('semester.semester', 'asc')
            ->orderBy('mata_kuliah.nama_matkul', 'ASC')
            ->orderBy('kelas.nama_kelas','ASC');

        $kelas_kuliah = $query->paginate(20);
        // dd($kelas_kuliah);
        $startNumber = ($kelas_kuliah->currentPage() - 1) * $kelas_kuliah->perPage() + 1;

        return view('pages-dosen.perkuliahan.kelas_perkuliahan', [
            'data' => $kelas_kuliah,
            'startNumber' => $startNumber,
            'getSemesters' => $getSemesters,
            'title' => $title,
        ])->with('success', 'Data Ditemukan');
    }

    public function show(Request $request, $id)
    {
        $kelas_kuliah = KelasKuliah::join('kelas', 'matakuliah_kelas.kelas_id', '=', 'kelas.id')
            ->join('rps', 'matakuliah_kelas.rps_id', 'rps.id')
            ->join('mata_kuliah', 'rps.matakuliah_id', '=', 'mata_kuliah.id')
            ->join('dosen', 'matakuliah_kelas.dosen_id', '=', 'dosen.id')
            ->join('semester', 'matakuliah_kelas.semester_id', '=', 'semester.id')
            ->select(
                'matakuliah_kelas.*',
                'semester.tahun_ajaran',
                'semester.semester',
                'kelas.nama_kelas as kelas',
                'mata_kuliah.nama_matkul as nama_matkul',
                'dosen.nama as nama_dosen',
                'dosen.status',
                'rps.tahun_rps'
            )
            ->where('matakuliah_kelas.id', $id)->first();

        $jumlah_mahasiswa = NilaiAkhirMahasiswa::selectRaw('COUNT(nilaiakhir_mahasiswa.mahasiswa_id) as jumlah_mahasiswa')->where('nilaiakhir_mahasiswa.matakuliah_kelasid', $id)->first();
        // dd($jumlah_mahasiswa);

        $query = NilaiAkhirMahasiswa::join('mahasiswa', 'nilaiakhir_mahasiswa.mahasiswa_id', '=', 'mahasiswa.id')
            ->select('mahasiswa.*','nilaiakhir_mahasiswa.id as id_nilai', 'nilaiakhir_mahasiswa.nilai_akhir as nilai_akhir')
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
        $mahasiswa = $query->orderBy('nim', 'asc')->distinct()->paginate(20);
        // dd($mahasiswa);
        $startNumber = ($mahasiswa->currentPage() - 1) * $mahasiswa->perPage() + 1;

        $keterangan = [];
        $huruf = [];

        foreach ($mahasiswa as $mhs) {
            $nilai_akhir = $mhs->nilai_akhir;

            if ($nilai_akhir >= 61) {
                $keterangan[$mhs->id] = "Lulus";
            } else {
                $keterangan[$mhs->id] = "Tidak Lulus";
            }

            if ($nilai_akhir >= 85) {
                $huruf[$mhs->id] = "A";
            }elseif ($nilai_akhir >= 76) {
                $huruf[$mhs->id] = "B+";
            }elseif ($nilai_akhir >= 71) {
                $huruf[$mhs->id] = "B";
            }elseif ($nilai_akhir >= 66) {
                $huruf[$mhs->id] = "C+";
            }elseif ($nilai_akhir >= 61) {
                $huruf[$mhs->id] = "C";
            }elseif ($nilai_akhir >= 51) {
                $huruf[$mhs->id] = "D";
            }else{
                $huruf[$mhs->id] = "E";
            }
        }

        // dd($keterangan);

        return view('pages-dosen.perkuliahan.detail_kelas_perkuliahan', [
            'success' => 'Data Ditemukan',
            'data' => $kelas_kuliah,
            'jumlah_mahasiswa' => $jumlah_mahasiswa,
            'keterangan' => $keterangan,
            'huruf' => $huruf,
            'mahasiswa' => $mahasiswa,
            'startNumber' => $startNumber,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $kelas = Kelas::pluck('nama_kelas', 'id');
        $mata_kuliah = MataKuliah::pluck('nama_matkul', 'id');
        $dosen = Dosen::pluck('nama', 'id');
        $semester = Semester::all();
        return view('pages-dosen.perkuliahan.tambah_kelas_perkuliahan', compact('kelas', 'mata_kuliah', 'dosen', 'semester'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'kelas' => 'required|exists:kelas,id',
            'mata_kuliah' => 'required|exists:mata_kuliah,id',
            'dosen' => 'required|exists:dosen,id',
            'semester' => 'required',
        ]);
        // dd($validate);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        try {
            $existingRecord = KelasKuliah::where('matakuliah_id', $request->mata_kuliah)
                ->where('dosen_id', $request->dosen)
                ->first();

            $koordinatorValue = $existingRecord ? $existingRecord->koordinator : "0";

            KelasKuliah::create([
                'kelas_id' => $request->kelas,
                'matakuliah_id' => $request->mata_kuliah,
                'dosen_id' => $request->dosen,
                'semester_id' => $request->semester,
                'koordinator' => $koordinatorValue,
            ]);
            // dd($request->semester);
            return redirect()->route('dosen.kelaskuliah')->with('success', 'Data Berhasil Ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['errors' => 'Data Gagal Ditambahkan: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $kelas_kuliah = KelasKuliah::join('kelas', 'matakuliah_kelas.kelas_id', '=', 'kelas.id')
            ->join('mata_kuliah', 'matakuliah_kelas.matakuliah_id', '=', 'mata_kuliah.id')
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
        $mata_kuliah = MataKuliah::pluck('nama_matkul', 'id');
        $dosen = Dosen::pluck('nama', 'id');
        $semester = Semester::all();
        return view('pages-dosen.perkuliahan.edit_kelas_perkuliahan', [
            'success' => 'Data Ditemukan',
            'data' => $kelas_kuliah,
            'kelas' => $kelas,
            'mata_kuliah' => $mata_kuliah,
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
            'mata_kuliah' => 'required|exists:mata_kuliah,id',
            'dosen' => 'required|exists:dosen,id',
            'semester' => 'required',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        try {
            $kelas_kuliah = KelasKuliah::find($id);
            $kelas_kuliah->update([
                'kelas_id' => $request->kelas,
                'matakuliah_id' => $request->mata_kuliah,
                'dosen_id' => $request->dosen,
                'semester_id' => $request->semester,
            ]);

            return redirect()->route('dosen.kelaskuliah')->with([
                'success' => 'Data Berhasil Diupdate',
                'data' => $kelas_kuliah
            ]);
        } catch (\Exception $e) {
            // dd($e->getMessage(), $e->getTrace()); // Tambahkan ini untuk melihat pesan kesalahan
            return redirect()->route('dosen.kelaskuliah.edit', $id)->with('error', 'Data Gagal Diupdate: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            KelasKuliah::where('id', $id)->delete();
            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
            // return redirect()->route('dosen.kelaskuliah')
            //     ->with('success', 'Data berhasil dihapus');
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal dihapus: ' . $e->getMessage()], 500);
            // return redirect()->route('dosen.kelaskuliah')
            //     ->with('error', 'Data gagal dihapus: ' . $e->getMessage());
        }
    }

    public function createMahasiswa($id)
    {
        $mahasiswa = Mahasiswa::pluck('nama', 'id');
        $kelas_kuliah = KelasKuliah::find($id);
        $matakuliah_kelas = KelasKuliah::join('kelas', 'matakuliah_kelas.kelas_id', '=', 'kelas.id')
            ->join('rps', 'matakuliah_kelas.rps_id', 'rps.id')
            ->join('mata_kuliah', 'rps.matakuliah_id', '=', 'mata_kuliah.id')
            ->select('matakuliah_kelas.*', 'kelas.nama_kelas as kelas', 'mata_kuliah.nama_matkul as nama_matkul')
            ->get();

        return view('pages-dosen.perkuliahan.tambah_daftar_mahasiswa', compact('kelas_kuliah', 'mahasiswa', 'matakuliah_kelas'));
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
                return response()->json(['status' => 'error', 'message' => 'Invalid RPS ID']);
                // return redirect()->back()->withErrors(['errors' => 'Invalid matakuliah_kelas ID'])->withInput();
            }

            // $id_rps = $result[0]->matakuliah_i                                                                                                                                                                     d;

            // Your existing SQL query
            $soal_sub_cpmk = Cpmk::join('sub_cpmk', 'cpmk.id', 'sub_cpmk.cpmk_id')
                ->join('soal_sub_cpmk', 'sub_cpmk.id', 'soal_sub_cpmk.subcpmk_id')
                ->where('cpmk.rps_id', $rps_id)->select('soal_sub_cpmk.id as soal_id')->get();

            // $sql_get = "SELECT `s`.`id` FROM `sub_cpmk` `s`
            // INNER JOIN `cpmk` `c` ON `s`.`cpmk_id` = `c`.`id`
            // INNER JOIN `mata_kuliah` `m` ON `c`.`matakuliah_id` = `m`.`id`
            // WHERE `m`.`id` = $id_rps";

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

            // return redirect()->route('dosen.kelaskuliah.show', $id)->with('success', 'Data Berhasil Ditambahkan');
            return response()->json(['status' => 'success', 'message' => 'Data berhasil ditambahkan']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal ditambahkan: ' . $e->getMessage()], 500);
            // return redirect()->back()->withErrors(['errors' => 'Data Gagal Ditambahkan: ' . $e->getMessage()])->withInput();
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

            // return redirect()->route('dosen.kelaskuliah')
            //     ->with('success', 'Data berhasil dihapus');
            return response()->json(['status' => 'success', 'message' => 'Data berhasil dihapus']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal dihapus: ' . $e->getMessage()], 500);
            // return redirect()->route('dosen.kelaskuliah')
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

    public function nilaiMahasiswa(Request $request, $id) {
        $kelasMatkul = KelasKuliah::join('rps', 'matakuliah_kelas.rps_id', 'rps.id')
        ->join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
        ->join('kelas', 'matakuliah_kelas.kelas_id', 'kelas.id')
        ->join('dosen', 'matakuliah_kelas.dosen_id', 'dosen.id')
        ->select('mata_kuliah.nama_matkul', 'kelas.nama_kelas', 'matakuliah_kelas.id as id_kelas', 'dosen.status')
        ->where('matakuliah_kelas.id', $id)
        ->first();

        $query = NilaiMahasiswa::join('mahasiswa', 'nilai_mahasiswa.mahasiswa_id', 'mahasiswa.id')
            ->join('matakuliah_kelas', 'nilai_mahasiswa.matakuliah_kelasid', 'matakuliah_kelas.id')
            ->join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->join('soal', 'soal_sub_cpmk.soal_id', 'soal.id')
            ->select('mahasiswa.nim', 'mahasiswa.nama', 'soal_sub_cpmk.id', 'soal_sub_cpmk.waktu_pelaksanaan', 'sub_cpmk.kode_subcpmk', 'soal_sub_cpmk.bobot_soal', 'soal.bentuk_soal','nilai_mahasiswa.id as id_nilai','nilai_mahasiswa.mahasiswa_id as id_mhs', 'nilai_mahasiswa.matakuliah_kelasid as id_kelas', 'nilai_mahasiswa.nilai')
            ->where('matakuliah_kelas.id', $id);

            // Cek apakah ada parameter pencarian
            if ($request->has('search')) {
                $searchTerm = $request->input('search');
                $query->where(function ($query) use ($searchTerm) {
                    $query->where('mahasiswa.nim', 'like', '%' . $searchTerm . '%')
                        ->orWhere('mahasiswa.nama', 'like', '%' . $searchTerm . '%');
                });
            }

            $nilai_mahasiswa = $query->orderby('soal_sub_cpmk.id', 'ASC')
            ->orderBy('mahasiswa.nama', 'ASC')
            // ->distinct('soal_sub_cpmk.waktu_pelaksanaan')
            ->get();

        $mahasiswa_data = [];
        $info_soal = [];
        $nomor = 1;
        // $nilaiMhs = [];

        foreach ($nilai_mahasiswa as $nilai) {
            $soal_id = $nilai->id;
            $mahasiswa_id = $nilai->nim;
            // $nilai_id = $nilai->id_nilai;

            if (!isset($info_soal[$soal_id])) {
                $info_soal[$soal_id] = [
                    'waktu_pelaksanaan' => $nilai->waktu_pelaksanaan,
                    'kode_subcpmk' => $nilai->kode_subcpmk,
                    'bobot_soal' => $nilai->bobot_soal,
                    'bentuk_soal' => $nilai->bentuk_soal,
                ];
            }

            if (!isset($mahasiswa_data[$mahasiswa_id])) {
                $mahasiswa_data[$mahasiswa_id] = [
                    'kelas_id' => $nilai->id_kelas,
                    'id_mhs' => $nilai->id_mhs,
                    'nim' => $nilai->nim,
                    'nama' => $nilai->nama,
                    'id_nilai' => [],
                    'nilai' => [],
                    'nomor' => $nomor
                ];
                $nomor++;
            }

            $mahasiswa_data[$mahasiswa_id]['id_nilai'][] = $nilai->id_nilai;
            $mahasiswa_data[$mahasiswa_id]['nilai'][] = $nilai->nilai;
        }

        // Sekarang $mahasiswa_data berisi data nilai untuk setiap mahasiswa dengan struktur yang diinginkan
        // dd(array_values($mahasiswa_data), array_values($info_soal));

        // Convert array to collection
        $collection = collect($mahasiswa_data);

        // Determine the current page from the request (default to 1)
        $currentPage = $request->get('page', 1);

        // Set the number of items per page
        $perPage = 25;

        // Slice the collection to get the items for the current page
        $currentItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();

        // Create the paginator
        $paginator = new LengthAwarePaginator($currentItems, $collection->count(), $perPage, $currentPage, [
            'path' => $request->url(),
            'query' => $request->query(),
        ]);

        return view('pages-dosen.perkuliahan.nilai_mahasiswa', ['data' => $kelasMatkul, 'mahasiswa_data' => $paginator, 'info_soal' => $info_soal, 'id_kelas' => $id]);
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
    public function updateEvaluasi(Request $request, $id)
    {
        $validate = Validator::make($request->all(), [
            'evaluasi' => 'string',
            'rencana_perbaikan' => 'string',
        ]);

        if($validate->fails()){
            return response()->json([
                'status' => 'error',
                'message' => $validate->errors()->first(),
            ], 422);
        }

        try {
            $evaluasi = KelasKuliah::find($id);
            $evaluasi->update([
                'evaluasi' => $request->evaluasi,
                'rencana_perbaikan' => $request->rencana_perbaikan,
            ]);

            // return redirect()->route('admin.cpl')->with([
            //     'success' => 'Data Berhasil Diupdate',
            //     'data' => $cpl
            // ]);
            return response()->json(['status' => 'success', 'message' => 'Data berhasil diupdate', 'data' => $evaluasi]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => 'Data gagal diupdate: ' . $e->getMessage()], 500);
            // dd($e->getMessage(), $e->getTrace()); // Tambahkan ini untuk melihat pesan kesalahan
            // return redirect()->route('admin.cpl.edit', $id)->with('error', 'Data Gagal Diupdate: ' . $e->getMessage())->withInput();
        }
    }

    public function generatePdf($id)
    {
        // Ambil data mata kuliah dari database
        $kelas_matkul = KelasKuliah::join('rps', 'matakuliah_kelas.rps_id', 'rps.id')
        ->join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
        ->join('kelas', 'matakuliah_kelas.kelas_id', 'kelas.id')
        ->join('dosen', 'matakuliah_kelas.dosen_id', 'dosen.id')
        ->join('semester', 'matakuliah_kelas.semester_id', 'semester.id')
        ->select(
            'mata_kuliah.nama_matkul', 'kelas.nama_kelas', 'dosen.nama as nama_dosen',
            'matakuliah_kelas.id as id_kelas', 'semester.tahun_ajaran', 'semester.semester', 'matakuliah_kelas.evaluasi', 'matakuliah_kelas.rencana_perbaikan')
        ->where('matakuliah_kelas.id', $id)
        ->first();

        $nilai_mahasiswa = NilaiMahasiswa::join('mahasiswa', 'nilai_mahasiswa.mahasiswa_id', 'mahasiswa.id')
            ->join('matakuliah_kelas', 'nilai_mahasiswa.matakuliah_kelasid', 'matakuliah_kelas.id')
            ->join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->join('soal', 'soal_sub_cpmk.soal_id', 'soal.id')
            ->select('mahasiswa.nim', 'mahasiswa.nama', 'soal_sub_cpmk.id', 'soal_sub_cpmk.waktu_pelaksanaan', 'sub_cpmk.kode_subcpmk', 'soal_sub_cpmk.bobot_soal', 'soal.bentuk_soal','nilai_mahasiswa.id as id_nilai','nilai_mahasiswa.mahasiswa_id as id_mhs', 'nilai_mahasiswa.matakuliah_kelasid as id_kelas', 'nilai_mahasiswa.nilai')
            ->where('matakuliah_kelas.id', $id)
            ->orderby('soal_sub_cpmk.id', 'ASC')
            ->orderBy('nim', 'asc')
            // ->distinct('soal_sub_cpmk.waktu_pelaksanaan')
            ->get();

        $nilai_akhir = NilaiAkhirMahasiswa::join('mahasiswa', 'nilaiakhir_mahasiswa.mahasiswa_id', '=', 'mahasiswa.id')
            ->select('mahasiswa.*', 'nilaiakhir_mahasiswa.nilai_akhir as nilai_akhir')
            // ->distinct()
            ->where('nilaiakhir_mahasiswa.matakuliah_kelasid', $id)
            ->orderBy('nim', 'asc')->distinct()->get();

        $rps = KelasKuliah::where('id', $id)->select('rps_id')->first();

        $cpl = Cpmk::join('cpl', 'cpmk.cpl_id', 'cpl.id')
        // ->join('sub_cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
        // ->join('soal_sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
        // ->join('soal', 'soal_sub_cpmk.soal_id', 'soal.id')
        ->where('rps_id', $rps->rps_id)
        ->select('cpl.*')
        ->orderBy('cpl.kode_cpl', 'asc')
        ->get();

        $cpmk = Cpmk::where('rps_id', $rps->rps_id)
        ->select('cpmk.*')
        ->orderBy('cpmk.kode_cpmk', 'asc')
        ->get();

        $subcpmk = Cpmk::join('sub_cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
        ->where('rps_id', $rps->rps_id)
        ->select('sub_cpmk.*')
        ->orderBy('sub_cpmk.kode_subcpmk', 'asc')
        ->get();

        $tugas = Cpmk::join('cpl', 'cpmk.cpl_id', 'cpl.id')
        ->join('sub_cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
        ->join('soal_sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
        ->join('soal', 'soal_sub_cpmk.soal_id', 'soal.id')
        ->where('rps_id', $rps->rps_id)
        ->select('soal_sub_cpmk.*', 'sub_cpmk.kode_subcpmk', 'soal.bentuk_soal', 'cpmk.kode_cpmk', 'cpl.kode_cpl')
        ->orderBy('sub_cpmk.id', 'asc')
        ->get();

        $totalBobotKeseluruhan = 0; // Initialize a variable to store the overall total weight

        foreach ($tugas as $tugasItem) {
            $totalBobot = $tugasItem->bobot_soal; // Access the calculated total weight for the current RPS
            $totalBobotKeseluruhan += $totalBobot; // Add the current RPS weight to the overall total
        }
        // dd($rps->id);
        $nilaiRataRata = NilaiMahasiswa::join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('soal', 'soal_sub_cpmk.soal_id', 'soal.id')
            ->selectRaw('soal.bentuk_soal, ROUND(AVG(nilai_mahasiswa.nilai), 2) as nilai_rata_rata')
            ->where('matakuliah_kelasid', $id)
            ->groupBy('soal.bentuk_soal')
            ->get();

            $labels = $nilaiRataRata->pluck('bentuk_soal')->toArray();
            $values = $nilaiRataRata->pluck('nilai_rata_rata')->toArray();

            $query = NilaiMahasiswa::join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->join('soal', 'soal_sub_cpmk.soal_id', 'soal.id')
            ->selectRaw('ROUND(SUM(nilai_mahasiswa.nilai * soal_sub_cpmk.bobot_soal) / SUM(soal_sub_cpmk.bobot_soal), 1) as nilai_rata_rata, soal_sub_cpmk.bobot_soal AS bobot_soal, sub_cpmk.kode_subcpmk')
            // ->selectRaw('sub_cpmk.kode_subcpmk, ROUND(AVG(nilai_mahasiswa.nilai), 2) as nilai_rata_rata')
            ->where('matakuliah_kelasid', $id)
            ->orderBy('sub_cpmk.kode_subcpmk', 'asc')
            ->groupBy('sub_cpmk.kode_subcpmk');

        $rataRataSubcpmk = $query->get();

            $labelSubcpmk = $rataRataSubcpmk->pluck('kode_subcpmk')->toArray();
            $valueSubcpmk = $rataRataSubcpmk->pluck('nilai_rata_rata')->toArray();

            $query = NilaiMahasiswa::join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->join('soal', 'soal_sub_cpmk.soal_id', 'soal.id')
            ->join('cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
            ->selectRaw('ROUND(SUM(nilai_mahasiswa.nilai * soal_sub_cpmk.bobot_soal) / SUM(soal_sub_cpmk.bobot_soal), 1) as nilai_rata_rata, soal_sub_cpmk.bobot_soal AS bobot_soal, cpmk.kode_cpmk')
            // ->selectRaw('sub_cpmk.kode_subcpmk, ROUND(AVG(nilai_mahasiswa.nilai), 2) as nilai_rata_rata')
            ->where('matakuliah_kelasid', $id)
            ->orderBy('cpmk.kode_cpmk', 'asc')
            ->groupBy('cpmk.kode_cpmk');

            // $sql = $query->toSql();
            // dd($sql);
            $rataRataCpmk = $query->get();

            $labelCpmk = $rataRataCpmk->pluck('kode_cpmk')->toArray();
            $valueCpmk = $rataRataCpmk->pluck('nilai_rata_rata')->toArray();

            $query = NilaiMahasiswa::join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->join('soal', 'soal_sub_cpmk.soal_id', 'soal.id')
            ->join('cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
            ->join('cpl', 'cpmk.cpl_id', 'cpl.id')
            ->selectRaw('ROUND(SUM(nilai_mahasiswa.nilai * soal_sub_cpmk.bobot_soal) / SUM(soal_sub_cpmk.bobot_soal), 1) as nilai_rata_rata, soal_sub_cpmk.bobot_soal AS bobot_soal, cpl.kode_cpl')
            // ->selectRaw('sub_cpmk.kode_subcpmk, ROUND(AVG(nilai_mahasiswa.nilai), 2) as nilai_rata_rata')
            ->where('matakuliah_kelasid', $id)
            ->orderBy('cpl.kode_cpl', 'asc')
            ->groupBy('cpl.kode_cpl');

        // $sql = $query->toSql();
        // dd($sql);
        $rataRataCpl = $query->get();

            $labelCpl = $rataRataCpl->pluck('kode_cpl')->toArray();
            $valueCpl = $rataRataCpl->pluck('nilai_rata_rata')->toArray();

            $charts = [];

            function createChartConfig($labels, $values, $label) {
                return [
                    'type' => 'radar',
                    'data' => [
                        'labels' => $labels,
                        'datasets' => [
                            [
                                'label' => $label,
                                'data' => $values,
                                'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
                                'borderColor' => 'rgba(255, 99, 132, 1)',
                                'borderWidth' => 1
                            ]
                        ]
                    ],
                    'options' => [
                        'scale' => [
                            'ticks' => [
                                'beginAtZero' => true,
                                'min' => 0,
                                'max' => 100
                            ]
                        ]
                    ]
                ];
            }

            $charts[] = createChartConfig($labels, $values, 'Nilai rata-rata tugas');
        $charts[] = createChartConfig($labelSubcpmk, $valueSubcpmk, 'Nilai rata-rata Sub CPMK');
        $charts[] = createChartConfig($labelCpmk, $valueCpmk, 'Nilai rata-rata CPMK');
        $charts[] = createChartConfig($labelCpl, $valueCpl, 'Nilai rata-rata CPL');

        $chartUrls = [];

        foreach ($charts as $chartConfig) {
            $url = 'https://quickchart.io/chart/create';
            $payload = json_encode(['chart' => $chartConfig]);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

            $response = curl_exec($ch);
            curl_close($ch);

            $responseData = json_decode($response, true);
            $chartUrls[] = $responseData['url'];
        }
        // Konfigurasi chart
        // $chartConfig = [
        //     'type' => 'radar',
        //     'data' => [
        //         'labels' => $labels,
        //         'datasets' => [
        //             [
        //                 'label' => 'Nilai rata-rata tugas',
        //                 'data' => $values,
        //                 'backgroundColor' => 'rgba(255, 99, 132, 0.2)',
        //                 'borderColor' => 'rgba(255, 99, 132, 1)',
        //                 'borderWidth' => 1
        //             ]
        //         ]
        //     ],
        //     'options' => [
        //         'scale' => [
        //             'ticks' => [
        //                 'beginAtZero' => true,
        //                 'min' => 0,
        //                 'max' => 100
        //             ]
        //         ]
        //     ]
        // ];

        // // Mengirim permintaan ke QuickChart API
        // $url = 'https://quickchart.io/chart/create';
        // $payload = json_encode(['chart' => $chartConfig]);

        // $ch = curl_init($url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        // curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        // curl_setopt($ch, CURLOPT_POST, true);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);

        // $response = curl_exec($ch);
        // curl_close($ch);

        // // Mendapatkan URL gambar dari respon
        // $responseData = json_decode($response, true);
        // $chartImageUrl = $responseData['url'];

            $mahasiswa_data = [];
            $info_soal = [];
            $nomor = 1;
            // $nilaiMhs = [];

            foreach ($nilai_mahasiswa as $nilai) {
                $soal_id = $nilai->id;
                $mahasiswa_id = $nilai->nim;
                // $nilai_id = $nilai->id_nilai;

                if (!isset($info_soal[$soal_id])) {
                    $info_soal[$soal_id] = [
                        'waktu_pelaksanaan' => $nilai->waktu_pelaksanaan,
                        'kode_subcpmk' => $nilai->kode_subcpmk,
                        'bobot_soal' => $nilai->bobot_soal,
                        'bentuk_soal' => $nilai->bentuk_soal,
                    ];
                }

                if (!isset($mahasiswa_data[$mahasiswa_id])) {
                    $mahasiswa_data[$mahasiswa_id] = [
                        'kelas_id' => $nilai->id_kelas,
                        'id_mhs' => $nilai->id_mhs,
                        'nim' => $nilai->nim,
                        'nama' => $nilai->nama,
                        'id_nilai' => [],
                        'nilai' => [],
                        'nomor' => $nomor
                    ];
                    $nomor++;
                }

                $mahasiswa_data[$mahasiswa_id]['id_nilai'][] = $nilai->id_nilai;
                $mahasiswa_data[$mahasiswa_id]['nilai'][] = $nilai->nilai;
            }

            foreach ($nilai_akhir as $akhir) {
                $mahasiswa_id = $akhir->nim;
                if (isset($mahasiswa_data[$mahasiswa_id])) {
                    $mahasiswa_data[$mahasiswa_id]['nilai_akhir'] = $akhir->nilai_akhir;
                    $mahasiswa_data[$mahasiswa_id]['nilai_huruf'] = $this->convertNilaiToHuruf($akhir->nilai_akhir);
                    $mahasiswa_data[$mahasiswa_id]['keterangan'] = $this->getKeterangan($akhir->nilai_akhir);
                }
            }

        $jumlah_mahasiswa = NilaiAkhirMahasiswa::selectRaw('COUNT(nilaiakhir_mahasiswa.mahasiswa_id) as jumlah_mahasiswa')->where('nilaiakhir_mahasiswa.matakuliah_kelasid', $id)->first();
        // Mulai membuat laporan PDF
        set_time_limit(300);
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('pages-dosen.perkuliahan.portfolio_perkuliahan_pdf', [
            'kelas' => $kelas_matkul, 'mahasiswa_data' => $mahasiswa_data, 'info_soal' => $info_soal,
            'jml_mhs' => $jumlah_mahasiswa, 'cpl' => $cpl, 'cpmk' => $cpmk, 'subcpmk' => $subcpmk, 'tugas' => $tugas, 'total_bobot'=> $totalBobotKeseluruhan,
            'chartUrls' => $chartUrls]));

        // Atur opsi PDF
        $options = new Options();
        $options->set('isRemoteEnabled', true);
        // define("DOMPDF_ENABLE_REMOTE", false);

        $dompdf->setPaper('A4', 'landscape');

        // Render PDF
        $dompdf->setOptions($options);
        $dompdf->render();

        // Menghasilkan nama file unik untuk laporan
        $filename = 'Portfolio Perkuliahan_' . $kelas_matkul->nama_matkul . '_Kelas ' . $kelas_matkul->nama_kelas . '.pdf';

        // Mengirimkan laporan PDF sebagai respons
        return $dompdf->stream($filename);
    }

    private function convertNilaiToHuruf($nilai)
    {
        if ($nilai >= 85) {
                return "A";
            }elseif ($nilai >= 76) {
                return "B+";
            }elseif ($nilai >= 71) {
                return "B";
            }elseif ($nilai >= 66) {
                return "C+";
            }elseif ($nilai >= 61) {
                return "C";
            }elseif ($nilai >= 51) {
                return "D";
            }else{
                return "E";
            }
    }

    private function getKeterangan($nilai)
    {
        if ($nilai >= 61) {
            return "Lulus";
        } else {
            return "Tidak Lulus";
        }
    }
}

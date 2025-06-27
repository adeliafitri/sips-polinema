<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KelasKuliah;
use App\Models\NilaiAkhirMahasiswa;
use App\Models\NilaiMahasiswa;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class NilaiController extends Controller
{
    public function show(Request $request, $id, $id_mahasiswa)
    {
        $nilai_mahasiswa = NilaiAkhirMahasiswa::join('mahasiswa', 'nilaiakhir_mahasiswa.mahasiswa_id', '=', 'mahasiswa.id')
            ->join('matakuliah_kelas', 'nilaiakhir_mahasiswa.matakuliah_kelasid', '=', 'matakuliah_kelas.id')
            ->join('rps', 'matakuliah_kelas.rps_id', 'rps.id')
            ->join('mata_kuliah', 'rps.matakuliah_id', '=', 'mata_kuliah.id')
            ->select('mahasiswa.nama as nama', 'mahasiswa.nim as nim', 'mata_kuliah.nama_matkul as nama_matkul', 'nilaiakhir_mahasiswa.*')
            // ->distinct()
            ->where('nilaiakhir_mahasiswa.matakuliah_kelasid', $id)
            ->where('nilaiakhir_mahasiswa.mahasiswa_id', $id_mahasiswa)
            ->first();
        // dd($nilai_mahasiswa);

        $query = NilaiMahasiswa::join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', '=', 'soal_sub_cpmk.id')
            ->select('soal_sub_cpmk.*', 'nilai_mahasiswa.nilai as nilai')
            // ->distinct()
            ->where('nilai_mahasiswa.matakuliah_kelasid', $id)
            ->where('nilai_mahasiswa.mahasiswa_id', $id_mahasiswa);

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
        return view('pages-admin.perkuliahan.detail_nilai_mahasiswa', [
            'data' => $nilai_mahasiswa,
            'nilai_subcpmk' => $nilai_subcpmk,
            'startNumber' => $startNumber,
            'sub_cpmk' => $query_sub,
            'subNumber' => $subNumber,
            // 'cpmk' => $result,
            // 'cpmkNumber' => $cpmkNumber
        ]);
    }

    public function edit($id, $id_mahasiswa, $id_subcpmk)
    {
        $nilai_subcpmk = NilaiMahasiswa::join('sub_cpmk', 'nilai_mahasiswa.subcpmk_id', '=', 'sub_cpmk.id')
            ->select('nilai_mahasiswa.*', 'sub_cpmk.kode_subcpmk as kode_subcpmk')
            ->where('nilai_mahasiswa.matakuliah_kelasid', $id)
            ->where('nilai_mahasiswa.mahasiswa_id', $id_mahasiswa)
            ->where('nilai_mahasiswa.subcpmk_id', $id_subcpmk)
            ->first();

        return view('pages-admin.perkuliahan.edit_nilai_mahasiswa', compact('nilai_subcpmk'));
    }

    public function update(Request $request, $id, $id_mahasiswa, $id_subcpmk)
    {
        $validate = Validator::make($request->all(), [
            'nilai' => 'required',
        ]);

        if ($validate->fails()) {
            return redirect()->back()->withErrors($validate)->withInput();
        }

        try {
            $nilai_subcpmk = NilaiMahasiswa::where('nilai_mahasiswa.matakuliah_kelasid', $id)
                ->where('nilai_mahasiswa.mahasiswa_id', $id_mahasiswa)
                ->where('nilai_mahasiswa.subcpmk_id', $id_subcpmk)
                ->first();
            $nilai_subcpmk->update([
                'nilai' => $request->nilai,
            ]);

            return redirect()->route('admin.kelaskuliah.nilaimahasiswa', ['id' => $id, 'id_mahasiswa' => $id_mahasiswa])->with([
                'success' => 'Data Nilai Berhasil Diupdate',
                'data' => $nilai_subcpmk
            ]);
        } catch (\Exception $e) {
            // dd($e->getMessage(), $e->getTrace()); // Tambahkan ini untuk melihat pesan kesalahan
            return redirect()->route('admin.kelaskuliah.nilaimahasiswa.edit', ['id' => $id, 'id_mahasiswa' => $id_mahasiswa, 'id_subcpmk' => $id_subcpmk])->with('error', 'Data Gagal Diupdate: ' . $e->getMessage())->withInput();
        }
    }

    public function nilaiCPL(Request $request)
    {
        $mahasiswa_id = $request->mahasiswa_id;
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
            ->where('nilai_mahasiswa.mahasiswa_id', $mahasiswa_id)
            ->where('nilai_mahasiswa.matakuliah_kelasid', $matakuliah_kelasid)
            ->get();


        $startNumber = [];

        if ($request->ajax()) {
            return view('pages-admin.perkuliahan.partials.nilai_cpl', [
                'data' => $data,
                'startNumber' => $startNumber,
            ])->with('success', 'Data Mata Kuliah Ditemukan');
        }
        return $data;
    }

    public function nilaiSubCpmk(Request $request)
    {
        // $mahasiswa_id = 1;
        // $matakuliah_kelasid = 1;

        $mahasiswa_id = $request->mahasiswa_id;
        $matakuliah_kelasid = $request->matakuliah_kelasid;
        $data = NilaiMahasiswa::join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('soal', 'soal.id', 'soal_sub_cpmk.soal_id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->join('cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
            ->join('cpl', 'cpmk.cpl_id', 'cpl.id')
            ->groupBy('soal_sub_cpmk.subcpmk_id')
            ->selectRaw('SUM(nilai_mahasiswa.nilai * soal_sub_cpmk.bobot_soal) / SUM(soal_sub_cpmk.bobot_soal) as total_nilai, soal_sub_cpmk.bobot_soal AS bobot_soal, sub_cpmk.kode_subcpmk, cpmk.kode_cpmk as kode_cpmk, cpl.kode_cpl as kode_cpl')
            ->orderBy('cpmk.id')
            ->where('nilai_mahasiswa.mahasiswa_id', $mahasiswa_id)
            ->where('nilai_mahasiswa.matakuliah_kelasid', $matakuliah_kelasid)
            ->get();



        $startNumber = [];

        if ($request->ajax()) {
            return view('pages-admin.perkuliahan.partials.nilai_sub_cpmk', [
                'data' => $data,
                'startNumber' => $startNumber,
            ])->with('success', 'Data Mata Kuliah Ditemukan');
        }
        return $data;
    }

    public function nilaiCpmk(Request $request)
    {
        $mahasiswa_id = $request->mahasiswa_id;
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
            ->where('nilai_mahasiswa.mahasiswa_id', $mahasiswa_id)
            ->where('nilai_mahasiswa.matakuliah_kelasid', $matakuliah_kelasid)
            ->get();


        $startNumber = [];

        if ($request->ajax()) {
            return view('pages-admin.perkuliahan.partials.nilai_cpmk', [
                'data' => $data,
                'startNumber' => $startNumber,
            ])->with('success', 'Data Mata Kuliah Ditemukan');
        }
        return $data;
    }

    public function nilaiTugas(Request $request)
    {
        $data = NilaiMahasiswa::join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('soal', 'soal.id', 'soal_sub_cpmk.soal_id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->select('soal_sub_cpmk.*', 'soal.bentuk_soal as bentuk_soal', 'nilai_mahasiswa.nilai as nilai', 'nilai_mahasiswa.id as id_nilai', 'sub_cpmk.kode_subcpmk as kode_subcpmk')
            ->where('nilai_mahasiswa.mahasiswa_id', $request->mahasiswa_id)
            ->where('nilai_mahasiswa.matakuliah_kelasid', $request->matakuliah_kelasid)
            ->get();
        $startNumber = [];

        if ($request->ajax()) {
            return view('pages-admin.perkuliahan.partials.nilai_tugas', [
                'data' => $data,
                'startNumber' => $startNumber,
            ])->with('success', 'Data Mata Kuliah Ditemukan');
        }
        return $data;
    }

    public function nilaiMahasiswa(Request $request, $id) {
        $kelasMatkul = KelasKuliah::join('rps', 'matakuliah_kelas.rps_id', 'rps.id')
        ->join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
        ->join('kelas', 'matakuliah_kelas.kelas_id', 'kelas.id')
        ->select('mata_kuliah.nama_matkul', 'kelas.nama_kelas', 'matakuliah_kelas.id as id_kelas')
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
            ->orderBy('mahasiswa.nim', 'ASC')
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

        // $startNumber = ($mahasiswa_data->currentPage() - 1) * $mahasiswa_data->perPage() + 1;
        // Sekarang $mahasiswa_data berisi data nilai untuk setiap mahasiswa dengan struktur yang diinginkan
        // dd(array_values($mahasiswa_data), array_values($info_soal));

        return view('pages-admin.perkuliahan.nilai_mahasiswa', ['data' => $kelasMatkul, 'mahasiswa_data' => $mahasiswa_data, 'info_soal' => $info_soal, 'id_kelas' => $id]);
    }

    public function generatePdf($id)
    {
        // Ambil data mata kuliah dari database
        $kelas_matkul = KelasKuliah::join('rps', 'matakuliah_kelas.rps_id', 'rps.id')
        ->join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
        ->join('kelas', 'matakuliah_kelas.kelas_id', 'kelas.id')
        ->join('dosen', 'matakuliah_kelas.dosen_id', 'dosen.id')
        ->select('mata_kuliah.nama_matkul', 'kelas.nama_kelas', 'dosen.nama as nama_dosen', 'matakuliah_kelas.id as id_kelas')
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
        $dompdf = new Dompdf();
        $dompdf->loadHtml(view('pages-admin.perkuliahan.nilai_pdf', ['kelas' => $kelas_matkul, 'mahasiswa_data' => $mahasiswa_data, 'info_soal' => $info_soal, 'jml_mhs' => $jumlah_mahasiswa]));

        // Atur opsi PDF
        $options = new Options();
        $options->set('isRemoteEnabled', true);

        $dompdf->setPaper('A4', 'landscape');

        // Render PDF
        $dompdf->setOptions($options);
        $dompdf->render();

        // Menghasilkan nama file unik untuk laporan
        $filename = 'Penilaian_' . $kelas_matkul->nama_matkul . '_Kelas ' . $kelas_matkul->nama_kelas . '.pdf';

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


    public function rataRataTugas(Request $request){
        $matakuliah_kelasid = $request->matakuliah_kelasid;
        $nilaiRataRata = NilaiMahasiswa::join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('soal', 'soal_sub_cpmk.soal_id', 'soal.id')
            ->selectRaw('soal.bentuk_soal, ROUND(AVG(nilai_mahasiswa.nilai), 2) as nilai_rata_rata')
            ->where('matakuliah_kelasid', $matakuliah_kelasid)
            ->groupBy('soal.bentuk_soal')
            ->get();

            $labels = $nilaiRataRata->pluck('bentuk_soal')->toArray();
            $values = $nilaiRataRata->pluck('nilai_rata_rata')->toArray();

            return response()->json([
                'labels' => $labels,
                'values' => $values,
            ]);
    }

    public function rataRataSubCPMK(Request $request){
        $matakuliah_kelasid = $request->matakuliah_kelasid;
        $query = NilaiMahasiswa::join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->join('soal', 'soal_sub_cpmk.soal_id', 'soal.id')
            ->selectRaw('ROUND(SUM(nilai_mahasiswa.nilai * soal_sub_cpmk.bobot_soal) / SUM(soal_sub_cpmk.bobot_soal), 1) as nilai_rata_rata, soal_sub_cpmk.bobot_soal AS bobot_soal, sub_cpmk.kode_subcpmk')
            // ->selectRaw('sub_cpmk.kode_subcpmk, ROUND(AVG(nilai_mahasiswa.nilai), 2) as nilai_rata_rata')
            ->where('matakuliah_kelasid', $matakuliah_kelasid)
            ->orderBy('sub_cpmk.kode_subcpmk', 'asc')
            ->groupBy('sub_cpmk.kode_subcpmk');

        $nilaiRataRata = $query->get();

            $labels = $nilaiRataRata->pluck('kode_subcpmk')->toArray();
            $values = $nilaiRataRata->pluck('nilai_rata_rata')->toArray();

            return response()->json([
                'labels' => $labels,
                'values' => $values,
            ]);
    }

    public function rataRataCPMK(Request $request){
        $matakuliah_kelasid = $request->matakuliah_kelasid;
        $query = NilaiMahasiswa::join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->join('soal', 'soal_sub_cpmk.soal_id', 'soal.id')
            ->join('cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
            ->selectRaw('ROUND(SUM(nilai_mahasiswa.nilai * soal_sub_cpmk.bobot_soal) / SUM(soal_sub_cpmk.bobot_soal), 1) as nilai_rata_rata, soal_sub_cpmk.bobot_soal AS bobot_soal, cpmk.kode_cpmk')
            // ->selectRaw('sub_cpmk.kode_subcpmk, ROUND(AVG(nilai_mahasiswa.nilai), 2) as nilai_rata_rata')
            ->where('matakuliah_kelasid', $matakuliah_kelasid)
            ->orderBy('cpmk.kode_cpmk', 'asc')
            ->groupBy('cpmk.kode_cpmk');

        // $sql = $query->toSql();
        // dd($sql);
        $nilaiRataRata = $query->get();

        $labels = $nilaiRataRata->pluck('kode_cpmk')->toArray();
        $values = $nilaiRataRata->pluck('nilai_rata_rata')->toArray();

            return response()->json([
                'labels' => $labels,
                'values' => $values,
            ]);
    }

    public function rataRataCPL(Request $request){
        $matakuliah_kelasid = $request->matakuliah_kelasid;
        $query = NilaiMahasiswa::join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->join('soal', 'soal_sub_cpmk.soal_id', 'soal.id')
            ->join('cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
            ->join('cpl', 'cpmk.cpl_id', 'cpl.id')
            ->selectRaw('ROUND(SUM(nilai_mahasiswa.nilai * soal_sub_cpmk.bobot_soal) / SUM(soal_sub_cpmk.bobot_soal), 1) as nilai_rata_rata, soal_sub_cpmk.bobot_soal AS bobot_soal, cpl.kode_cpl')
            // ->selectRaw('sub_cpmk.kode_subcpmk, ROUND(AVG(nilai_mahasiswa.nilai), 2) as nilai_rata_rata')
            ->where('matakuliah_kelasid', $matakuliah_kelasid)
            ->orderBy('cpl.kode_cpl', 'asc')
            ->groupBy('cpl.kode_cpl');

        // $sql = $query->toSql();
        // dd($sql);
        $nilaiRataRata = $query->get();

            $labels = $nilaiRataRata->pluck('kode_cpl')->toArray();
            $values = $nilaiRataRata->pluck('nilai_rata_rata')->toArray();

            return response()->json([
                'labels' => $labels,
                'values' => $values,
            ]);
    }
}

<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\NilaiMahasiswa;

class NilaiTugasFormatExcel implements FromView, ShouldAutoSize
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        // $id = $this->request->route('id');

        $nilai_mahasiswa = NilaiMahasiswa::join('mahasiswa', 'nilai_mahasiswa.mahasiswa_id', 'mahasiswa.id')
            ->join('matakuliah_kelas', 'nilai_mahasiswa.matakuliah_kelasid', 'matakuliah_kelas.id')
            ->join('rps', 'matakuliah_kelas.rps_id', 'rps.id')
            ->join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
            ->join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->join('soal', 'soal_sub_cpmk.soal_id', 'soal.id')
            ->select('mahasiswa.nim', 'mahasiswa.nama', 'soal_sub_cpmk.id', 'soal_sub_cpmk.waktu_pelaksanaan', 'sub_cpmk.kode_subcpmk', 'soal_sub_cpmk.bobot_soal', 'soal.bentuk_soal','nilai_mahasiswa.id as id_nilai','nilai_mahasiswa.mahasiswa_id as id_mhs', 'nilai_mahasiswa.matakuliah_kelasid as id_kelas', 'nilai_mahasiswa.nilai')
            ->where('matakuliah_kelas.id', $this->id)
            ->orderby('mahasiswa.nama', 'ASC')
            ->orderby('soal_sub_cpmk.id', 'ASC')
            ->get();

        $mahasiswa_data = [];
        $info_soal = [];
        $nomor = 1;

        foreach ($nilai_mahasiswa as $nilai) {
            $bentuk_soal = $nilai->bentuk_soal;
            $bobot_soal = $nilai->bobot_soal;
            $soal_key = $bentuk_soal . '_' . $bobot_soal;
            $mahasiswa_id = $nilai->nim;

            // Menyusun info soal
            if (!isset($info_soal[$bentuk_soal])) {
                $info_soal[$bentuk_soal] = [
                    'bobot_soal' => []
                ];
            }

            if (!in_array($bobot_soal, $info_soal[$bentuk_soal]['bobot_soal'])) {
                $info_soal[$bentuk_soal]['bobot_soal'][] = $bobot_soal;
            }

            // Tambah bobot_soal jika bentuk_soal sama
            // $info_soal[$bentuk_soal]['bobot_soal'] += $bobot_soal;

            // Menyusun data mahasiswa
            if (!isset($mahasiswa_data[$mahasiswa_id])) {
                $mahasiswa_data[$mahasiswa_id] = [
                    'kelas_id' => $nilai->id_kelas,
                    'id_mhs' => $nilai->id_mhs,
                    'nim' => $nilai->nim,
                    'nama' => $nilai->nama,
                    'nilai' => [],
                    'nomor' => $nomor,
                ];
            }
            $nomor++;
            $mahasiswa_data[$mahasiswa_id]['nilai'][$soal_key] = $nilai->nilai;
        }

        return view('pages-dosen.generate.excel.nilai-mahasiswa', [
            'mahasiswa_data' => $mahasiswa_data,
            'info_soal' => $info_soal
        ]);
    }
}

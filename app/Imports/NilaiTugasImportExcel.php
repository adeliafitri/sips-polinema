<?php

namespace App\Imports;

use App\Models\Cpmk;
use App\Models\KelasKuliah;
use App\Models\Mahasiswa;
use App\Models\NilaiAkhirMahasiswa;
use App\Models\NilaiMahasiswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use PhpParser\Node\Stmt\Foreach_;

class NilaiTugasImportExcel implements ToModel, WithHeadingRow
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function model(array $row)
    {
        // dd($row);
        // if (!empty($row[1])) {
            $nim = $row['nim'];
            $mahasiswa = Mahasiswa::where('nim', $nim)->first();
            // dd($mahasiswa);
            if ($mahasiswa) {
                        $nilaiTugas = NilaiMahasiswa::join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
                            ->join('soal', 'soal_sub_cpmk.soal_id', 'soal.id')
                            ->select('nilai_mahasiswa.id as id_nilai', 'soal.bentuk_soal')
                            ->where('mahasiswa_id', $mahasiswa->id)
                            ->where('matakuliah_kelasid', $this->id)
                            ->get()
                            ->groupBy('bentuk_soal');

                            foreach ($nilaiTugas as $bentukSoal => $tugasGroup) {
                                $nilaiTotal = 0;

                                foreach ($tugasGroup as $tugas) {
                                    $minggu = preg_replace('/[^a-z0-9]+/', '_', strtolower($bentukSoal));
                                    $nilai = $row[$minggu];

                                    if ($nilai !== null) {
                                        $nilaiTotal += $nilai;
                                    }
                                }

                                foreach ($tugasGroup as $tugas) {
                                    $nilaiMahasiswa = NilaiMahasiswa::find($tugas->id_nilai);
                                    $nilaiMahasiswa->nilai = $nilaiTotal / count($tugasGroup); // Average or distribute the score
                                    $nilaiMahasiswa->save();
                                }
                            }
                // dd($nilaiTugas);
                        // foreach($nilaiTugas as $tugas) {
                        //     $minggu =  preg_replace('/[&\s]+/', '_',strtolower($tugas->bentuk_soal));
                        //     // dd($minggu);
                        //     // dd($tugas->id_nilai);
                        //     $nilaiMahasiswa = NilaiMahasiswa::find($tugas->id_nilai);
                        //     $nilai = $row[$minggu];
                        //     $nilaiMahasiswa->nilai = $nilai;
                        //     // dd($nilaiMahasiswa->toSql(), $nilaiMahasiswa->getBindings());
                        //     // dd($nilaiMahasiswa->where('id', $tugas->id_nilai)->toSql(), $nilaiMahasiswa->getBindings());
                        //     $nilaiMahasiswa->save();
                        // }

                $update_nilai_akhir = NilaiMahasiswa::join('soal_sub_cpmk', 'nilai_mahasiswa.soal_id', 'soal_sub_cpmk.id')
                ->join('soal', 'soal.id', 'soal_sub_cpmk.soal_id')
                ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
                ->where('nilai_mahasiswa.mahasiswa_id', $mahasiswa->id)
                ->where('nilai_mahasiswa.matakuliah_kelasid', $this->id)
                ->selectRaw('(SUM(nilai_mahasiswa.nilai * soal_sub_cpmk.bobot_soal) / 100) AS nilai_akhir')
                ->first();

                $nilaiAkhirMahasiswa = NilaiAkhirMahasiswa::where('mahasiswa_id', $mahasiswa->id)
                ->where('matakuliah_kelasid', $this->id)
                ->first();

                $nilaiAkhirMahasiswa->nilai_akhir = $update_nilai_akhir->nilai_akhir;
                $nilaiAkhirMahasiswa->save();
            }
        // }
    }
}

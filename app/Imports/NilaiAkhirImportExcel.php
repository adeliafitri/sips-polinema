<?php

namespace App\Imports;

use App\Models\Mahasiswa;
use App\Models\NilaiAkhirMahasiswa;
use App\Models\NilaiMahasiswa;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class NilaiAkhirImportExcel implements ToModel, WithHeadingRow
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function model(array $row)
    {
        $nim = $row['nim'];
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if ($mahasiswa) {
            $nilaiAkhirMahasiswa = NilaiAkhirMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->where('matakuliah_kelasid', $this->id)
            ->first();

            $nilaiAkhirMahasiswa->nilai_akhir = $row['nilai_akhir'];
            $nilaiAkhirMahasiswa->save();

            $nilaiTugas = NilaiMahasiswa::where('mahasiswa_id', $mahasiswa->id)
            ->where('matakuliah_kelasid', $this->id)
            ->get();
            foreach ($nilaiTugas as $data) {
                // dd($data);
                $data->nilai = $row['nilai_akhir'];
                $data->save();
            }
        }
    }
}

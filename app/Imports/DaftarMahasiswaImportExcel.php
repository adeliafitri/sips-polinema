<?php

namespace App\Imports;

use App\Models\Cpmk;
use App\Models\User;
use App\Models\Dosen;
use App\Models\KelasKuliah;
use App\Models\Mahasiswa;
use App\Models\NilaiAkhirMahasiswa;
use App\Models\NilaiMahasiswa;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class DaftarMahasiswaImportExcel implements ToModel, WithHeadingRow
{
    private $matakuliah_kelasid;

    public function __construct($matakuliah_kelasid)
    {
        $this->matakuliah_kelasid = $matakuliah_kelasid;
    }

    public function model(array $row)
    {
        $nim = $row['nim'];
        $mahasiswa = Mahasiswa::where('nim', $nim)->first();

        if ($mahasiswa) {
            $rps_id = KelasKuliah::where('id', $this->matakuliah_kelasid)->value('rps_id');
            if (empty($rps_id)) {
                return redirect()->back()->withErrors(['errors' => 'Invalid matakuliah_kelas ID'])->withInput();
            }
            $soal_sub_cpmk = Cpmk::join('sub_cpmk', 'cpmk.id', 'sub_cpmk.cpmk_id')
                ->join('soal_sub_cpmk', 'sub_cpmk.id', 'soal_sub_cpmk.subcpmk_id')
                ->where('cpmk.rps_id', $rps_id)->select('soal_sub_cpmk.id as soal_id')->get();
            foreach ($soal_sub_cpmk as $data) {
                // dd($data);
                // $soal_id = $data->soal_id;
                try {
                    NilaiMahasiswa::create([
                        'mahasiswa_id' => $mahasiswa->id,
                        'matakuliah_kelasid' => $this->matakuliah_kelasid,
                        'soal_id' => $data->soal_id,
                    ]);
                } catch (\Exception $e) {
                    return redirect()->back()->withErrors(['errors' => 'Data Gagal Ditambahkan: ' . $e->getMessage()])->withInput();
                }
            }
            $nilaiAkhirMahasiswa = new NilaiAkhirMahasiswa();
            $nilaiAkhirMahasiswa->mahasiswa_id = $mahasiswa->id;
            $nilaiAkhirMahasiswa->matakuliah_kelasid = $this->matakuliah_kelasid;
            $nilaiAkhirMahasiswa->save();
        }
    }
}

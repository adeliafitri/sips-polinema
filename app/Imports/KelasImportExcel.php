<?php

namespace App\Imports;

use App\Models\Rps;
use App\Models\User;
use App\Models\Dosen;
use App\Models\Kelas;
use App\Models\Semester;
use App\Models\MataKuliah;
use App\Models\KelasKuliah;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class KelasImportExcel implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        try {
        $kode_matkul = $row['kode_mata_kuliah'];
        $tahun_rps = $row['tahun_rps'];

        $rps = Rps::join('mata_kuliah as mk', 'rps.matakuliah_id', 'mk.id')
            ->where('mk.kode_matkul', $kode_matkul)
            ->where('rps.tahun_rps', $tahun_rps)
            ->select('rps.id')
            ->first();

            if (!$rps) {
                throw new \Exception("RPS dengan kode mata kuliah $kode_matkul dan tahun $tahun_rps tidak ditemukan.");
            }

        $nidn = $row['nidn'];
        $dosen = Dosen::where('nidn', $nidn)->first();
        if (!$dosen) {
            throw new \Exception("Dosen dengan NIDN $nidn tidak ditemukan.");
        }

        $kelas = $row['kelas'];
        $kelas_id = Kelas::where('nama_kelas', $kelas)->pluck('id')->first();
        if (!$kelas_id) {
            throw new \Exception("Kelas dengan nama $kelas tidak ditemukan.");
        }

        $tahun_ajaran = $row['tahun_ajaran'];
        $semester = $row['semester'];
        $semester_id = Semester::where('tahun_ajaran', $tahun_ajaran)
        ->where('semester', $semester)
        ->pluck('id')->first();

        if (!$semester_id) {
            throw new \Exception("Semester dengan tahun ajaran $tahun_ajaran dan semester $semester tidak ditemukan.");
        }

        KelasKuliah::create([
            'rps_id' => $rps->id,
            'dosen_id' => $dosen->id,
            'semester_id' => $semester_id,
            'kelas_id' => $kelas_id
        ]);
        } catch (\Exception $e) {
            // Logging error ke dalam laravel log file
            Log::error("Error importing kelas: " . $e->getMessage(), [
                'row' => $row
            ]);

            // throw new \Exception("Error di baris: " . json_encode($row) . ". Pesan: " . $e->getMessage());
            throw new \Exception($e->getMessage());
            // Jika ada error, kamu juga bisa melempar exception atau sekedar melanjutkan proses tanpa memasukkan baris yang error
            // Misalnya dengan return null atau skip
            // return null;
        }
    }
}

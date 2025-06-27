<?php

namespace App\Imports;

use App\Models\User;
use App\Models\Dosen;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MahasiswaImportExcel implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        try {
            $password = $row['nim'];
            $auth =  User::create([
                'username' => $row['nim'],
                'password' => Hash::make($password),
                'role' => 'mahasiswa'
            ]);

            $id_auth = $auth->id;

            // $defaultStatus = 'aktif';

            Mahasiswa::create([
                'id_auth' => $id_auth,
                'nama' => $row['nama_mahasiswa'],
                'nim' => $row['nim'],
                'telp' => $row['no_telp'],
                'angkatan' => $row['angkatan'],
                'tahun_lulus' => '0000',
                'program_studi' => $row['program_studi'],
            ]);
        } catch (\Exception $e) {
            // Logging error ke dalam laravel log file
            Log::error("Error importing kelas: " . $e->getMessage(), [
                'row' => $row
            ]);

            // throw new \Exception("Error di baris: " . json_encode($row) . ". Pesan: " . $e->getMessage());
            throw new \Exception($e->getMessage());
        }
    }
}

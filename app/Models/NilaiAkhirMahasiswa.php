<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiAkhirMahasiswa extends Model
{
    use HasFactory;

    protected $table ='nilaiakhir_mahasiswa';

    protected $fillable = [
        'matakuliah_kelasid',
        'mahasiswa_id',
        'nilai_akhir',
    ];

    public function kelas_kuliah()
    {
        return $this->belongsTo(KelasKuliah::class);
    }
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }

}

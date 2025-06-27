<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NilaiMahasiswa extends Model
{
    use HasFactory;

    protected $table = 'nilai_mahasiswa';

    protected $fillable = [
        'matakuliah_kelasid',
        'mahasiswa_id',
        'soal_id',
        'nilai',
    ];

    public function kelas_kuliah()
    {
        return $this->belongsTo(KelasKuliah::class);
    }
    public function mahasiswa()
    {
        return $this->belongsTo(Mahasiswa::class);
    }
    public function soal()
    {
        return $this->belongsTo(SoalSubCpmk::class);
    }
}

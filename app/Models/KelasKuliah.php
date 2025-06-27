<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KelasKuliah extends Model
{
    use HasFactory;

    protected $table ='matakuliah_kelas';

    protected $fillable = [
        'rps_id',
        'kelas_id',
        'dosen_id',
        'semester_id',
        'koordinator',
        'evaluasi',
        'rencana_perbaikan'
    ];

    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }
    public function mata_kuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }
    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
    public function nilai_mahasiswa()
    {
        return $this->hasMany(NilaiMahasiswa::class);
    }

    public function nilaiakhir_mahasiswa()
    {
        return $this->hasMany(NilaiAkhirMahasiswa::class);
    }
}

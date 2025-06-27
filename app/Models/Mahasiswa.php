<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Mahasiswa extends Model
{
    use HasFactory;

    protected $table ='mahasiswa';

    protected $fillable = [
        'id_auth',
        'nama',
        'nim',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'telp',
        'image',
        'angkatan',
        'status',
        'tahun_lulus',
        'program_studi',
    ];

    public function auth()
    {
        return $this->belongsTo(Auth::class);
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

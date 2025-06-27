<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rps extends Model
{
    use HasFactory;

    protected $table ='rps';

    protected $fillable = [
        'matakuliah_id',
        'semester',
        'tahun_rps',
        'koordinator'
    ];

    public function cpmk()
    {
        return $this->hasMany(Cpmk::class, 'rps_id');
    }
    public function kelas_kuliah()
    {
        return $this->hasMany(KelasKuliah::class, 'rps_id');
    }
    public function mata_kuliah()
    {
        return $this->belongsTo(MataKuliah::class);
    }
    public function dosen()
    {
        return $this->belongsTo(Dosen::class);
    }
}

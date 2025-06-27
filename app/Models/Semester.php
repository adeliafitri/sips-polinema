<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use HasFactory;

    protected $table = 'semester';

    protected $fillable = [
        'semester',
        'tahun_ajaran',
        'is_active'
    ];

    public function kelas_kuliah()
    {
        return $this->hasMany(KelasKuliah::class);
    }
}

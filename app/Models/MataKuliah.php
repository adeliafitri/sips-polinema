<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MataKuliah extends Model
{
    use HasFactory;

    protected $table ='mata_kuliah';

    protected $fillable = [
        'kode_matkul',
        'nama_matkul',
        'sks',
        'is_pilihan', 
    ];

    public function rps()
    {
        return $this->hasMany(Rps::class, 'matakuliah_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dosen extends Model
{
    use HasFactory;

    protected $table = 'dosen';

    protected $fillable = [
        'id_auth',
        'nama',
        'nidn',
        'telp',
        'image',
        'email',
        'status'
    ];

    public function auth()
    {
        return $this->belongsTo(User::class);
    }
    public function kelas_kuliah()
    {
        return $this->hasMany(KelasKuliah::class);
    }
    public function rps()
    {
        return $this->hasMany(KelasKuliah::class);
    }
}

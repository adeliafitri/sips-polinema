<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpl extends Model
{
    use HasFactory;

    protected $table ='cpl';

    protected $fillable = [
        'kode_cpl',
        'deskripsi',
        'jenis_cpl',
    ];

    // Enum values for jenis_cpl column
    public static $jenisCPLOptions = ['Sikap', 'Pengetahuan', 'Keterampilan Umum', 'Keterampilan Khusus'];

    public function cpmk()
    {
        return $this->hasMany(Cpmk::class);
    }
}

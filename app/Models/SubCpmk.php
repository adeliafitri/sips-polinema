<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCpmk extends Model
{
    use HasFactory;

    protected $table ='sub_cpmk';

    protected $fillable = [
        'cpmk_id',
        'kode_subcpmk',
        'deskripsi',
    ];

    public function cpmk()
    {
        return $this->belongsTo(Cpmk::class);
    }

    public function soal()
    {
        return $this->hasMany(SoalSubCpmk::class, 'subcpmk_id');
    }
}

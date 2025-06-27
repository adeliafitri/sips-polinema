<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cpmk extends Model
{
    use HasFactory;

    protected $table ='cpmk';

    protected $fillable = [
        'rps_id',
        'cpl_id',
        'kode_cpmk',
        'deskripsi',
    ];

    public function cpl()
    {
        return $this->belongsTo(Cpl::class);
    }
    public function rps()
    {
        return $this->belongsTo(Rps::class);
    }
    public function sub_cpmk()
    {
        return $this->hasMany(SubCpmk::class, 'cpmk_id');
    }
}

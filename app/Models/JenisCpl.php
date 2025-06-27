<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisCpl extends Model
{
    use HasFactory;

    protected $table ='jenis_cpl';

    protected $fillable = [
        'nama_jenis',
    ];

    public function cpl()
    {
        return $this->hasMany(Cpl::class);
    }
}

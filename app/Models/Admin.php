<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;

    protected $table ='admin';

    protected $fillable = [
        'id_auth',
        'nama',
        'email',
        'telp',
        'image',
    ];

    public function auth()
    {
        return $this->belongsTo(User::class);
    }
}

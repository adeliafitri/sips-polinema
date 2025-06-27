<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use App\Models\NilaiAkhirMahasiswa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        return view('pages-mahasiswa.dashboard');
    }

}

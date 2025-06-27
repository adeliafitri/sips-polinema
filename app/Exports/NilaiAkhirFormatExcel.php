<?php

namespace App\Exports;

use Illuminate\Http\Request;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\NilaiAkhirMahasiswa;

class NilaiAkhirFormatExcel implements FromView, ShouldAutoSize
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        // $id = $this->request->route('id');

        $mahasiswa = NilaiAkhirMahasiswa::join('mahasiswa', 'nilaiakhir_mahasiswa.mahasiswa_id', 'mahasiswa.id')
            ->join('matakuliah_kelas', 'nilaiakhir_mahasiswa.matakuliah_kelasid', 'matakuliah_kelas.id')
            ->select('mahasiswa.nim', 'mahasiswa.nama')
            ->where('matakuliah_kelas.id', $this->id)
            ->get();

        return view('pages-dosen.generate.excel.nilai-akhir', [
            'data' => $mahasiswa,
        ]);
    }
}

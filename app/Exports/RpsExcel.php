<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use App\Models\SoalSubCpmk;

class RpsExcel implements FromView, ShouldAutoSize
{
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function view(): View
    {
        // $id = $this->request->route('id');

        $data_rps = SoalSubCpmk::join('soal', 'soal_sub_cpmk.soal_id', 'soal.id')
            ->join('sub_cpmk', 'soal_sub_cpmk.subcpmk_id', 'sub_cpmk.id')
            ->join('cpmk', 'sub_cpmk.cpmk_id', 'cpmk.id')
            ->join('cpl', 'cpmk.cpl_id', 'cpl.id')
            ->join('rps', 'cpmk.rps_id', 'rps.id')
            ->join('mata_kuliah', 'rps.matakuliah_id', 'mata_kuliah.id')
            ->select('cpl.kode_cpl', 'cpmk.kode_cpmk', 'cpmk.deskripsi', 'sub_cpmk.kode_subcpmk', 'sub_cpmk.deskripsi', 'soal.bentuk_soal', 'soal_sub_cpmk.bobot_soal', 'soal_sub_cpmk.waktu_pelaksanaan')
            ->where('rps.id', $this->id)
            ->get();

        return view('generate.excel.data-rps', [
            'data' => $data_rps,
        ]);
    }
}

<?php
namespace App\Imports;

use App\Models\Cpl;
use App\Models\Cpmk;
use App\Models\Soal;
use App\Models\SubCpmk;
use App\Models\SoalSubCpmk;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class RpsImport implements ToCollection
{
    private $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            // Skip baris header
            if ($index == 0) {
                continue;
            }

            $cpl = Cpl::select('id')->where('kode_cpl', $row[2])->first();
            // dd($cpl->id);
            // Tahap 2: Simpan data CPMK
            $cpmk = Cpmk::firstOrCreate(
                [
                    'cpl_id' => optional($cpl)->id,
                    'rps_id' => $this->id,
                    'kode_cpmk' => $row[3]
                ],
                [
                    'deskripsi' => $row[4]
                ]
            );

            // Tahap 3: Simpan data Sub CPMK
            $subCpmk = SubCpmk::firstOrCreate(
                [
                    'cpmk_id' => $cpmk->id,
                    'kode_subcpmk' => $row[5]
                ],
                [
                    'deskripsi' => $row[6]
                ]
            );

            $soal = Soal::firstorCreate(
                [
                    'bentuk_soal' => $row[7],
                ]
            );

            // Tahap 4: Simpan data Tugas
            SoalSubCpmk::firstOrCreate(
                [
                    'subcpmk_id' => $subCpmk->id,
                    'soal_id' => $soal->id,
                    'bobot_soal' => $row[8],
                    'waktu_pelaksanaan' => $row[1]
                ],
            );
        }
    }
}


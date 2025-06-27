<?php

namespace App\Exports;

use App\Models\Cpl;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class CplFormatExcel implements FromView, ShouldAutoSize, WithEvents
{
    // public function view(): View
    // {
    //     $jenisCPLOptions = Cpl::$jenisCPLOptions;
    //     return view('pages-admin.generate.excel.cpl-format', [
    //         'jenisCPLOptions' => $jenisCPLOptions,
    //     ]);
    // }

    protected $jenisCPLOptions;

    public function __construct()
    {
        $this->jenisCPLOptions = Cpl::$jenisCPLOptions;
    }

    public function view(): View
    {
        return view('pages-admin.generate.excel.cpl-format', [
            'jenisCPLOptions' => $this->jenisCPLOptions,
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $optionsString = implode(',', $this->jenisCPLOptions);
                $dropdownCell = $event->sheet->getCell('B2');

                // Set the dropdown without data validation
                $dropdownCell->getDataValidation()
                    ->setType(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::TYPE_LIST)
                    ->setErrorStyle(\PhpOffice\PhpSpreadsheet\Cell\DataValidation::STYLE_INFORMATION)
                    ->setAllowBlank(false)
                    ->setShowInputMessage(true)
                    ->setShowErrorMessage(true)
                    ->setShowDropDown(true)
                    ->setErrorTitle('Input error')
                    ->setError('Value is not in the list.')
                    ->setFormula1('"' . $optionsString . '"'); // Use double quotes to indicate a string

                // Set the default value for the dropdown
                $dropdownCell->setValue($this->jenisCPLOptions[0]);
            },
        ];
    }
}

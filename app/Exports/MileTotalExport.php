<?php

namespace App\Exports;

use DB;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class MileTotalExport implements FromView, ShouldAutoSize, WithStyles
{
    private $search;
    private $limit;

    public function __construct($search, $limit)
    {
        $this->search = $search;
        $this->limit = $limit;
    }

    public function view(): View
    {
        $excel_data = get_data_mile_total($this->search, $this->limit);
        return view('util.download_data.mile_total', [
            'headers'   => $excel_data->header,
            'values'    => $excel_data->data
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:Z1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B2:Z1000')->getAlignment()->setHorizontal('right');
    }
}
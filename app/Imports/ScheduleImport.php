<?php

namespace App\Imports;

use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToArray;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\BeforeSheet;

use App\Models\WeeklySchedule;

class ScheduleImport implements ToArray, WithEvents
{
    public $sheetNames;
    //public $sheetData;

    public function __construct()
    {
        $this->sheetNames = [];
        //$this->sheetData = [];
    }

    public function array(array $array)
    {
        //$this->sheetData[$this->sheetNames[count($this->sheetNames)-1]] = $array;

        $name = $this->sheetNames[count($this->sheetNames)-1];
        $fleet_net = $array[0][1];

        $start = 4;
        for ($i = $start; $i < count($array); $i++) {
            $year               = $array[$i][0];
            $week               = $array[$i][1];
            
            // $from = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($array[$i][2])->format('Y-m-d');
            // $to = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($array[$i][3])->format('Y-m-d');
            $from               = $array[$i][2];
            $to                 = $array[$i][3];
            $driver_name        = $array[$i][4];
            $driver_id          = $array[$i][5];
            $tractor_id         = $array[$i][6];
            $tcheck             = $array[$i][7];
            $spare_unit         = $array[$i][8];

            $saturday           = $array[$i][9];
            $sunday             = $array[$i][10];
            $monday             = $array[$i][11];
            $tuesday            = $array[$i][12];
            $wednesday          = $array[$i][13];
            $thursday           = $array[$i][14];
            $friday             = $array[$i][15];

            WeeklySchedule::insert([
                'year_num'      => $year,
                'week_num'      => $week,
                'from_date'     => $from,
                'to_date'       => $to,
                'driver_id'     => $driver_id,
                'driver_name'   => $driver_name,
                'tractor_id'    => $tractor_id,
                'tcheck'        => $tcheck,
                'spare_unit'    => $spare_unit,
                'fleet_net'     => $fleet_net,
                'saturday'      => $saturday,
                'sunday'        => $sunday,
                'monday'        => $monday,
                'tuesday'       => $tuesday,
                'wednesday'     => $wednesday,
                'thursday'      => $thursday,
                'friday'        => $friday
            ]);
        }
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function(BeforeSheet $event) {
                $this->sheetNames[] = $event->getSheet()->getTitle();
            },
        ];
    }
}
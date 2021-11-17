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

        $start = 5;
        for ($i = $start; $i < count($array); $i++) {
            $year               = $array[$i][0];
            $week               = $array[$i][1];
            
            // $from = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($array[$i][2])->format('Y-m-d');
            // $to = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($array[$i][3])->format('Y-m-d');
            $from               = $array[$i][2];
            $to                 = $array[$i][3];
            $driver_name        = $array[$i][4];
            $driver_id          = $array[$i][5];
            $tcheck             = $array[$i][6];
            $spare_unit         = $array[$i][7];

            $sat_tractor_id     = $array[$i][8];
            $sat_start_time     = $array[$i][9];
            $sun_tractor_id     = $array[$i][10];
            $sun_start_time     = $array[$i][11];
            $mon_tractor_id     = $array[$i][12];
            $mon_start_time     = $array[$i][13];
            $tue_tractor_id     = $array[$i][14];
            $tue_start_time     = $array[$i][15];
            $wed_tractor_id     = $array[$i][16];
            $wed_start_time     = $array[$i][17];
            $thu_tractor_id     = $array[$i][18];
            $thu_start_time     = $array[$i][19];
            $fri_tractor_id     = $array[$i][20];
            $fri_start_time     = $array[$i][21];

            $schedules = WeeklySchedule::where('year_num', $year)
                                        ->where('week_num', $week)
                                        ->where('driver_id', $driver_id)
                                        ->get()
                                        ->all();
            
            if (empty($schedules)) {
                // insert new data from csv/xlsx into database
                WeeklySchedule::insert([
                    'year_num'          => $year,
                    'week_num'          => $week,
                    'from_date'         => $from,
                    'to_date'           => $to,
                    'driver_id'         => $driver_id,
                    'driver_name'       => $driver_name,
                    'tcheck'            => $tcheck,
                    'spare_unit'        => $spare_unit,
                    'fleet_net'         => $fleet_net,
                    'sat_tractor_id'    => $sat_tractor_id,
                    'sat_start_time'    => $sat_start_time,
                    'sun_tractor_id'    => $sun_tractor_id,
                    'sun_start_time'    => $sun_start_time,
                    'mon_tractor_id'    => $mon_tractor_id,
                    'mon_start_time'    => $mon_start_time,
                    'tue_tractor_id'    => $tue_tractor_id,
                    'tue_start_time'    => $tue_start_time,
                    'wed_tractor_id'    => $wed_tractor_id,
                    'wed_start_time'    => $wed_start_time,
                    'thu_tractor_id'    => $thu_tractor_id,
                    'thu_start_time'    => $thu_start_time,
                    'fri_tractor_id'    => $fri_tractor_id,
                    'fri_start_time'    => $fri_start_time
                ]);
            } else {
                // update data
                $s = $schedules[0];

                $s->from_date       = $from;
                $s->to_date         = $to;
                $s->driver_name     = $driver_name;
                $s->tcheck          = $tcheck;
                $s->spare_unit      = $spare_unit;
                $s->fleet_net       = $fleet_net;
                $s->sat_tractor_id  = $sat_tractor_id;
                $s->sat_start_time  = $sat_start_time;
                $s->sun_tractor_id  = $sun_tractor_id;
                $s->sun_start_time  = $sun_start_time;
                $s->mon_tractor_id  = $mon_tractor_id;
                $s->mon_start_time  = $mon_start_time;
                $s->tue_tractor_id  = $tue_tractor_id;
                $s->tue_start_time  = $tue_start_time;
                $s->wed_tractor_id  = $wed_tractor_id;
                $s->wed_start_time  = $wed_start_time;
                $s->thu_tractor_id  = $thu_tractor_id;
                $s->thu_start_time  = $thu_start_time;
                $s->fri_tractor_id  = $fri_tractor_id;
                $s->fri_start_time  = $fri_start_time;

                $s->save();
            }
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
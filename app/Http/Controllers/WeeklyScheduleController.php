<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Imports\ScheduleImport;
use Maatwebsite\Excel\Facades\Excel;

use App\Models\WeeklySchedule;

class WeeklyScheduleController extends Controller
{
    public function index()
    {
        return view('schedule.upload');
    }

    public function upload(Request $request)
    {
        $file = $request->file('upload-file');
        
        if ($file) {
            $file_name = $file->getClientOriginalName();

            $Imports = new ScheduleImport();
            $ts = Excel::import($Imports, $file);

            $request->session()->flash('status', 'Weekly Schedules were imported from <b>' . $file_name . '</b> successfully !');
        } else {
            $request->session()->flash('error', 'Please choose a file to submit.');
        }
        return view('schedule.upload');
    }

    public function search(Request $request)
    {
        $year_num = $request->input('year-num') ?? date("Y");
        $week_num = $request->input('week-num') ?? date("W");
        $driver_id = $request->input('driver-id') ?? '';
        
        $schedules = WeeklySchedule::with('driver')
                                ->where('year_num', $year_num)
                                ->where('week_num', $week_num)
                                ->where('driver_id', 'like', '%' . $driver_id . '%')
                                ->get()
                                ->all();
        
        return view('schedule.search', compact('year_num', 'week_num', 'driver_id', 'schedules'));
    }

    public function getSchedule(Request $request)
    {
        $schedule_id = $request->s_id;
        if (empty($schedule_id)) {
            $schedule = null;
        } else {
            $schedule = WeeklySchedule::find($schedule_id);
        }
        
        return view('schedule.view', compact('schedule'));
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Linehaul_Drivers;
use App\Models\Linehaul_Trips;
use App\Models\FixedRateSetting;
use DateTime;
use DB;

class PayrollController extends Controller
{
    //members
    private $year_num;
    private $week_num;
    private $work_status;
    
    public function __construct()
    {
        $this->year_num     = Date("Y");
        $this->week_num     = Date("W");
        $this->work_status  = 1;
    }

    public function index(Request $request)
    {
        $this->authorize('manage-payroll');
        
        if ($request->input('year-num') && $request->input('week-num')) {
            $this->year_num = $request->input('year-num');
            $this->week_num = $request->input('week-num');    
        } else {
            $this->year_num = Date("Y");
            $this->week_num = Date("W");
        }
        
        if ($request->input('work-status') !== null) {
            $this->work_status = $request->input('work-status');
        } else {
            $this->work_status = 1;
        }
                
        $fixed_rates = FixedRateSetting::all();
        $drivers = Linehaul_Drivers::where('work_status', $this->work_status)->get()->all();

        $payrolls = [];
        $total_price = 0;
        foreach ($drivers as $d) {
            $payroll = new \stdClass();
            $payroll->id = $d->id;
            $payroll->driver_id = $d->driver_id;
            $payroll->driver_name = $d->driver_name;
            $payroll->work_status = $d->work_status;
            $payroll->price_per_mile = $d->price_per_mile;
            $payroll->total_miles = 0;
            $payroll->fr_miles = 0;
            $payroll->other_miles = 0;
            $payroll->total_price = 0;
            $payroll->fr_price = 0;
            $payroll->other_price = 0;

            $trips = Linehaul_Trips::where('year_num', $this->year_num)
                                ->where('week_num', $this->week_num)
                                ->where('driver_1', $d->driver_id)
                                ->get()
                                ->all();
            
            foreach ($trips as $t) {
                $flag = false;
                foreach ($fixed_rates as $r) {
                    if ($t->miles_qty >= $r->from_miles && $t->miles_qty <= $r->to_miles) {
                        $payroll->fr_miles += $t->miles_qty;
                        $payroll->fr_price += $r->fixed_rate;
                        $flag = true;
                        break;
                    }
                }

                // in case miles is not in mileage for fixed rate
                if (!$flag) {
                    $payroll->other_miles += $t->miles_qty;
                }
            }

            $payroll->other_price = $payroll->other_miles * $d->price_per_mile;
            $payroll->total_miles = $payroll->fr_miles + $payroll->other_miles;
            $payroll->total_price = $payroll->fr_price + $payroll->other_price;

            $total_price += $payroll->total_price;

            array_push($payrolls, $payroll);
        }
        
        return view('payroll.payrolls', [
            'year_num'      => $this->year_num,
            'week_num'      => $this->week_num,
            'work_status'   => $this->work_status,
            'payrolls'      => $payrolls,
            'total_price'   => $total_price
        ]);
    }

    public function get_payroll(Request $request)
    {
        $this->authorize('manage-payroll');

        $id = $request->route()->parameter('id');
        $year_num = $request->route()->parameter('year');
        $week_num = $request->route()->parameter('week');

        if ($year_num && $week_num) {
            $this->year_num = $year_num;
            $this->week_num = $week_num;
        } else {
            $this->year_num = Date("Y");
            $this->week_num = Date("W");
        }

        $d = Linehaul_Drivers::find($id);
        $fixed_rates = FixedRateSetting::all();

        $payroll = new \stdClass();
        $payroll->id = $d->id;
        $payroll->driver_id = $d->driver_id;
        $payroll->driver_name = $d->driver_name;
        $payroll->work_status = $d->work_status;
        $payroll->price_per_mile = $d->price_per_mile;
        $payroll->total_miles = 0;
        $payroll->fr_miles = 0;
        $payroll->other_miles = 0;
        $payroll->total_price = 0;
        $payroll->fr_price = 0;
        $payroll->other_price = 0;

        $trips = Linehaul_Trips::where('year_num', $this->year_num)
                            ->where('week_num', $this->week_num)
                            ->where('driver_1', $d->driver_id)
                            ->get()
                            ->all();
        
        foreach ($trips as $t) {
            $flag = false;
            foreach ($fixed_rates as $r) {
                if ($t->miles_qty >= $r->from_miles && $t->miles_qty <= $r->to_miles) {
                    $payroll->fr_miles += $t->miles_qty;
                    $payroll->fr_price += $r->fixed_rate;
                    $flag = true;
                    break;
                }
            }

            // in case miles is not in mileage for fixed rate
            if (!$flag) {
                $payroll->other_miles += $t->miles_qty;
            }
        }

        $payroll->other_price = $payroll->other_miles * $d->price_per_mile;
        $payroll->total_miles = $payroll->fr_miles + $payroll->other_miles;
        $payroll->total_price = $payroll->fr_price + $payroll->other_price;

        // dd($payroll);
        return view('payroll.view', [
            'year_num'      => $this->year_num,
            'week_num'      => $this->week_num,
            'payroll'       => $payroll
        ]);
    }

    public function get_rates()
    {
        $this->authorize('manage-payroll-setting');

        $rates = Linehaul_Drivers::orderBy('work_status', 'desc')
                                ->get()
                                ->all();
        return view('payroll.rates', [
            'rates' => $rates
        ]);
    }

    public function get_rate(Request $request)
    {
        $this->authorize('manage-payroll-setting');

        $id = $request->route()->parameter('id');
        
        $rate = Linehaul_Drivers::find($id);

        return view('payroll.rate', [
            'rate' => $rate
        ]);
    }

    public function save_rate(Request $request)
    {
        $this->authorize('manage-payroll-setting');

        $id             = $request->input('id');
        $price_per_mile = $request->input('price-per-mile');

        $rate = Linehaul_Drivers::find($id);
        $rate->price_per_mile = $price_per_mile;
        $rate->save();

        $request->session()->flash('status', 'Updated price per mile for <strong>' . $rate->driver_name . '</strong> !');
        return redirect()->route('get-rates');
    }

    public function remove_rate(Request $request)
    {
        $this->authorize('manage-payroll-setting');

        $id = $request->route()->parameter('id');
        
        $rate = Linehaul_Drivers::find($id);
        $rate->price_per_mile = 0;
        $rate->save();
        
        $request->session()->flash('status', 'Removed fixed rate and price per mile for <strong>' . $rate->driver_name . '</strong> !');
        return redirect()->route('get-rates');
    }

    public function get_fixed_rates_setting()
    {
        $this->authorize('manage-payroll-setting');

        $rates = FixedRateSetting::all();
        
        return view('payroll.fixed_rates_setting', compact('rates')); 
    }
    
    public function save_fixed_rates_setting(Request $request)
    {
        $this->authorize('manage-payroll-setting');

        $from_miles = $request->input('from-miles');
        $to_miles = $request->input('to-miles');
        $fixed_rates = $request->input('fixed-rate');

        if (empty($from_miles)) {
            $request->session()->flash('error', 'Please add at least one fixed rate data!');
            return redirect()->route('get-rates');
        }

        FixedRateSetting::truncate();

        foreach ($fixed_rates as $idx => $fr) {
            $fm = $from_miles[$idx];
            $tm = $to_miles[$idx];

            $rate = new FixedRateSetting();
            
            $rate->from_miles = $fm;
            $rate->to_miles = $tm;
            $rate->fixed_rate = $fr;

            $rate->save();
        }

        $request->session()->flash('status', 'Updated fixed rates setting!');
        return redirect()->route('get-rates');
    }

    public function save_workstatus(Request $request)
    {
        $id = $request->route()->parameter('id');

        $driver = Linehaul_Drivers::find($id);
        $driver->work_status = ($driver->work_status == 1) ? 0 : 1;
        $driver->save();

        $request->session()->flash('status', 'Changed work status for <strong>' . $driver->driver_name . '</strong> !');
        return redirect()->route('get-rates');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

use App\Models\Company;
use App\Models\Linehaul_Drivers;
use App\Models\Linehaul_Trips;
use App\Models\FixedRateSetting;
use App\Models\GlobalSetting;

use App\Mail\DriverEarningsReportMail;

use Carbon\Carbon;
use stdClass;

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

        FixedRateSetting::whereNotNull('id')->delete();

        foreach ($fixed_rates as $idx => $fr) {
            $fm = $from_miles[$idx];
            $tm = $to_miles[$idx];

            $rate = new FixedRateSetting();
            
            $rate->from_miles = $fm;
            $rate->to_miles = $tm;
            $rate->fixed_rate = $fr;
            $rate->company_id = Auth::user()->company_id;

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

    public function get_setting(Request $request)
    {
        $this->authorize('manage-global-setting');

        // if sending_method = '1', automatically sends email
        // if sending_method = '0', maually sends email
        $sending_method = GlobalSetting::where('module', 'payroll')
                                ->where('key', 'sending_method')
                                ->get()
                                ->first();
        $delivery_date = GlobalSetting::where('module', 'payroll')
                                ->where('key', 'delivery_date')
                                ->get()
                                ->first();
        
        if (!$sending_method || !$delivery_date) {
            $request->session()->flash('status', null);
            $request->session()->flash('error', '<strong>Payroll Setting</strong> not saved yet!');
        }
        return view('payroll.payroll_setting', compact('sending_method', 'delivery_date')); 
    }

    public function save_setting(Request $request)
    {
        $this->authorize('manage-global-setting');

        $sm = $request->input('sending-method') ?? '0';
        $dd = $request->input('delivery-date') ?? 'Monday';

        // if sending_method = '1', automatically sends email with payroll earnings to drivers
        // if sending_method = '0', maually sends email with payroll earnings to drivers
        $sending_method = GlobalSetting::where('module', 'payroll')
                                ->where('key', 'sending_method')
                                ->get()
                                ->first();
        $delivery_date = GlobalSetting::where('module', 'payroll')
                                ->where('key', 'delivery_date')
                                ->get()
                                ->first();
        
        if ($sending_method) {
            $sending_method->value = $sm;
        } else {
            $sending_method = new GlobalSetting();
            $sending_method->company_id = Auth::user()->company_id;
            $sending_method->module = 'payroll';
            $sending_method->key = 'sending_method';
            $sending_method->value = $sm;
        }

        if ($delivery_date) {
            $delivery_date->value = ($sm != '0') ? $dd : null;
        } else {
            $delivery_date = new GlobalSetting();
            $delivery_date->company_id = Auth::user()->company_id;
            $delivery_date->module = 'payroll';
            $delivery_date->key = 'delivery_date';
            $delivery_date->value = ($sm != '0') ? $dd : null;
        }
        $sending_method->save();
        $delivery_date->save();

        $request->session()->flash('error', null);
        $request->session()->flash('status', '<strong>Payroll Setting</strong> saved successfully!');
        
        return redirect()->route('payroll-get-setting');
    }

    public function get_drivers(Request $request)
    {
        $this->authorize('manage-payroll');

        $sending_method = GlobalSetting::where('module', 'payroll')
                                    ->where('key', 'sending_method')
                                    ->get()
                                    ->first();
                                    
        $available = true;
        if (!$sending_method) {
            $request->session()->flash('error', '<strong>Payroll Setting</strong> not saved yet!');
            $available = false;
        } else if ($sending_method->value == '1') {
            $request->session()->flash('error', '<strong>Driver Payroll Report</strong> will be sent weekly automatically!');
            $available = false;
        }

        $drivers = Linehaul_Drivers::where('work_status', 1)
                                    ->get()
                                    ->all();
        

        return view('payroll.earnings', compact('drivers', 'available'));
    }

    protected function get_payroll_report($id, $from_date, $to_date)
    {
        $fixed_rates = FixedRateSetting::all();
        $driver = Linehaul_Drivers::find($id);
        $company = Company::find(Auth::user()->company_id);
        $today = Carbon::now()->format('Y-m-d');

        $payroll = new \stdClass();
        $payroll->id = $driver->id;
        $payroll->driver_id = $driver->driver_id;
        $payroll->driver_name = $driver->driver_name;
        $payroll->email = $driver->email;
        $payroll->work_status = $driver->work_status;
        $payroll->price_per_mile = $driver->price_per_mile;
        $payroll->total_miles = 0;
        $payroll->fr_miles = 0;
        $payroll->other_miles = 0;
        $payroll->total_price = 0;
        $payroll->fr_price = 0;
        $payroll->other_price = 0;

        $trips = Linehaul_Trips::where('date', '>=', $from_date)
                            ->where('date', '<=', $to_date)
                            ->where('driver_1', $driver->driver_id)
                            ->orderBy('date', 'ASC')
                            ->get()
                            ->all();
        
        $new_trips = [];
        foreach ($trips as $t) {
            $new_trip = new stdClass();
            $new_trip->date = Carbon::createFromFormat('Y-m-d', $t->date)->format('m/d/Y');
            $new_trip->origin = $t->leg_org;
            $new_trip->destination = $t->leg_dest;
            $new_trip->miles = $t->miles_qty;
            $new_trip->value = 0;
            
            $flag = false;
            foreach ($fixed_rates as $r) {
                if ($t->miles_qty >= $r->from_miles && $t->miles_qty <= $r->to_miles) {
                    $payroll->fr_miles += $t->miles_qty;
                    $payroll->fr_price += $r->fixed_rate;

                    $new_trip->value = $r->fixed_rate;

                    $flag = true;
                    break;
                }
            }

            // in case miles is not in mileage for fixed rate
            if (!$flag) {
                $payroll->other_miles += $t->miles_qty;

                $new_trip->value = $t->miles_qty * $driver->price_per_mile;
            }

            $new_trips[] = $new_trip;
        }

        $payroll->other_price = $payroll->other_miles * $driver->price_per_mile;
        $payroll->total_miles = $payroll->fr_miles + $payroll->other_miles;
        $payroll->total_price = $payroll->fr_price + $payroll->other_price;
        $payroll->trips = $new_trips;
        $payroll->trips_num = count($new_trips);
        
        $payroll->company = $company;
        $payroll->payment_date = Carbon::createFromFormat('Y-m-d', $today)->format('m/d/Y');
        $payroll->from_date = Carbon::createFromFormat('Y-m-d', $from_date)->format('m/d/Y');
        $payroll->to_date = Carbon::createFromFormat('Y-m-d', $to_date)->format('m/d/Y');

        return $payroll;
    }

    public function get_driver_earnings_report(Request $request)
    {
        $this->authorize('manage-payroll');

        $id = $request->route()->parameter('id');
        $from_date = $request->route()->parameter('from_date');
        $to_date = $request->route()->parameter('to_date');
                
        if (!$id || !$from_date || !$to_date || $from_date > $to_date) {
            $request->session()->flash('error', 'Input Error!');
            return back()->withInput();
        }

        $payroll = $this->get_payroll_report($id, $from_date, $to_date);

        return view('payroll.earnings_report', compact('payroll', 'from_date', 'to_date'));
    }

    public function send_report_email(Request $request)
    {
        $this->authorize('manage-payroll');

        $id = $request->input('driver-id');
        $from_date = $request->input('from-date');
        $to_date = $request->input('to-date');

        if (!$id || !$from_date || !$to_date || $from_date > $to_date) {
            $request->session()->flash('error', 'Input Error!');
            return back()->withInput();
        }

        $payroll = $this->get_payroll_report($id, $from_date, $to_date);

        if ($payroll->email) {
            Mail::to($payroll->email)->send(new DriverEarningsReportMail($payroll));
            $request->session()->flash('status', "Driver Payroll Report sent to <strong>{$payroll->driver_name}</strong> successfully!");
        } else {
            $request->session()->flash('error', "<strong>{$payroll->driver_name}</strong> has not his email address yet!");
        }

        return redirect('/payroll/drivers');
    }

    public function send_bulk_report_emails(Request $request)
    {
        $this->authorize('manage-payroll');

        $from_date = $request->input('from-date');
        $to_date = $request->input('to-date');
        $ids = $request->input('checked-drivers');

        $fixed_rates = FixedRateSetting::all();
        $drivers = Linehaul_Drivers::whereIn('id', $ids)->get()->all();
        $company = Company::find(Auth::user()->company_id);
        $today = Carbon::now()->format('Y-m-d');

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

            $trips = Linehaul_Trips::where('date', '>=', $from_date)
                                ->where('date', '<=', $to_date)
                                ->where('driver_1', $d->driver_id)
                                ->get()
                                ->all();
            
            $new_trips = [];
            foreach ($trips as $t) {
                $new_trip = new stdClass();
                $new_trip->date = Carbon::createFromFormat('Y-m-d', $t->date)->format('m/d/Y');
                $new_trip->origin = $t->leg_org;
                $new_trip->destination = $t->leg_dest;
                $new_trip->miles = $t->miles_qty;
                $new_trip->value = 0;
                
                $flag = false;
                foreach ($fixed_rates as $r) {
                    if ($t->miles_qty >= $r->from_miles && $t->miles_qty <= $r->to_miles) {
                        $payroll->fr_miles += $t->miles_qty;
                        $payroll->fr_price += $r->fixed_rate;

                        $new_trip->value = $r->fixed_rate;

                        $flag = true;
                        break;
                    }
                }

                // in case miles is not in mileage for fixed rate
                if (!$flag) {
                    $payroll->other_miles += $t->miles_qty;

                    $new_trip->value = $t->miles_qty * $d->price_per_mile;
                }

                $new_trips[] = $new_trip;
            }

            $payroll->other_price = $payroll->other_miles * $d->price_per_mile;
            $payroll->total_miles = $payroll->fr_miles + $payroll->other_miles;
            $payroll->total_price = $payroll->fr_price + $payroll->other_price;
            $payroll->trips = $new_trips;
            $payroll->trips_num = count($new_trips);
            
            $payroll->company = $company;
            $payroll->payment_date = Carbon::createFromFormat('Y-m-d', $today)->format('m/d/Y');
            $payroll->from_date = Carbon::createFromFormat('Y-m-d', $from_date)->format('m/d/Y');
            $payroll->to_date = Carbon::createFromFormat('Y-m-d', $to_date)->format('m/d/Y');
                            
            if ($d->email) {
                Mail::to($d->email)->send(new DriverEarningsReportMail($payroll));
            }
        }

        $request->session()->flash('status', "Driver Payroll Reports sent to chosen drivers successfully!");

        return redirect('/payroll/drivers');
    }
}
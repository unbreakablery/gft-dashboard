<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\linehaul_drivers;
use DateTime;
use DB;

class PayrollController extends Controller
{
    //members
    private $year_num;
    private $week_num;
    private $from_m_fr;
    private $to_m_fr;

    public function __construct()
    {
        $this->year_num         = Date("Y");
        $this->week_num         = Date("W");
        
        $this->get_setting();
    }

    private function get_setting()
    {
        $from_m_fr = DB::table('global_setting')
                            ->select('code', 'value')
                            ->where('code', 'from_m_fr')
                            ->get()
                            ->first();
        $to_m_fr = DB::table('global_setting')
                            ->select('code', 'value')
                            ->where('code', 'to_m_fr')
                            ->get()
                            ->first();
        
        if ($from_m_fr != null) {
            $this->from_m_fr = floatval($from_m_fr->value);
        } else {
            $this->from_m_fr = 19.00;
        }
        if ($to_m_fr != null) {
            $this->to_m_fr = floatval($to_m_fr->value);
        } else {
            $this->to_m_fr = 23.00;
        }
    }
    
    public function index(Request $request)
    {
        if ($request->input('year-num') && $request->input('week-num')) {
            $this->year_num = $request->input('year-num');
            $this->week_num = $request->input('week-num');    
        } else {
            $this->year_num = Date("Y");
            $this->week_num = Date("W") - 1;
        }
        
        $drivers = DB::select("
                                SELECT
                                    d.*,
                                    SUM(CASE WHEN t.miles_qty BETWEEN {$this->from_m_fr} AND {$this->to_m_fr} THEN 1 ELSE 0 END) AS cnt_trips_fix_rate,
                                    SUM(CASE WHEN t.miles_qty BETWEEN {$this->from_m_fr} AND {$this->to_m_fr} THEN t.miles_qty ELSE 0 END) AS miles_fix_rate,
                                    SUM(CASE WHEN t.miles_qty BETWEEN {$this->from_m_fr} AND {$this->to_m_fr} THEN 0 ELSE t.miles_qty END) AS miles_other,
                                    SUM(t.miles_qty) AS total_miles,
                                    (SUM(CASE WHEN t.miles_qty BETWEEN {$this->from_m_fr} AND {$this->to_m_fr} THEN 1 ELSE 0 END)) / 2* d.fixed_rate AS payroll_fix_rate,
                                    (SUM(CASE WHEN t.miles_qty BETWEEN {$this->from_m_fr} AND {$this->to_m_fr} THEN 0 ELSE t.miles_qty END)) * d.price_per_mile AS payroll_per_mile,
                                    ((SUM(CASE WHEN t.miles_qty BETWEEN {$this->from_m_fr} AND {$this->to_m_fr} THEN 1 ELSE 0 END)) / 2 * d.fixed_rate + (SUM(CASE WHEN t.miles_qty BETWEEN {$this->from_m_fr} AND {$this->to_m_fr} THEN 0 ELSE t.miles_qty END)) * d.price_per_mile) AS total_payroll
                                FROM
                                    linehaul_drivers AS d
                                INNER JOIN linehaul_trips AS t ON t.driver_1 = d.driver_id
                                WHERE
                                    t.year_num = {$this->year_num} AND
                                    t.week_num = {$this->week_num}
                                GROUP BY d.driver_id
                            ");
        
        return view('payroll.payrolls', [
            'year_num'          => $this->year_num,
            'week_num'          => $this->week_num,
            'drivers'           => $drivers
        ]);
    }

    public function get_payroll(Request $request)
    {
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
        
        $payroll = collect(DB::select("
                                        SELECT
                                            d.*,
                                            SUM(CASE WHEN t.miles_qty BETWEEN {$this->from_m_fr} AND {$this->to_m_fr} THEN 1 ELSE 0 END) AS cnt_trips_fix_rate,
                                            SUM(CASE WHEN t.miles_qty BETWEEN {$this->from_m_fr} AND {$this->to_m_fr} THEN t.miles_qty ELSE 0 END) AS miles_fix_rate,
                                            SUM(CASE WHEN t.miles_qty BETWEEN {$this->from_m_fr} AND {$this->to_m_fr} THEN 0 ELSE t.miles_qty END) AS miles_other,
                                            SUM(t.miles_qty) AS total_miles,
                                            (SUM(CASE WHEN t.miles_qty BETWEEN {$this->from_m_fr} AND {$this->to_m_fr} THEN 1 ELSE 0 END)) / 2 * d.fixed_rate AS payroll_fix_rate,
                                            (SUM(CASE WHEN t.miles_qty BETWEEN {$this->from_m_fr} AND {$this->to_m_fr} THEN 0 ELSE t.miles_qty END)) * d.price_per_mile AS payroll_per_mile,
                                            ((SUM(CASE WHEN t.miles_qty BETWEEN {$this->from_m_fr} AND {$this->to_m_fr} THEN 1 ELSE 0 END)) / 2 * d.fixed_rate + (SUM(CASE WHEN t.miles_qty BETWEEN {$this->from_m_fr} AND {$this->to_m_fr} THEN 0 ELSE t.miles_qty END)) * d.price_per_mile) AS total_payroll
                                        FROM
                                            linehaul_drivers AS d
                                        INNER JOIN linehaul_trips AS t ON t.driver_1 = d.driver_id
                                        WHERE
                                            t.year_num = {$this->year_num} AND
                                            t.week_num = {$this->week_num} AND
                                            d.id = {$id}
                                        GROUP BY d.driver_id
                                    "))->first();
        return view('payroll.view', [
            'year_num'      => $this->year_num,
            'week_num'      => $this->week_num,
            'from_m_fr'     => number_format($this->from_m_fr, 2),
            'to_m_fr'       => number_format($this->to_m_fr, 2),
            'payroll'       => $payroll
        ]);
    }

    public function get_rates()
    {
        $rates = DB::table('linehaul_drivers')
                    ->get()
                    ->all();
        return view('payroll.rates', [
            'rates' => $rates
        ]);
    }

    public function get_rate(Request $request)
    {
        $id = $request->route()->parameter('id');
        $rate = DB::table('linehaul_drivers')
                    ->where('id', $id)
                    ->get()
                    ->first();
        return view('payroll.rate', [
            'rate' => $rate
        ]);
    }

    public function save_rate(Request $request)
    {
        $id             = $request->input('id');
        $fixed_rate     = $request->input('fixed-rate');
        $price_per_mile = $request->input('price-per-mile');

        $affected = DB::table('linehaul_drivers')
                        ->where('id', $id)
                        ->update([
                                'fixed_rate'        => $fixed_rate, 
                                'price_per_mile'    => $price_per_mile
                            ]);
        $request->session()->flash('status', 'Updated fixed rate and price per mile for id=' . $id . ' !');
        return redirect()->route('get-rates');
    }

    public function remove_rate(Request $request)
    {
        $id = $request->route()->parameter('id');
        $rate = DB::table('linehaul_drivers')
                    ->where('id', $id)
                    ->update([
                                'fixed_rate'        => 0, 
                                'price_per_mile'    => 0
                            ]);
        $request->session()->flash('status', 'Removed fixed rate and price per mile for id=' . $id . ' !');
        return redirect()->route('get-rates');
    }

    public function get_miles_setting()
    {
        $this->get_setting();

        return view('payroll.miles_setting', [
            'from_m_fr'     => $this->from_m_fr,
            'to_m_fr'       => $this->to_m_fr
        ]); 
    }
    
    public function save_miles_setting(Request $request)
    {
        $from_m_fr  = $request->input('from-m-fr');
        $to_m_fr    = $request->input('to-m-fr');
        
        $affected = DB::table('global_setting')
                        ->where('code', 'from_m_fr')
                        ->update([
                                'value' => $from_m_fr
                            ]);
        
        $affected = DB::table('global_setting')
                        ->where('code', 'to_m_fr')
                        ->update([
                                'value' => $to_m_fr
                            ]);

        $this->get_setting();

        $request->session()->flash('status', 'Updated miles setting for fixed rate!');
        return redirect()->route('get-rates');
    }
}
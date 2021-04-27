<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('get_data_revenue')) {
    function get_data_revenue($search) {
        $grosses            = DB::select("
                                SELECT 
                                    year_num,
                                    week_num,
                                    CONCAT('WK-', week_num, ', ', year_num) AS week_name,
                                    SUM(daily_gross_amt) AS gross
                                FROM 
                                    linehaul_trips
                                WHERE
                                    CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) <= 
                                    CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                    CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) >= 
                                    CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END))
                                GROUP BY year_num, week_num
                                ORDER BY year_num ASC, week_num ASC");
        $o_s_adjustments    = DB::select("
                                SELECT 
                                    year_num,
                                    week_num,
                                    CONCAT('WK-', week_num, ', ', year_num) AS week_name,
                                    SUM(amt) AS o_s_adjustments
                                FROM 
                                    other_settlement_adjustments
                                WHERE
                                    CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) <= 
                                    CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                    CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) >= 
                                    CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END))
                                GROUP BY year_num, week_num
                                ORDER BY year_num ASC, week_num ASC");
        $fuels              = DB::select("
                                SELECT 
                                    year_num,
                                    week_num,
                                    CONCAT('WK-', week_num, ', ', year_num) AS week_name,
                                    -SUM(auth_chgbk_net) AS fuel_cost
                                FROM 
                                    fuel_purchases
                                WHERE
                                    CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) <= 
                                    CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                    CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) >= 
                                    CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END))
                                GROUP BY year_num, week_num
                                ORDER BY year_num ASC, week_num ASC");
        $repairs            = DB::select("
                                SELECT 
                                    year_num,
                                    week_num,
                                    CONCAT('WK-', week_num, ', ', year_num) AS week_name,
                                    -SUM(repair_misc_amt) AS repair_cost
                                FROM 
                                    tractor_repairs_misc
                                WHERE
                                    CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) <= 
                                    CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
	                                CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) >= 
                                    CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END))
                                GROUP BY year_num, week_num
                                ORDER BY year_num ASC, week_num ASC");
        
        $headers = array();
                
        foreach ($grosses as $gross) {
            if (!in_array($gross->week_name, $headers)) {
                array_push($headers, $gross->week_name);
            }
            $gross->gross = round($gross->gross, 2);
        }
        foreach ($o_s_adjustments as $adjustment) {
            if (!in_array($adjustment->week_name, $headers)) {
                array_push($headers, $adjustment->week_name);
            }
            $adjustment->o_s_adjustments = round($adjustment->o_s_adjustments, 2);
        }
        foreach ($fuels as $fuel) {
            if (!in_array($fuel->week_name, $headers)) {
                array_push($headers, $fuel->week_name);
            }
            $fuel->fuel_cost = round($fuel->fuel_cost, 2);
        }
        foreach ($repairs as $repair) {
            if (!in_array($repair->week_name, $headers)) {
                array_push($headers, $repair->week_name);
            }
            $repair->repair_cost = round($repair->repair_cost, 2);
        }
        
        $revenues = array();
        foreach ($headers as $week) {
            $revenue = 0;
            foreach ($grosses as $gross) {
                if ($gross->week_name == $week) {
                    $revenue += $gross->gross;
                    break;
                }
            }
            foreach ($o_s_adjustments as $adjustment) {
                if ($adjustment->week_name == $week) {
                    $revenue += $adjustment->o_s_adjustments;
                    break;
                }
            }
            foreach ($fuels as $fuel) {
                if ($fuel->week_name == $week) {
                    $revenue -= $fuel->fuel_cost;
                    break;
                }
            }
            foreach ($repairs as $repair) {
                if ($repair->week_name == $week) {
                    $revenue -= $repair->repair_cost;
                    break;
                }
            }

            array_push($revenues, round($revenue, 2));
        }

        $total_revenue = 0;
        foreach($revenues as $revenue) {
            $total_revenue += $revenue;
        }
        
        array_push($headers, 'Total');
        array_push($revenues, $total_revenue);

        $excel_data = new \stdClass();
        $excel_data->header = $headers;
        $excel_data->data = $revenues;
        return $excel_data;
    }
}

if (!function_exists('get_data_mile_total')) {
    function get_data_mile_total($search) {
        $weeks = DB::select("
                            SELECT 
                                year_num,
                                week_num,
                                CONCAT('WK-', week_num, ', ', year_num) AS week_name,
                                SUM(miles_qty) AS total_miles
                            FROM 
                                linehaul_trips
                            WHERE
                                CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) <= 
                                CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) >= 
                                CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END))
                            GROUP BY year_num, week_num
                            ORDER BY year_num ASC, week_num ASC");
        $headers = array();
        foreach ($weeks as $week) {
            if (!in_array($week->week_name, $headers)) {
                array_push($headers, $week->week_name);
            }
        }
        
        $values = array();
        $total_miles = 0;
        foreach ($headers as $week_name) {
            $value = 0;
            foreach ($weeks as $week) {
                if ($week_name == 'WK-' . $week->week_num . ', ' . $week->year_num) {
                    $value = $week->total_miles;
                    $total_miles += $week->total_miles;
                    break;
                };
            }
            $values[] = $value;
        }
        array_push($headers, 'Total');
        array_push($values, $total_miles);
        
        $excel_data = new \stdClass();
        $excel_data->header = $headers;
        $excel_data->data = $values;
        return $excel_data;
    }
}

if (!function_exists('get_data_mile_driver')) {
    function get_data_mile_driver($search) {
        $weeks = DB::select("
                            SELECT 
                                t.year_num,
                                t.week_num,
                                t.driver_1 AS driver_id,
                                d.driver_name,
                                SUM(t.miles_qty) AS miles
                            FROM 
                                linehaul_trips AS t
                            LEFT JOIN linehaul_drivers AS d ON d.driver_id = t.driver_1
                            WHERE
                                CONCAT(t.year_num, (CASE WHEN t.week_num < 10 THEN CONCAT('0', t.week_num) ELSE t.week_num END)) <= 
                                CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                CONCAT(t.year_num, (CASE WHEN t.week_num < 10 THEN CONCAT('0', t.week_num) ELSE t.week_num END)) >= 
                                CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END))
                            GROUP BY t.year_num, t.week_num, t.driver_1
                            ORDER BY t.year_num ASC, t.week_num ASC");

        $headers = array();
        $drivers = array();
        foreach ($weeks as $week) {
            $week->week = 'WK-' . $week->week_num . ', ' . $week->year_num;

            if (!in_array($week->week, $headers)) {
                array_push($headers, $week->week);
            }

            if (!array_key_exists($week->driver_id, $drivers)) {
                $drivers[$week->driver_id] = $week->driver_name;
            }
        }
        $values = array();
        foreach ($drivers as $d_key => $d_val) {
            $temp = array();
            foreach ($headers as $h_idx => $h_val) {
                $flag = false;
                foreach ($weeks as $week) {
                    if ($week->driver_id == $d_key && $week->week == $h_val) {
                        $temp[$h_idx] = $week->miles;
                        $flag = true;
                        break;
                    }
                }
                if ($flag == false) {
                    $temp[$h_idx] = 0;
                }
            }
            $values[$d_key] = array('driver_name' => $d_val, 'miles' => $temp);
        }
        
        $excel_data = new \stdClass();
        $excel_data->header = $headers;
        $excel_data->data = $values;
        return $excel_data;
    }
}

if (!function_exists('get_data_mile_vehicle')) {
    function get_data_mile_vehicle($search) {
        $weeks = DB::select("
                            SELECT 
                                year_num,
                                week_num,
                                vehicle,
                                SUM(miles_qty) AS miles
                            FROM 
                                linehaul_trips AS t
                            WHERE
                                CONCAT(t.year_num, (CASE WHEN t.week_num < 10 THEN CONCAT('0', t.week_num) ELSE t.week_num END)) <= 
                                CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                CONCAT(t.year_num, (CASE WHEN t.week_num < 10 THEN CONCAT('0', t.week_num) ELSE t.week_num END)) >= 
                                CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END))
                            GROUP BY year_num, week_num, vehicle
                            ORDER BY year_num ASC, week_num ASC");
        $headers = array();
        $vehicles = array();
        foreach ($weeks as $week) {
            $week->week = 'WK-' . $week->week_num . ', ' . $week->year_num;

            if (!in_array($week->week, $headers)) {
                array_push($headers, $week->week);
            }

            if (!in_array($week->vehicle, $vehicles)) {
                array_push($vehicles, $week->vehicle);
            }
        }
        $values = array();
        foreach ($vehicles as $v) {
            $temp = array();
            foreach ($headers as $h_idx => $h_val) {
                $flag = false;
                foreach ($weeks as $week) {
                    if ($week->vehicle == $v && $week->week == $h_val) {
                        $temp[$h_idx] = $week->miles;
                        $flag = true;
                        break;
                    }
                }
                if ($flag == false) {
                    $temp[$h_idx] = 0;
                }
            }
            $values[$v] = $temp;
        }
        
        $excel_data = new \stdClass();
        $excel_data->header = $headers;
        $excel_data->data = $values;
        return $excel_data;
    }
}

if (!function_exists('get_data_trips_driver')) {
    function get_data_trips_driver($search) {
        $trips = DB::select("
                            SELECT 
                                t.year_num,
                                t.week_num,
                                CONCAT('WK-', t.week_num, ', ', t.year_num) AS week_name,
                                t.driver_1 AS driver_id,
                                d.driver_name,
                                COUNT(*) AS trips 
                            FROM 
                                linehaul_trips AS t 
                            INNER JOIN linehaul_drivers AS d ON d.driver_id = t.driver_1 
                            WHERE 
                                CONCAT(t.year_num, (CASE WHEN t.week_num < 10 THEN CONCAT('0', t.week_num) ELSE t.week_num END)) <= 
                                CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                CONCAT(t.year_num, (CASE WHEN t.week_num < 10 THEN CONCAT('0', t.week_num) ELSE t.week_num END)) >= 
                                CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END))
                            GROUP BY 
                                t.year_num, t.week_num, t.driver_1 
                            ORDER BY t.year_num, t.week_num, t.driver_1");
        
        $headers = array();
        $drivers = array();
        foreach ($trips as $trip) {
            if (!in_array($trip->week_name, $headers)) {
                array_push($headers, $trip->week_name);
            }

            if (!array_key_exists($trip->driver_id, $drivers)) {
                $drivers[$trip->driver_id] = $trip->driver_name;
            }
        }
        
        $values = array();
        foreach ($drivers as $d_key => $d_val) {
            $temp = array();
            foreach ($headers as $h_idx => $h_val) {
                $flag = false;
                foreach ($trips as $trip) {
                    if ($trip->driver_id == $d_key && $trip->week_name == $h_val) {
                        $temp[$h_idx] = $trip->trips;
                        $flag = true;
                        break;
                    }
                }
                if ($flag == false) {
                    $temp[$h_idx] = 0;
                }
            }
            $values[$d_key] = array('driver_name' => $d_val, 'trips' => $temp);
        }
        
        $excel_data = new \stdClass();
        $excel_data->header = $headers;
        $excel_data->data = $values;
        return $excel_data;
    }
}

if (!function_exists('get_data_mpg_vehicle')) {
    function get_data_mpg_vehicle($search) {
        $week_miles = DB::select("
                                SELECT 
                                    year_num,
                                    week_num,
                                    CONCAT('WK-', t.week_num, ', ', t.year_num) AS week_name,
                                    vehicle,
                                    SUM(miles_qty) AS miles
                                FROM 
                                    linehaul_trips AS t
                                WHERE
                                    CONCAT(t.year_num, (CASE WHEN t.week_num < 10 THEN CONCAT('0', t.week_num) ELSE t.week_num END)) <= 
                                    CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                    CONCAT(t.year_num, (CASE WHEN t.week_num < 10 THEN CONCAT('0', t.week_num) ELSE t.week_num END)) >= 
                                    CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END))
                                GROUP BY year_num, week_num, vehicle
                                ORDER BY year_num ASC, week_num ASC");
        
        $week_fuels = DB::select("
                                SELECT 
                                    year_num,
                                    week_num,
                                    CONCAT('WK-', week_num, ', ', year_num) AS week_name,
                                    vehicle,
                                    SUM(qty) AS fuel_qty
                                FROM 
                                    fuel_purchases
                                WHERE
                                    CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) <= 
                                    CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                    CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) >= 
                                    CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END))
                                GROUP BY year_num, week_num, vehicle
                                ORDER BY year_num ASC, week_num ASC");
        
        $headers = array();
        $vehicles = array();
        foreach ($week_miles as $wm) {
            if (!in_array($wm->week_name, $headers)) {
                array_push($headers, $wm->week_name);
            }

            if (!in_array($wm->vehicle, $vehicles)) {
                array_push($vehicles, $wm->vehicle);
            }
        }
        foreach ($week_fuels as $wf) {
            if (!in_array($wf->week_name, $headers)) {
                array_push($headers, $wf->week_name);
            }

            if (!in_array($wf->vehicle, $vehicles)) {
                array_push($vehicles, $wf->vehicle);
            }
        }
        
        $miles_data = array();
        foreach ($vehicles as $v) {
            $temp = array();
            foreach ($headers as $h_idx => $h_val) {
                $flag = false;
                foreach ($week_miles as $wm) {
                    if ($wm->vehicle == $v && $wm->week_name == $h_val) {
                        $temp[$h_idx] = $wm->miles;
                        $flag = true;
                        break;
                    }
                }
                if ($flag == false) {
                    $temp[$h_idx] = 0;
                }
            }
            $miles_data[$v] = $temp;
        }

        $fuels_data = array();
        foreach ($vehicles as $v) {
            $temp = array();
            foreach ($headers as $h_idx => $h_val) {
                $flag = false;
                foreach ($week_fuels as $wf) {
                    if ($wf->vehicle == $v && $wf->week_name == $h_val) {
                        $temp[$h_idx] = $wf->fuel_qty;
                        $flag = true;
                        break;
                    }
                }
                if ($flag == false) {
                    $temp[$h_idx] = 0;
                }
            }
            $fuels_data[$v] = $temp;
        }
        
        $values = array();
        foreach ($vehicles as $v) {
            $m = $miles_data[$v];
            $f = $fuels_data[$v];

            $value = array();
            foreach ($headers as $h_idx => $h_val) {
                array_push($value, ($f[$h_idx] == 0) ? 0 : round($m[$h_idx] / $f[$h_idx], 1));
            }
            $values[$v] = $value;
        }
        
        $excel_data = new \stdClass();
        $excel_data->header = $headers;
        $excel_data->data = $values;
        return $excel_data;
    }
}

if (!function_exists('get_data_fuelcost_total')) {
    function get_data_fuelcost_total($search) {
        $weeks = DB::select("
                            SELECT 
                                year_num,
                                week_num,
                                CONCAT('WK-', week_num, ', ', year_num) AS week_name,
                                SUM(pur_amt) AS cost
                            FROM 
                                fuel_purchases
                            WHERE
                                CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) <= 
                                CONCAT({$search->to_year_num}, (CASE WHEN {$search->to_week_num} < 10 THEN CONCAT('0', {$search->to_week_num}) ELSE {$search->to_week_num} END)) AND 
                                CONCAT(year_num, (CASE WHEN week_num < 10 THEN CONCAT('0', week_num) ELSE week_num END)) >= 
                                CONCAT({$search->from_year_num}, (CASE WHEN {$search->from_week_num} < 10 THEN CONCAT('0', {$search->from_week_num}) ELSE {$search->from_week_num} END))
                            GROUP BY year_num, week_num
                            ORDER BY year_num ASC, week_num ASC");

        $headers = array();
        foreach ($weeks as $week) {
            if (!in_array($week->week_name, $headers)) {
                array_push($headers, $week->week_name);
            }
        }

        $values = array();
        $total_cost = 0;
        foreach ($weeks as $week) {
            array_push($values, $week->cost);
            $total_cost += $week->cost;
        }

        array_push($headers, 'Total');
        array_push($values, $total_cost);

        $excel_data = new \stdClass();
        $excel_data->header = $headers;
        $excel_data->data = $values;
        return $excel_data;
    }
}
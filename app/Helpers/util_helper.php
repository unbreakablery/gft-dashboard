<?php

use Illuminate\Support\Facades\DB;

if (!function_exists('get_data_mile_total')) {
    function get_data_mile_total($search, $limit) {
        if ($search->week_num < $limit) {
            $limit = $search->week_num;
        }
        $weeks = DB::select("
                            SELECT 
                                year_num,
                                week_num,
                                SUM(miles_qty) AS total_miles
                            FROM 
                                linehaul_trips
                            WHERE
                                year_num = {$search->year_num} AND
                                week_num <= {$search->week_num} AND
                                week_num > {$search->week_num} - {$limit}
                            GROUP BY year_num, week_num
                            ORDER BY year_num ASC, week_num ASC
                            LIMIT {$limit}");
        $categories = array();
        for ($i = $search->week_num - $limit + 1; $i <= $search->week_num; $i++) {
            array_push($categories, 'WK-' . $i . ', ' . $search->year_num);
        }
        $values = array();
        foreach ($categories as $cat) {
            $value = 0;
            foreach ($weeks as $week) {
                if ($cat == 'WK-' . $week->week_num . ', ' . $week->year_num) {
                    $value = $week->total_miles;
                    break;
                };
            }
            $values[] = $value;
        }

        $excel_data = new \stdClass();
        $excel_data->header = $categories;
        $excel_data->data = $values;
        return $excel_data;
    }
}

if (!function_exists('get_data_mile_driver')) {
    function get_data_mile_driver($search, $limit) {
        if ($search->week_num < $limit) {
            $limit = $search->week_num;
        }
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
                                t.year_num = {$search->year_num} AND
                                t.week_num <= {$search->week_num} AND
                                t.week_num > {$search->week_num} - {$limit}
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
    function get_data_mile_vehicle($search, $limit) {
        if ($search->week_num < $limit) {
            $limit = $search->week_num;
        }
        $weeks = DB::select("
                            SELECT 
                                year_num,
                                week_num,
                                vehicle,
                                SUM(miles_qty) AS miles
                            FROM 
                                linehaul_trips AS t
                            WHERE
                                year_num = {$search->year_num} AND
                                week_num <= {$search->week_num} AND
                                week_num > {$search->week_num} - {$limit}
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

if (!function_exists('get_data_revenue')) {
    function get_data_revenue($search, $limit) {
        if ($search->week_num < $limit) {
            $limit = $search->week_num;
        }
        
        $grosses            = DB::select("
                                SELECT 
                                    year_num,
                                    week_num,
                                    SUM(daily_gross_amt) AS gross
                                FROM 
                                    linehaul_trips
                                WHERE
                                    year_num = {$search->year_num} AND
                                    week_num <= {$search->week_num} AND
                                    week_num > {$search->week_num} - {$limit}
                                GROUP BY year_num, week_num
                                ORDER BY year_num ASC, week_num ASC
                                LIMIT {$limit}");
        $o_s_adjustments    = DB::select("
                                SELECT 
                                    year_num,
                                    week_num,
                                    SUM(amt) AS o_s_adjustments
                                FROM 
                                    other_settlement_adjustments
                                WHERE
                                    year_num = {$search->year_num} AND
                                    week_num <= {$search->week_num} AND
                                    week_num > {$search->week_num} - {$limit}
                                GROUP BY year_num, week_num
                                ORDER BY year_num ASC, week_num ASC
                                LIMIT {$limit}");
        $fuels              = DB::select("
                                SELECT 
                                    year_num,
                                    week_num,
                                    -SUM(auth_chgbk_net) AS fuel_cost
                                FROM 
                                    fuel_purchases
                                WHERE
                                    year_num = {$search->year_num} AND
                                    week_num <= {$search->week_num} AND
                                    week_num > {$search->week_num} - {$limit}
                                GROUP BY year_num, week_num
                                ORDER BY year_num ASC, week_num ASC
                                LIMIT {$limit}");
        $repairs            = DB::select("
                                SELECT 
                                    year_num,
                                    week_num,
                                    -SUM(repair_misc_amt) AS repair_cost
                                FROM 
                                    tractor_repairs_misc
                                WHERE
                                    year_num = {$search->year_num} AND
                                    week_num <= {$search->week_num} AND
                                    week_num > {$search->week_num} - {$limit}
                                GROUP BY year_num, week_num
                                ORDER BY year_num ASC, week_num ASC
                                LIMIT {$limit}");
        $weeks = array();
        for ($i = $search->week_num - $limit + 1; $i <= $search->week_num; $i++) {
            array_push($weeks, 'WK-' . $i . ', ' . $search->year_num);
        }
        
        foreach ($grosses as $gross) {
            $gross->week = 'WK-' . $gross->week_num . ', ' . $gross->year_num;
            $gross->gross = round($gross->gross, 2);
        }
        foreach ($o_s_adjustments as $adjustment) {
            $adjustment->week = 'WK-' . $adjustment->week_num . ', ' . $adjustment->year_num;
            $adjustment->o_s_adjustments = round($adjustment->o_s_adjustments, 2);
        }
        foreach ($fuels as $fuel) {
            $fuel->week = 'WK-' . $fuel->week_num . ', ' . $fuel->year_num;
            $fuel->fuel_cost = round($fuel->fuel_cost, 2);
        }
        foreach ($repairs as $repair) {
            $repair->week = 'WK-' . $repair->week_num . ', ' . $repair->year_num;
            $repair->repair_cost = round($repair->repair_cost, 2);
        }
        
        $revenues = array();
        foreach ($weeks as $week) {
            $revenue = 0;
            foreach ($grosses as $gross) {
                if ($gross->week == $week) {
                    $revenue += $gross->gross;
                    break;
                }
            }
            foreach ($o_s_adjustments as $adjustment) {
                if ($adjustment->week == $week) {
                    $revenue += $adjustment->o_s_adjustments;
                    break;
                }
            }
            foreach ($fuels as $fuel) {
                if ($fuel->week == $week) {
                    $revenue -= $fuel->fuel_cost;
                    break;
                }
            }
            foreach ($repairs as $repair) {
                if ($repair->week == $week) {
                    $revenue -= $repair->repair_cost;
                    break;
                }
            }

            array_push($revenues, round($revenue, 2));
        }

        $ytd_revenue = DB::select("
                            SELECT 
                                {$search->year_num} as year_num,
                                (gross + o_s_adjustments - fuel_cost - repair_cost) AS revenue
                            FROM
                                (SELECT 
                                    (SELECT 
                                        IFNULL(SUM(t.daily_gross_amt), 0)
                                    FROM linehaul_trips AS t
                                    WHERE YEAR(t.date) = {$search->year_num}) AS gross,
                                    (SELECT 
                                        IFNULL(SUM(t.amt), 0)
                                    FROM other_settlement_adjustments AS t
                                    WHERE YEAR(t.date) = {$search->year_num}) AS o_s_adjustments,
                                    (SELECT 
                                        IFNULL(-SUM(t.auth_chgbk_net), 0)
                                    FROM fuel_purchases AS t
                                    WHERE YEAR(t.date) = {$search->year_num}) AS fuel_cost,
                                    (SELECT 
                                        IFNULL(-SUM(t.repair_misc_amt), 0)
                                    FROM tractor_repairs_misc AS t
                                    WHERE YEAR(t.date) = {$search->year_num}) AS repair_cost) AS t");
        
        array_push($weeks, 'YTD');
        array_push($revenues, (count($ytd_revenue) != 1) ? 0 : round($ytd_revenue[0]->revenue, 2));

        $excel_data = new \stdClass();
        $excel_data->header = $weeks;
        $excel_data->data = $revenues;
        return $excel_data;
    }
}

if (!function_exists('get_data_trips_driver')) {
    function get_data_trips_driver($search, $limit) {
        if ($search->week_num < $limit) {
            $limit = $search->week_num;
        }

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
                                t.year_num = {$search->year_num} AND 
                                t.week_num <= {$search->week_num} AND 
                                t.week_num > {$search->week_num} - {$limit}
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
    function get_data_mpg_vehicle($search, $limit) {
        if ($search->week_num < $limit) {
            $limit = $search->week_num;
        }

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
                                    year_num = {$search->year_num} AND
                                    week_num <= {$search->week_num} AND
                                    week_num > {$search->week_num} - {$limit}
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
                                    year_num = {$search->year_num} AND
                                    week_num <= {$search->week_num} AND
                                    week_num > {$search->week_num} - {$limit}
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
    function get_data_fuelcost_total($search, $limit) {
        if ($search->week_num < $limit) {
            $limit = $search->week_num;
        }
        
        $weeks = DB::select("
                            SELECT 
                                year_num,
                                week_num,
                                CONCAT('WK-', week_num, ', ', year_num) AS week_name,
                                SUM(pur_amt) AS cost
                            FROM 
                                fuel_purchases
                            WHERE
                                year_num = {$search->year_num} AND
                                week_num <= {$search->week_num} AND
                                week_num > {$search->week_num} - {$limit}
                            GROUP BY year_num, week_num
                            ORDER BY year_num ASC, week_num ASC");

        $headers = array();
        foreach ($weeks as $week) {
            if (!in_array($week->week_name, $headers)) {
                array_push($headers, $week->week_name);
            }
        }

        // dd($headers);
        // dd($weeks);

        $excel_data = new \stdClass();
        $excel_data->header = $headers;
        $excel_data->data = $weeks;
        return $excel_data;
    }
}
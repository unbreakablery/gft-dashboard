<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\External_Links;

use App\Imports\ExternalLinksImport;

use App\Exports\MileTotalExport;
use App\Exports\MileDriverExport;
use App\Exports\MileVehicleExport;
use App\Exports\RevenueExport;
use App\Exports\TripsDriverExport;
use App\Exports\MpgVehicleExport;
use App\Exports\FuelcostTotalExport;

use Maatwebsite\Excel\Facades\Excel;

use DB;
use stdClass;

class UtilController extends Controller
{   
    //last weeks to show on charts
    private $limit = 6;

    public function get_ext_links() {
        $ext_links = External_Links::all();
        return view('util.ext_links.list', [
            'ext_links' => $ext_links
        ]);
    }
    public function get_ext_link(Request $request) {
        $id = $request->input('id');
        $links = External_Links::where([
                                        'id' => $id
                                    ])
                                ->get();
                            
        if ($links == null || count($links) != 1) {
            return response()->json([
                'type' => 'failed',
                'message' => "Can't find the link info by id = {$id}"
            ]);
        } else {
            $link = $links[0];
            $link->description = nl2br(e($link->description));
            return response()->json([
                'type' => 'success',
                'link' => $links[0]
            ]);
        }
    }
    public function save_ext_link(Request $request) {
        $link = new stdClass();
        $link->id           = $request->input('id');
        $link->name         = $request->input('name');
        $link->url          = str_replace(" ", "", $request->input('url'));
        $link->description  = $request->input('description');
        
        if ($link->id) {
            // update
            External_Links::where([
                                'id' => $link->id
                            ])
                    ->update([
                        'name'          => $link->name,
                        'url'           => $link->url,
                        'description'   => $link->description
                    ]);
                $request->session()->flash('success', 'Was updated successfully ! (Link #: ' . $link->id . ')');
            return redirect('util/ext-links');
        } else {
            // insert
            $links = External_Links::where([
                                            'url' => $link->url
                                        ])
                                    ->get();

            if (count($links) > 0) {
                $request->session()->flash('error', 'Does exist ebsite page with the same link already ! (URL: ' . $link->url . ')');
                return view('util.ext_links.link', [
                    'link' => $link
                ]);
            } else {
                $link_id = External_Links::insertGetId([
                    'name'          => $link->name,
                    'url'           => $link->url,
                    'description'   => $link->description
                ]);
                $request->session()->flash('success', 'Was added successfully ! (Link #: ' . $link_id . ')');
                return redirect('util/ext-links');
            }
        }
    }
    public function edit_ext_link(Request $request) {
        $id = $request->route()->parameter('id');
        $links = External_Links::where([
                                        'id' => $id
                                    ])
                                ->get();
        if ($links == null || count($links) != 1) {
            return response()->json([
                'type' => 'failed',
                'message' => "Can't find the link info by id = {$id}"
            ]);
        } else {
            $link = $links[0];
            return view('util.ext_links.link', [
                'link' => $link
            ]);
        }
    }
    public function remove_ext_link(Request $request) {
        $id = $request->route()->parameter('id');
        External_Links::where([
                                'id' => $id
                            ])
                        ->delete();
        $request->session()->flash('success', 'Was removed successfully ! (Link #: ' . $id . ')');
        return redirect('util/ext-links');
    }
    public function truncate_ext_links(Request $request) {
        External_Links::truncate();
        $request->session()->flash('success', 'Was truncated successfully ! (All Links)');
        return redirect('util/ext-links');
    }
    public function upload_ext_links(Request $request) {
        $file = $request->file('upload-file');
        
        if ($file) {
            $file_name = $file->getClientOriginalName();

            External_Links::truncate();
            $Imports = new ExternalLinksImport();
            $ts = Excel::import($Imports, $file);

            $request->session()->flash('success', 'External links were imported from <b>' . $file_name . '</b> successfully !');
        } else {
            $request->session()->flash('error', 'Please choose a file to submit.');
        }
        return redirect('util/ext-links');
    }
    public function search_download_data(Request $request) {
        $search = new stdClass();
        $search->year_num       = $request->input('year-num');
        $search->week_num       = $request->input('week-num');
        $search->key_metric     = $request->input('key-metric');
        
        switch ($search->key_metric) {
            case 'miles-total':
                $excel_data = get_data_mile_total($search, $this->limit);
                break;
            case 'miles-driver':
                $excel_data = get_data_mile_driver($search, $this->limit);
                break;
            case 'miles-vehicle':
                $excel_data = get_data_mile_vehicle($search, $this->limit);
                break;
            case 'trips-driver':
                $excel_data = get_data_trips_driver($search, $this->limit);
                break;
            case 'mpg-vehicle':
                $excel_data = get_data_mpg_vehicle($search, $this->limit);
                break;
            case 'revenue':
                $excel_data = get_data_revenue($search, $this->limit);
                break;
            case 'fuelcost-total':
                $excel_data = get_data_fuelcost_total($search, $this->limit);
                break;
            default:
                $request->session()->flash('error', 'Invalid Key Metric!');
                return redirect('util.download-data');
        }
        return view('util.download_data.data', [
            'year_num'      => $search->year_num,
            'week_num'      => $search->week_num,
            'key_metric'    => $search->key_metric,
            'headers'       => $excel_data->header,
            'values'        => $excel_data->data,
        ]);
    }
    public function download_data(Request $request)
    {
        $search = new stdClass();
        $search->year_num       = $request->input('year-num');
        $search->week_num       = $request->input('week-num');
        $search->key_metric     = $request->input('key-metric');

        switch ($search->key_metric) {
            case 'miles-total':
                $exports = new MileTotalExport($search, $this->limit);
                return Excel::download($exports, 'Total Miles_WK' . $search->week_num . '-' . $search->year_num . '.xlsx');
                break;
            case 'miles-driver':
                $exports = new MileDriverExport($search, $this->limit);
                return Excel::download($exports, 'Miles By Driver_WK' . $search->week_num . '-' . $search->year_num . '.xlsx');
                break;
            case 'miles-vehicle':
                $exports = new MileVehicleExport($search, $this->limit);
                return Excel::download($exports, 'Miles By Vehicle_WK' . $search->week_num . '-' . $search->year_num . '.xlsx');
                break;
            case 'trips-driver':
                $exports = new TripsDriverExport($search, $this->limit);
                return Excel::download($exports, 'Trips By Driver_WK' . $search->week_num . '-' . $search->year_num . '.xlsx');
                break;
            case 'mpg-vehicle':
                $exports = new MpgVehicleExport($search, $this->limit);
                return Excel::download($exports, 'MPG By Vehicle_WK' . $search->week_num . '-' . $search->year_num . '.xlsx');
                break;
            case 'revenue':
                $exports = new RevenueExport($search, $this->limit);
                return Excel::download($exports, 'Revenue_WK' . $search->week_num . '-' . $search->year_num . '.xlsx');
                break;
            case 'fuelcost-total':
                $exports = new FuelcostTotalExport($search, $this->limit);
                return Excel::download($exports, 'Fuel Cost Total_WK' . $search->week_num . '-' . $search->year_num . '.xlsx');
                break;
            default:
                $request->session()->flash('error', 'Invalid Key Metric!');
                return redirect('util.download-data');
        }
    }
}

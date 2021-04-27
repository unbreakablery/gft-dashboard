<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\External_Links;

use App\Imports\ExternalLinksImport;

use App\Exports\HistoricalDataExport;

use Maatwebsite\Excel\Facades\Excel;

use DB;
use stdClass;

class UtilController extends Controller
{   
    //last weeks to show on charts
    private $limit = 6;

    public function get_ext_links() 
    {
        $ext_links = External_Links::all();
        return view('util.ext_links.list', [
            'ext_links' => $ext_links
        ]);
    }
    public function get_ext_link(Request $request)
    {
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
    public function save_ext_link(Request $request)
    {
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
    public function edit_ext_link(Request $request)
    {
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
    public function remove_ext_link(Request $request)
    {
        $id = $request->route()->parameter('id');
        External_Links::where([
                                'id' => $id
                            ])
                        ->delete();
        $request->session()->flash('success', 'Was removed successfully ! (Link #: ' . $id . ')');
        return redirect('util/ext-links');
    }
    public function truncate_ext_links(Request $request)
    {
        External_Links::truncate();
        $request->session()->flash('success', 'Was truncated successfully ! (All Links)');
        return redirect('util/ext-links');
    }
    public function upload_ext_links(Request $request)
    {
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
    public function download_data(Request $request)
    {
        $search = new stdClass();
        $search->from_year_num  = $request->input('from-year-num');
        $search->from_week_num  = $request->input('from-week-num');
        $search->to_year_num    = $request->input('to-year-num');
        $search->to_week_num    = $request->input('to-week-num');
        $search->key_metrics    = $request->input('key-metrics');
        
        $exports = new HistoricalDataExport($search);
        return Excel::download($exports, 'HD_WK' . $search->from_week_num . '-' . $search->from_year_num 
                                        . '_WK' . $search->to_week_num . '-' . $search->to_year_num . '.xlsx');
    }
}

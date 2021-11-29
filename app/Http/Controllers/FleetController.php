<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Fleet;
use App\Models\SignImage;
use DB;
use DateTime;
use Redirect;

use App\Imports\FleetsImport;
use Maatwebsite\Excel\Facades\Excel;

class FleetController extends Controller
{
    public function getFleets(Request $request) {
        $tractor_id = $request->input('tractor-id') ?? '';
        $model = $request->input('model') ?? '';
        $service_provider = $request->input('service_provider') ?? '';

        $fleets = DB::select("
                                SELECT
                                    f.*,
                                    DATE_FORMAT(f.bit, '%M %e, %Y') AS bit_date,
                                    (CASE
                                        WHEN f.bit <= CURDATE() AND CURDATE() <= DATE_ADD(f.bit, INTERVAL 60 DAY) THEN 'text-warning' 
                                        WHEN f.bit <= CURDATE() AND DATE_ADD(f.bit, INTERVAL 60 DAY) < CURDATE() AND CURDATE() <= DATE_ADD(f.bit, INTERVAL 80 DAY) THEN 'text-danger' 
                                        ELSE ''
                                    END) AS bit_color
                                FROM fleets AS f
                                WHERE f.tractor_id LIKE '%" . $tractor_id . "%' AND 
                                        f.model LIKE '%" . $model . "%' AND 
                                        f.service_provider LIKE '%" . $service_provider . "%'");

        return view('fleets.list', compact('fleets', 'tractor_id', 'model', 'service_provider'));
    }

    public function getFleet(Request $request) {
        $id = $request->input('id');
        $fleets = DB::select("
                                SELECT
                                    f.*,
                                    DATE_FORMAT(f.bit, '%M %e, %Y') AS bit_date,
                                    (CASE
                                        WHEN f.bit <= CURDATE() AND CURDATE() <= DATE_ADD(f.bit, INTERVAL 60 DAY) THEN 'text-warning' 
                                        WHEN f.bit <= CURDATE() AND DATE_ADD(f.bit, INTERVAL 60 DAY) < CURDATE() AND CURDATE() <= DATE_ADD(f.bit, INTERVAL 80 DAY) THEN 'text-danger' 
                                        ELSE ''
                                    END) AS bit_color
                                FROM fleets AS f
                                WHERE f.id = {$id}");
        if ($fleets == null || count($fleets) != 1) {
            return response()->json([
                'type'      => 'failed',
                'message'   => "Can't find the fleet info by id = {$id}"
            ]);
        } else {
            $fleet = $fleets[0];
            return response()->json([
                'type'      => 'success',
                'fleet'     => $fleet
            ]);
        }
    }

    public function saveFleet(Request $request) {
        $id = $request->input('id');
        $existed = Fleet::where('tractor_id', $request->input('tractor_id'))->get();

        if ($id) {
            $fleet = Fleet::find($id);

            if (count($existed) > 0 && $existed[0]->id != $id) {
                $request->session()->flash('error', 'Does exist Tractor # already ! (Tractor #: ' . $request->input('tractor_id') . ')');
                return Redirect::back()->withInput();
            }

            $fleet->tractor_id          = $request->input('tractor_id');
            $fleet->model               = $request->input('model');
            $fleet->vin                 = $request->input('vin');
            $fleet->year                = $request->input('year');
            $fleet->license_plate       = $request->input('license_plate');
            $fleet->t_check             = $request->input('t_check');
            $fleet->pre_pass            = $request->input('pre_pass');
            $fleet->service_provider    = $request->input('service_provider');
            $fleet->qiv                 = $request->input('qiv');
            $fleet->bit                 = $request->input('bit');
            $fleet->domicile            = $request->input('domicile');
            $fleet->domicile_email      = $request->input('domicile_email');
            $fleet->book_value          = ($request->input('book_value')) ? $request->input('book_value') : 0;
            $fleet->vedr                = $request->input('vedr');
            $fleet->eld                 = $request->input('eld');

            $fleet->save();

            $request->session()->flash('success', 'Was updated successfully ! (Tractor #: ' . $fleet->tractor_id . ')');
            return redirect('fleet/list');
        } else {
            if (count($existed) > 0) {
                $request->session()->flash('error', 'Does exist Tractor # already ! (Tractor #: ' . $request->input('tractor_id') . ')');
                return Redirect::back()->withInput();
            }

            $fleet = new Fleet();
            $fleet->tractor_id          = $request->input('tractor_id');
            $fleet->model               = $request->input('model');
            $fleet->vin                 = $request->input('vin');
            $fleet->year                = $request->input('year');
            $fleet->license_plate       = $request->input('license_plate');
            $fleet->t_check             = $request->input('t_check');
            $fleet->pre_pass            = $request->input('pre_pass');
            $fleet->service_provider    = $request->input('service_provider');
            $fleet->qiv                 = $request->input('qiv');
            $fleet->bit                 = $request->input('bit');
            $fleet->domicile            = $request->input('domicile');
            $fleet->domicile_email      = $request->input('domicile_email');
            $fleet->book_value          = ($request->input('book_value')) ? $request->input('book_value') : 0;
            $fleet->vedr                = $request->input('vedr');
            $fleet->eld                 = $request->input('eld');

            $fleet->save();

            $request->session()->flash('success', 'Was saved successfully ! (Tractor #: ' . $fleet->tractor_id . ')');
            return redirect('fleet/list');
        }
    }

    public function editFleet(Request $request) {
        $id = $request->route()->parameter('id');
        $fleets = DB::select("
                                SELECT
                                    f.*,
                                    DATE_FORMAT(f.bit, '%M %e, %Y') AS bit_date,
                                    (CASE
                                        WHEN f.bit <= CURDATE() AND CURDATE() <= DATE_ADD(f.bit, INTERVAL 60 DAY) THEN 'text-warning' 
                                        WHEN f.bit <= CURDATE() AND DATE_ADD(f.bit, INTERVAL 60 DAY) < CURDATE() AND CURDATE() <= DATE_ADD(f.bit, INTERVAL 80 DAY) THEN 'text-danger' 
                                        ELSE ''
                                    END) AS bit_color
                                FROM fleets AS f
                                WHERE f.id = {$id}");
        if ($fleets == null || count($fleets) != 1) {
            return response()->json([
                'type'      => 'failed',
                'message'   => "Can't find the tractor info by id = {$id}"
            ]);
        } else {
            $fleet = $fleets[0];
            return view('fleets.fleet', [
                'fleet' => $fleet
            ]);
        }
    }

    public function removeFleet(Request $request) {
        $id = $request->route()->parameter('id');
        
        $res = Fleet::find($id)->delete();
        if ($res) {
            $request->session()->flash('success', 'Fleet removed successfully. (ID: ' . $id . ')');
        } else {
            $request->session()->flash('error', 'Can\'t remove this fleet at this time. (ID: ' . $id . ') Please retry later.');
        }
        return redirect('/fleet/list');
    }

    public function uploadFleets(Request $request)
    {
        $file = $request->file('upload-file');
        
        if ($file) {
            $file_name = $file->getClientOriginalName();

            $Imports = new FleetsImport();
            $ts = Excel::import($Imports, $file);

            $request->session()->flash('success', 'Fleets were imported from <strong>' . $file_name . '</strong> successfully!');
        } else {
            $request->session()->flash('error', 'Please choose a file to submit.');
        }
        return redirect('fleet/list');
    }

    public function mmrIndex()
    {
        $fleets = Fleet::get()->all();
        $signs = SignImage::get()->all();

        return view('fleets.mmr', compact('fleets', 'signs'));
    }

    public function uploadSigns(Request $request)
    {
        $files = "";

        if($request->hasfile('upload-files')) {
            foreach($request->file('upload-files') as $file) {
                $name = $file->getClientOriginalName();
                $ext = $file->getClientOriginalExtension();
                $save_name = pathinfo($name, PATHINFO_FILENAME);

                $file->move(public_path('media/photos/signs'), $name);
                $files .= $name . ", ";

                $signs = SignImage::where('name', $save_name)->get()->all();

                if (!$signs) {
                    $sign = new SignImage();
                } else {
                    $sign = $signs[0];
                }

                $sign->name = $save_name;
                $sign->extension = $ext;
                $sign->save();
            }
            $request->session()->flash('success', 'Uploaded Sign Image Files: <strong>' . implode(', ', explode(', ', $files, -1)) . '</strong>');
        } else {
            $request->session()->flash('error', 'Please choose sign image files!');
        }

        return redirect('mmr');
    }
}
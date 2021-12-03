<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Linehaul_Drivers;

use App\Imports\DriversImport;
use Maatwebsite\Excel\Facades\Excel;

class DriverController extends Controller
{
    public function getDrivers(Request $request)
    {
        $status = ['active' => 1, 'inactive' => 0];
        $work_status = $request->route()->parameter('status');
        
        if (!isset($status[$work_status]) || $status[$work_status] === null) {
            $drivers = Linehaul_Drivers::all();
        } else {
            $drivers = Linehaul_Drivers::where('work_status', $status[$work_status])
                                    ->get()
                                    ->all();
        }
        
        return view('drivers.list', compact('drivers'));
    }
    public function getDriver(Request $request)
    {
        $id = $request->input('id');
        $driver = Linehaul_Drivers::find($id);
        if (!$driver) {
            return response()->json([
                'type' => 'failed',
                'message' => "Can't find the driver info by id = {$id}"
            ]);
        } else {
            return response()->json([
                'type' => 'success',
                'driver' => $driver
            ]);
        }
    }
    public function editDriver(Request $request)
    {
        $id = $request->route()->parameter('id');
        $driver = Linehaul_Drivers::find($id);
        if (!$driver) {
            return response()->json([
                'type' => 'failed',
                'message' => "Can't find the driver info by id = {$id}"
            ]);
        } else {
            return view('drivers.edit', [
                'driver' => $driver
            ]);
        }
    }
    public function removeDriver(Request $request)
    {
        $id = $request->route()->parameter('id');
        $res = Linehaul_Drivers::find($id)->delete();

        if ($res) {
            $request->session()->flash('success', 'Driver removed successfully. (Id: ' . $id . ')');
        } else {
            $request->session()->flash('error', 'Can\'t remove this driver at this time. (Id: ' . $id . ') Please retry later.');
        }
        return redirect('drivers');
    }
    public function saveDriver(Request $request)
    {
        $id = $request->input('id');

        if ($id) {
            $driver = Linehaul_Drivers::find($id);
        } else {
            $driver = new Linehaul_Drivers();
        }

        $driver->driver_id          = $request->input('driver_id');
        $driver->driver_name        = $request->input('driver_name');
        $driver->phone              = $request->input('phone');
        $driver->license            = $request->input('license');
        $driver->address            = $request->input('address');
        $driver->fixed_rate         = $request->input('fixed_rate');
        $driver->price_per_mile     = $request->input('price_per_mile');
        $driver->work_status        = $request->input('work_status');

        $existed = Linehaul_Drivers::where('driver_id', $request->input('driver_id'))
                                    ->get()
                                    ->all();

        if (!$existed || $id == $existed[0]->id) {
            // save driver info
            $driver->save();
            $request->session()->flash('success', 'Driver info was saved successfully. (ID: ' . $driver->driver_id . ')');
            return redirect('drivers');
        } else {
            // in case exists the driver with the same driver id
            $request->session()->flash('error', 'Exists already the driver with the same driver ID (' . $request->input('driver_id') . ')');
            return view('drivers.edit', compact('driver', $driver));
        }        
    }
    public function removeBulkDrivers(Request $request)
    {
        $checkedDrivers = $request->input('checked-drivers');
        $res = Linehaul_Drivers::destroy($checkedDrivers);
        
        if ($res) {
            $request->session()->flash('success', 'Drivers(#' . implode(',', $checkedDrivers) .') were removed successfully.');
        } else {
            $request->session()->flash('error', 'Can\'t remove these drivers at this time. Please retry later.');
        }
        return redirect('drivers');
    }
    public function uploadDrivers(Request $request)
    {
        $file = $request->file('upload-file');
        
        if ($file) {
            $file_name = $file->getClientOriginalName();

            $Imports = new DriversImport();
            $ts = Excel::import($Imports, $file);

            $request->session()->flash('success', 'Drivers were imported from <strong>' . $file_name . '</strong> successfully !');
        } else {
            $request->session()->flash('error', 'Please choose a file to submit.');
        }
        return redirect('drivers');
    }
}

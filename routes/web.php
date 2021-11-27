<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UploadDataController;
use App\Http\Controllers\ChartDataController;
use App\Http\Controllers\ScorecardsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FleetController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\UtilController;
use App\Http\Controllers\WeeklyScheduleController;
use App\Http\Controllers\DriverController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect('login');
    //return view('chart.total_revenue_week');
});

// Route::middleware(['auth:sanctum', 'verified'])->get('/dashboard', function () {
//     return view('dashboard');
// })->name('dashboard');

//Route::get('upload', [UploadDataController::class, 'index'])->name('upload');
Route::middleware(['auth:sanctum', 'verified'])->get('user/setting', [UserController::class, 'index'], function () {
    return route('login');
})->name('user-setting');
Route::middleware(['auth:sanctum', 'verified'])->post('user/setting', [UserController::class, 'update'], function () {
    return route('login');
})->name('user-setting');

Route::middleware(['auth:sanctum', 'verified'])->get('upload/{type}', [UploadDataController::class, 'index'], function () {
    return route('login');
})->name('upload');

Route::middleware(['auth:sanctum', 'verified'])->post('upload/statement', [UploadDataController::class, 'upload_statement'], function () {
    return route('login');
})->name('upload-statement');

Route::middleware(['auth:sanctum', 'verified'])->post('upload/photo', [UploadDataController::class, 'upload_photo'], function () {
    return route('login');
})->name('upload-photo');

Route::middleware(['auth:sanctum', 'verified'])->post('upload/scorecards', [UploadDataController::class, 'upload_scorecards'], function () {
    return route('login');
})->name('upload-scorecards');

Route::middleware(['auth:sanctum', 'verified'])->post('upload/check-st', [UploadDataController::class, 'check_st'], function () {
    return route('login');
});

Route::middleware(['auth:sanctum', 'verified'])->get('chart/total-miles-week', [ChartDataController::class, 'total_miles_week'], function () {
    return route('login');
})->name('total-miles-week');

Route::middleware(['auth:sanctum', 'verified'])->post('chart/total-miles-week', [ChartDataController::class, 'total_miles_week'], function () {
    return route('login');
});

Route::middleware(['auth:sanctum', 'verified'])->get('chart/miles-week-driver', [ChartDataController::class, 'miles_week_driver'], function () {
    return route('login');
})->name('miles-week-driver');

Route::middleware(['auth:sanctum', 'verified'])->post('chart/miles-week-driver', [ChartDataController::class, 'miles_week_driver'], function () {
    return route('login');
});

Route::middleware(['auth:sanctum', 'verified'])->get('chart/miles-week-vehicle', [ChartDataController::class, 'miles_week_vehicle'], function () {
    return route('login');
})->name('miles-week-vehicle');

Route::middleware(['auth:sanctum', 'verified'])->post('chart/miles-week-vehicle', [ChartDataController::class, 'miles_week_vehicle'], function () {
    return route('login');
});

Route::middleware(['auth:sanctum', 'verified'])->get('chart/mpg-week-vehicle', [ChartDataController::class, 'mpg_week_vehicle'], function () {
    return route('login');
})->name('mpg-week-vehicle');

Route::middleware(['auth:sanctum', 'verified'])->post('chart/mpg-week-vehicle', [ChartDataController::class, 'mpg_week_vehicle'], function () {
    return route('login');
});

Route::middleware(['auth:sanctum', 'verified'])->get('chart/total-fuelcost-week', [ChartDataController::class, 'total_fuelcost_week'], function () {
    return route('login');
})->name('total-fuelcost-week');

Route::middleware(['auth:sanctum', 'verified'])->post('chart/total-fuelcost-week', [ChartDataController::class, 'total_fuelcost_week'], function () {
    return route('login');
});

Route::middleware(['auth:sanctum', 'verified'])->get('chart/fuelcost-week-vehicle', [ChartDataController::class, 'fuelcost_week_vehicle'], function () {
    return route('login');
})->name('fuelcost-week-vehicle');

Route::middleware(['auth:sanctum', 'verified'])->post('chart/fuelcost-week-vehicle', [ChartDataController::class, 'fuelcost_week_vehicle'], function () {
    return route('login');
});

Route::middleware(['auth:sanctum', 'verified'])->get('chart/total-revenue-week', [ChartDataController::class, 'total_revenue_week'], function () {
    return route('login');
})->name('dashboard');

Route::middleware(['auth:sanctum', 'verified'])->post('chart/total-revenue-week', [ChartDataController::class, 'total_revenue_week'], function () {
    return route('login');
});

Route::middleware(['auth:sanctum', 'verified'])->get('scorecards', [ScorecardsController::class, 'get_persons'], function () {
    return route('login');
})->name('persons');

Route::middleware(['auth:sanctum', 'verified'])->get('scorecards/{id}', [ScorecardsController::class, 'get_scorecard'], function () {
    return route('login');
})->name('scorecard');

Route::middleware(['auth:sanctum', 'verified'])->post('scorecards/send-email', [ScorecardsController::class, 'send_email'], function () {
    return route('login');
})->name('send_email');

Route::middleware(['auth:sanctum', 'verified'])->get('scorecards/person/remove/{id}', [ScorecardsController::class, 'remove_person'], function () {
    return route('login');
})->name('remove-person');

// Fleet
Route::middleware(['auth:sanctum', 'verified'])->get('fleet/list', [FleetController::class, 'getFleets'], function () {
    return route('login');
})->name('fleets');
Route::middleware(['auth:sanctum', 'verified'])->post('fleet/list', [FleetController::class, 'getFleets'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->get('fleet/edit/{id}', [FleetController::class, 'editFleet'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->get('fleet/remove/{id}', [FleetController::class, 'removeFleet'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->post('fleet/get', [FleetController::class, 'getFleet'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->get('fleet/add', function () {
    return view('fleets.fleet');
});
Route::middleware(['auth:sanctum', 'verified'])->post('fleet/save', [FleetController::class, 'saveFleet'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->post('fleet/upload', [FleetController::class, 'uploadFleets'], function () {
    return route('login');
});

// MMR
Route::middleware(['auth:sanctum', 'verified'])->get('mmr', [FleetController::class, 'mmrIndex'], function () {
    return route('login');
})->name('mmr');
Route::middleware(['auth:sanctum', 'verified'])->post('mmr/upload-signs', [FleetController::class, 'uploadSigns'], function () {
    return route('login');
})->name('mmr-upload-signs');

// Payroll
Route::middleware(['auth:sanctum', 'verified'])->get('payroll', [PayrollController::class, 'index'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->post('payroll', [PayrollController::class, 'index'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->get('payroll/get/{id}/{year}/{week}', [PayrollController::class, 'get_payroll'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->get('payroll/rates', [PayrollController::class, 'get_rates'], function () {
    return route('login');
})->name('get-rates');
Route::middleware(['auth:sanctum', 'verified'])->post('payroll/rate/save', [PayrollController::class, 'save_rate'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->get('payroll/rate/{id}', [PayrollController::class, 'get_rate'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->get('payroll/rate/remove/{id}', [PayrollController::class, 'remove_rate'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->get('payroll/miles-setting', [PayrollController::class, 'get_miles_setting'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->post('payroll/miles-setting/save', [PayrollController::class, 'save_miles_setting'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->get('payroll/work-status/save/{id}', [PayrollController::class, 'save_workstatus'], function () {
    return route('login');
})->name('payroll-workstatus-save');

// Utils
Route::middleware(['auth:sanctum', 'verified'])->get('util/ext-links', [UtilController::class, 'get_ext_links'], function () {
    return route('login');
})->name('util.ext-links');
Route::middleware(['auth:sanctum', 'verified'])->post('util/ext-links/get', [UtilController::class, 'get_ext_link'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->get('util/ext-links/add', function () {
    return view('util.ext_links.link');
});
Route::middleware(['auth:sanctum', 'verified'])->post('util/ext-links/save', [UtilController::class, 'save_ext_link'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->get('util/ext-links/edit/{id}', [UtilController::class, 'edit_ext_link'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->get('util/ext-links/remove/{id}', [UtilController::class, 'remove_ext_link'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->get('util/ext-links/truncate', [UtilController::class, 'truncate_ext_links'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->post('util/ext-links/upload', [UtilController::class, 'upload_ext_links'], function () {
    return route('login');
});

Route::middleware(['auth:sanctum', 'verified'])->get('util/download-data', function () {
    return view('util.download_data.search_form');
})->name('util.download-data');
Route::middleware(['auth:sanctum', 'verified'])->post('util/download-data/download', [UtilController::class, 'download_data'], function () {
    return route('login');
})->name('util.download-data.download');
Route::middleware(['auth:sanctum', 'verified'])->post('util/download-data/view', [UtilController::class, 'view_data'], function () {
    return route('login');
})->name('util.download-data.view');

// routes for weekly schedule
Route::middleware(['auth:sanctum', 'verified'])->get('schedule/upload', [WeeklyScheduleController::class, 'index'], function () {
    return route('login');
})->name('ws');
Route::middleware(['auth:sanctum', 'verified'])->post('schedule/upload', [WeeklyScheduleController::class, 'upload'], function () {
    return route('login');
})->name('ws.upload');
Route::middleware(['auth:sanctum', 'verified'])->get('schedule/search', [WeeklyScheduleController::class, 'search'], function () {
    return route('login');
})->name('ws.search_form');
Route::middleware(['auth:sanctum', 'verified'])->post('schedule/search', [WeeklyScheduleController::class, 'search'], function () {
    return route('login');
})->name('ws.search');
Route::middleware(['auth:sanctum', 'verified'])->get('schedule/get/{s_id}', [WeeklyScheduleController::class, 'getSchedule'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->post('schedule/get', [WeeklyScheduleController::class, 'getSchedulePost'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->get('schedule/edit/{id}', [WeeklyScheduleController::class, 'editSchedule'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->get('schedule/add', [WeeklyScheduleController::class, 'addSchedule'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->post('schedule/save', [WeeklyScheduleController::class, 'saveSchedule'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->get('schedule/remove/{id}', [WeeklyScheduleController::class, 'removeSchedule'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->post('schedule/remove-bulk', [WeeklyScheduleController::class, 'removeBulkSchedules'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->post('schedule/send-sms', [WeeklyScheduleController::class, 'sendSMS'], function () {
    return route('login');
});

// routes for drivers
Route::middleware(['auth:sanctum', 'verified'])->get('drivers', [DriverController::class, 'getDrivers'], function () {
    return route('login');
})->name('drivers');
Route::middleware(['auth:sanctum', 'verified'])->post('drivers/get', [DriverController::class, 'getDriver'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->get('drivers/edit/{id}', [DriverController::class, 'editDriver'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->get('drivers/remove/{id}', [DriverController::class, 'removeDriver'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->get('drivers/add', function () {
    return view('drivers.edit');
});
Route::middleware(['auth:sanctum', 'verified'])->post('drivers/remove-bulk', [DriverController::class, 'removeBulkDrivers'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->post('drivers/save', [DriverController::class, 'saveDriver'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->post('drivers/upload', [DriverController::class, 'uploadDrivers'], function () {
    return route('login');
});
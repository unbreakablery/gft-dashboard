<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UploadDataController;
use App\Http\Controllers\ChartDataController;
use App\Http\Controllers\ScorecardsController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\TractorsController;
use App\Http\Controllers\PayrollController;
use App\Http\Controllers\UtilController;

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

Route::middleware(['auth:sanctum', 'verified'])->get('tractors', [TractorsController::class, 'get_tractors'], function () {
    return route('login');
})->name('tractors');
Route::middleware(['auth:sanctum', 'verified'])->get('tractors/edit/{id}', [TractorsController::class, 'edit_tractor'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->get('tractors/remove/{id}', [TractorsController::class, 'remove_tractor'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->post('tractors/get-tractor', [TractorsController::class, 'get_tractor'], function () {
    return route('login');
});
Route::middleware(['auth:sanctum', 'verified'])->get('tractors/add', function () {
    return view('tractors.tractor');
});
Route::middleware(['auth:sanctum', 'verified'])->post('tractors/save', [TractorsController::class, 'save_tractor'], function () {
    return route('login');
});
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
    return view('util.download_data.data');
})->name('util.download-data');
Route::middleware(['auth:sanctum', 'verified'])->post('util/download-data/search', [UtilController::class, 'search_download_data'], function () {
    return route('login');
})->name('util.download-data.search');
Route::middleware(['auth:sanctum', 'verified'])->post('util/download-data/download', [UtilController::class, 'download_data'], function () {
    return route('login');
})->name('util.download-data.download');
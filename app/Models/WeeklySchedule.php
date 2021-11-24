<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Linehaul_Drivers;

class WeeklySchedule extends Model
{
    use HasFactory;

    protected $table = 'weekly_schedule';

    protected $fillable = [
        'year_num',
        'week_num',
        'from_date',
        'to_date',
        'driver_id',
        'driver_name',
        'driver_phone',
        'tractor_id',
        'tcheck',
        'spare_unit',
        'fleet_net',
        'saturday',
        'sunday',
        'monday',
        'tuesday',
        'wednesday',
        'thursday',
        'friday',
        'sent_sms',
        'response'
    ];

    public $timestamps = false;

    public function driver()
    {
        return $this->hasOne(Linehaul_Drivers::class, 'driver_id', 'driver_id');
    }
}

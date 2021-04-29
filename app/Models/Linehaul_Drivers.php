<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Linehaul_Drivers extends Model
{
    use HasFactory;

    protected $table = 'linehaul_drivers';

    protected $fillable = [
        'driver_id',
        'driver_name',
        'fixed_rate',
        'price_per_mile',
        'work_status'
    ];

    public $timestamps = false;
}

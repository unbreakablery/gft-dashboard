<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FixedRateSetting extends Model
{
    use HasFactory;

    protected $table = 'fixed_rate_setting';

    protected $fillable = [
        'driver_id',
        'ms_id',
        'fixed_rate'
    ];

    public $timestamps = true;
}

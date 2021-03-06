<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tractor_Repairs_Misc extends Model
{
    use HasFactory;

    protected $table = 'tractor_repairs_misc';

    protected $fillable = [
        'year_num',
        'week_num',
        'date',
        'vehicle',
        'ticket_check_id',
        'truck_stop',
        'city',
        'state',
        'description',
        'auth_chgbk_arrears',
        'auth_chgbk_refund',
        'repair_misc_amt'
    ];

    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fuel_Purchases extends Model
{
    use HasFactory;

    protected $table = 'fuel_purchases';

    protected $fillable = [
        'year_num',
        'week_num',
        'date',
        'vehicle',
        'ticket_check_id',
        'truck_stop',
        'city',
        'state',
        'qty',
        'pur_amt',
        'auth_chgbk_arrears',
        'auth_chgbk_refund',
        'auth_chgbk_net'
    ];

    public $timestamps = false;
}

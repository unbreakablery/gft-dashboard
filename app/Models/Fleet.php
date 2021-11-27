<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fleet extends Model
{
    use HasFactory;

    protected $table = 'fleets';

    protected $fillable = [
        'tractor_id',
        'model',
        'vin',
        'year',
        'license_plate',
        't_check',
        'pre_pass',
        'service_provider',
        'qiv',
        'bit',
        'domicile',
        'domicile_email',
        'book_value',
        'vedr',
        'eld'
    ];

    public $timestamps = false;
}

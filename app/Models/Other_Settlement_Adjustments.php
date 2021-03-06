<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Other_Settlement_Adjustments extends Model
{
    use HasFactory;

    protected $table = 'other_settlement_adjustments';

    protected $fillable = [
        'year_num',
        'week_num',
        'date',
        'type',
        'description',
        'amt'
    ];

    public $timestamps = false;
}

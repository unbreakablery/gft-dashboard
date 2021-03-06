<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scorecards extends Model
{
    use HasFactory;

    protected $table = 'scorecards';

    protected $fillable = [
        'person_id',
        'type',
        'wk_47',
        'wk_48',
        'wk_49',
        'wk_50',
        'wk_51',
        'wk_52',
        'q1',
        'q2',
        'q3',
        'q4'
    ];

    public $timestamps = false;
}

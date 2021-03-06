<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Persons extends Model
{
    use HasFactory;

    protected $table = 'persons';

    protected $fillable = [
        'name',
        'fedex_id',
        'drug_test',
        'birth',
        'mec',
        'mvr',
        'cov',
        'email'
    ];

    public $timestamps = false;
}

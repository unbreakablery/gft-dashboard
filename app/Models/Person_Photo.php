<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Person_Photo extends Model
{
    use HasFactory;

    protected $table = 'person_photo';

    protected $fillable = [
        'name',
        'ext'
    ];

    public $timestamps = false;
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SignImage extends Model
{
    use HasFactory;

    protected $table = 'sign_images';

    protected $fillable = [
        'name',
        'extension'
    ];

    public $timestamps = true;
}

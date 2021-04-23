<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class External_Links extends Model
{
    use HasFactory;

    protected $table = 'external_links';

    protected $fillable = [
        'name',
        'url',
        'description'
    ];

    public $timestamps = false;
}

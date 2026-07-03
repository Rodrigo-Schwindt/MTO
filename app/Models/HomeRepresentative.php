<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeRepresentative extends Model
{
    protected $fillable = [
        'title',
        'logos',
    ];

    protected $casts = [
        'logos' => 'array',
    ];
}

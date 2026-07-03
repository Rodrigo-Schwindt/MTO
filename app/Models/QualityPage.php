<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualityPage extends Model
{
    protected $fillable = [
        'image_banner',
        'image',
        'title',
        'description',
    ];
}

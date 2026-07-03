<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceCategoryImage extends Model
{
    protected $fillable = [
        'service_category_id',
        'image',
        'orden',
    ];

    public function serviceCategory()
    {
        return $this->belongsTo(ServiceCategory::class);
    }
}

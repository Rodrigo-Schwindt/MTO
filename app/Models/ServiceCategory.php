<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class ServiceCategory extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
        'orden',
        'image',
        'visible',
        'destacado',
    ];

    protected $casts = [
        'visible' => 'boolean',
        'destacado' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (ServiceCategory $service) {
            if (!$service->slug && $service->title) {
                $service->slug = Str::slug($service->title);
            }

            if ($service->orden) {
                $service->orden = strtoupper($service->orden);
            }
        });
    }

    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }

    public function images()
    {
        return $this->hasMany(ServiceCategoryImage::class)->orderBy('orden')->orderBy('id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('orden')->orderBy('title');
    }
}

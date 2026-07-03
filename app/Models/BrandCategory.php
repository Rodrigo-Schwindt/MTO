<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BrandCategory extends Model
{
    protected $fillable = [
        'title',
        'orden',
        'visible',
    ];

    protected $casts = [
        'visible' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (BrandCategory $category) {
            if ($category->orden) {
                $category->orden = strtoupper($category->orden);
            }
        });
    }

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }

    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('orden')->orderBy('title');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'brand_category_id',
        'image',
        'orden',
        'visible',
        'destacado',
    ];

    protected $casts = [
        'visible' => 'boolean',
        'destacado' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Brand $brand) {
            if ($brand->orden) {
                $brand->orden = strtoupper($brand->orden);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(BrandCategory::class, 'brand_category_id');
    }

    public function scopeVisible($query)
    {
        return $query->where('visible', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('orden')->orderBy('id');
    }
}

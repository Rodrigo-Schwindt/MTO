<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EquipmentProduct extends Model
{
    protected $fillable = [
        'equipment_category_id',
        'title',
        'slug',
        'description',
        'technical_sheet',
        'orden',
        'visible',
    ];

    protected $casts = [
        'visible' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (EquipmentProduct $product) {
            if (!$product->slug && $product->title) {
                $product->slug = Str::slug($product->title);
            }

            if ($product->orden) {
                $product->orden = strtoupper($product->orden);
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(EquipmentCategory::class, 'equipment_category_id');
    }

    public function images()
    {
        return $this->hasMany(EquipmentProductImage::class)->orderByDesc('is_main')->orderBy('orden')->orderBy('id');
    }

    public function mainImage()
    {
        return $this->hasOne(EquipmentProductImage::class)->where('is_main', true);
    }

    public function relatedProducts()
    {
        return $this->belongsToMany(
            EquipmentProduct::class,
            'equipment_product_related',
            'equipment_product_id',
            'related_equipment_product_id'
        )->with(['mainImage', 'images'])->ordered();
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

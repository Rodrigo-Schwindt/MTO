<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class EquipmentCategory extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'imagen',
        'orden',
        'visible',
    ];

    protected $casts = [
        'visible' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (EquipmentCategory $category) {
            if (!$category->slug && $category->title) {
                $category->slug = Str::slug($category->title);
            }

            if ($category->orden) {
                $category->orden = strtoupper($category->orden);
            }
        });
    }

    public function products()
    {
        return $this->hasMany(EquipmentProduct::class)->ordered();
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

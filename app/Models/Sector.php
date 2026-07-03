<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sector extends Model
{
    protected $fillable = [
        'title',
        'image',
        'description',
        'slug',
        'orden',
        'visible',
    ];

    protected $casts = [
        'visible' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (Sector $sector) {
            if ($sector->orden) {
                $sector->orden = strtoupper($sector->orden);
            }
        });
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

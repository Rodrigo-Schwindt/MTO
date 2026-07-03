<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeMembership extends Model
{
    protected $fillable = [
        'image',
        'orden',
        'visible',
    ];

    protected $casts = [
        'visible' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::saving(function (HomeMembership $membership) {
            if ($membership->orden) {
                $membership->orden = strtoupper($membership->orden);
            }
        });
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresupuestoSystemType extends Model
{
    use HasFactory;

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
        static::saving(function (PresupuestoSystemType $type) {
            if ($type->orden) {
                $type->orden = strtoupper($type->orden);
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

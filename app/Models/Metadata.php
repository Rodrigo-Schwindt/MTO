<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Metadata extends Model
{
    protected $fillable = [
        'section',
        'keywords',
        'description',
    ];

    public static function getForSection($section)
    {
        return static::where('section', $section)->first();
    }

    public static function getForEquipamiento($equipamientoId)
    {
        return static::where('section', 'equipamiento-' . $equipamientoId)->first();
    }

    public static function getForServicio($servicioId)
    {
        return static::where('section', 'servicio-' . $servicioId)->first();
    }

    public static function getForCategoria($categoriaId)
    {
        return static::where('section', 'categoria-' . $categoriaId)->first();
    }

    public static function getForNovedad($novedadId)
    {
        return static::where('section', 'novedad-' . $novedadId)->first();
    }
}

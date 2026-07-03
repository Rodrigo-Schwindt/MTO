<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QualityDownload extends Model
{
    protected $fillable = [
        'title',
        'image',
        'file',
        'original_name',
        'size',
        'orden',
    ];

    protected static function booted(): void
    {
        static::saving(function (QualityDownload $download) {
            if ($download->orden) {
                $download->orden = strtoupper($download->orden);
            }
        });
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('orden')->orderBy('title');
    }
}

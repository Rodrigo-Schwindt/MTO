<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Project extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'description',
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
        static::saving(function (Project $project) {
            if (!$project->slug && $project->title) {
                $project->slug = Str::slug($project->title);
            }

            if ($project->orden) {
                $project->orden = strtoupper($project->orden);
            }
        });
    }

    public function images()
    {
        return $this->hasMany(ProjectImage::class)->orderByDesc('is_main')->orderBy('orden')->orderBy('id');
    }

    public function mainImage()
    {
        return $this->hasOne(ProjectImage::class)->where('is_main', true);
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

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NovCategories extends Model
{
    protected $table = 'nov_categories';

    protected $fillable = [
        'title',
        'orden',
    ];

    public function novedades()
    {
        return $this->belongsToMany(Novedades::class, 'nov_pivote', 'category_id', 'novedades_id');
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('orden')->orderBy('title');
    }
}

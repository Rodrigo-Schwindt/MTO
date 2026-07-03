<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EquipmentProductImage extends Model
{
    protected $fillable = [
        'equipment_product_id',
        'image',
        'is_main',
        'orden',
    ];

    protected $casts = [
        'is_main' => 'boolean',
    ];

    public function product()
    {
        return $this->belongsTo(EquipmentProduct::class, 'equipment_product_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PresupuestoRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'mobile',
        'province',
        'locality',
        'service',
        'equipment',
        'system_type',
        'message',
        'attachment',
        'attachment_original_name',
        'attachment_size',
        'read_at',
        'ip',
        'user_agent',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactInquiry extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'lastname',
        'email',
        'phone',
        'message',
        'read_at',
        'ip',
        'user_agent',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PasswordReset extends Model
{
    protected $fillable = [
        'company_id',
        'email',
        'token',
        'created_at'
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];
}

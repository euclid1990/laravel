<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class PasswordResetToken extends Model
{
    protected $table = 'password_resets';

    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];

    protected $hidden = [
        'token',
    ];
}

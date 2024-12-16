<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResetPassword extends Model
{
    public $timestamps = false;

    protected $primaryKey = 'token';

    protected $fillable = [
        'user_id',
        'token',
        'expired_at'
    ];
}

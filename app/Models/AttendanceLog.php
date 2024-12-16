<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceLog extends Model
{
    use SoftDeletes;

    protected $table = "attendance_log_users";

    /**
     * Add the late checking or Early checkout 
     * To calculate if user used all his late checking and early checkout
     */
    protected $fillable = [
        'time',
        'date',
        'user_id',
        'activity',
    ];
}

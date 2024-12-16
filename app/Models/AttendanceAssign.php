<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceAssign extends Model
{
    use SoftDeletes;

    protected $table = "attendance_assigns";

    protected $fillable = [
        'status',
        'user_id',
        'shift_id',
        'created_by',
        'effective_to',
        'effective_from',
        'attendance_working_hour_id',
        'attendance_regularization_id',
    ];
}

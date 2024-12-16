<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceRegularize extends Model
{
    use SoftDeletes;

    protected $table = "attendance_regularizations";

    protected $fillable = [
        'name',
        'times',
        'past_days',
        'frequency',
        'after_days',
        'past_month',
        'created_by',
        'future_days',
        'current_day',
        'before_salary',
        'attendance_working_hour_id',
    ];
}

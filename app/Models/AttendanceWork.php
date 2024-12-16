<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceWork extends Model
{
    use SoftDeletes;

    protected $table = "attendance_working_hours";

    protected $fillable = [
        'name',
        'status',
        'created_by',
        'total_hours',
        'full_day_hours',
        'half_day_hours',
        'grace_for_checkin',
        'late_checkin_count',
        'late_checkin_minutes',
        'grace_for_checkout',
        'early_checkout_count',
        'late_checkin_allowed',
        'early_checkout_minutes',
        'late_checkin_frequency',
        'early_checkout_allowed',
        'early_checkout_frequency',
        'working_hours_calculation',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AttendanceReport extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'attendance_assign_id',
        'user_id',
        'shift_id',
        'shift_start_time',
        'shift_end_time',
        'user_login_time',
        'user_logout_time',
        'late_checking',
        'early_checkout',
        'login_duration',
        'work_duration',
        'break_duration',
        'login_remarks',
        'logout_remarks',
        'user_work',
        'absent_reason',
        'absent_value',
        'leave_application_id',
        'is_regularized',
        'regularization_requested_by',
        'regularization_approved_by',
        'regularization_date',
        'regularization_remarks',
        'processing_status',
        'processing_remarks',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpAttendance extends Model
{
    use SoftDeletes;

    protected $table = 'employee_attendances';

    protected $fillable = [
        'user_id',
        'punch_required',
        'cc_not_allowed',
        'department_id',
        'overtime_default',
        'overtime_weekoff',
        'overtime_holiday',
        'weekoff_start_default',
        'weekoff_start_approved',
        'single_punch_required',
    ];
}

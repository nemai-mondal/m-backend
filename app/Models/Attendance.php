<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Attendance extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'ar_date',
        'user_id',
        'day_type',
        'shift_id',
        'work_time',
        'login_remarks',
        'logout_remarks',
        'is_regularized',
        'regularized_by',
        'employee_login',
        'employee_logout',
        'total_login_hours',
        'total_working_hours',
        'leave_application_id',
        'regularization_remarks',
    ];

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }


}

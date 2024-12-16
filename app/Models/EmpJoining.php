<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpJoining extends Model
{
    use SoftDeletes;

    protected $table = "employee_joinings";

    protected $fillable = [
        'user_id',
        'office_email',
        'transfer_date',
        'date_of_joining',
        'salary_start_date',
        'confirmation_date',
        'last_working_date',
        'notice_period_employer',
        'notice_period_employee',
        'probation_period_in_days',
    ];
}

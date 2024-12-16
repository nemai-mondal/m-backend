<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpPersonalDetail extends Model
{
    protected $table = "employee_personal_details";

    protected $fillable = [
        'user_id',
        'father_name',
        'mother_name',
        'marital_status',
        'marriage_date',
        'spouse_name',
        'personal_email',
        'religion',
        'nationality',
        'country_of_birth',
        'state_of_birth',
        'place_of_birth',
        'physical_disabilities',
        'identification_mark1',
        'identification_mark2',
        'hobbies',
        'confirmation_date',
        'phone',
        'date_of_birth',
        'gender',
        'blood_group',
        'alternate_number',
        'emergency_number',
        'country_code'
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpProfessionalDetail extends Model
{
    protected $table = "employee_professional_details";

    protected $fillable = [
        "user_id",
        "approving_manager_id",
        "reporting_manager_id",
        "date_of_joining",
        "contract_type",
    ];
}

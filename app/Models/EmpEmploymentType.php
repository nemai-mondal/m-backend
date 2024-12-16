<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpEmploymentType extends Model
{
    protected $table = "employee_employment_types";

    protected $fillable = [
        "user_id",
        "employment_type_id"
    ];

    public function employmentType() {
        return $this->belongsTo(EmploymentType::class, 'employment_type_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpRole extends Model
{
    protected $table = "employee_roles";

    protected $fillable = [
        "user_id",
        "role_id"
    ];
}

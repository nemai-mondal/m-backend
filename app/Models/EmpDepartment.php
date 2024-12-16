<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpDepartment extends Model
{
    use SoftDeletes;
    protected $table = "employee_departments";

    protected $fillable = [
        "user_id",
        "department_id"
    ];

    public function users()
    {
        return $this->hasMany(User::class, 'id', 'user_id');
    }
    
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DepartmentProject extends Model
{
    use SoftDeletes;
    
    protected $table = "project_departments";

    protected $fillable = [
        'project_id',
        'department_id', 
        'estimation_value',
        'estimation_type',
    ];

    public function departments() {
        return $this->hasMany(Department::class, 'id', 'department_id');
    }

    public function department() {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }
}

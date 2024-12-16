<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpOrganization extends Model
{
    use SoftDeletes;

    protected $table = 'employee_organizations_details';

    protected $fillable = [
        'user_id',
        'department_id',
        'designation_id',
        'location',
        'effective_date',
    ];

    public function department() {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }

    public function designation() {
        return $this->hasOne(Designation::class, 'id', 'designation_id');
    }
}

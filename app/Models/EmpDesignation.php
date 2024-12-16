<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpDesignation extends Model
{
    use SoftDeletes;
    protected $table = "employee_designations";

    protected $fillable = [
        "user_id",
        "designation_id"
    ];

    public function users() {
        return $this->hasMany(User::class, 'id', 'user_id');
    }

    public function designation() {
        return $this->hasOne(Designation::class, 'id', 'designation_id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProjectTarget extends Model
{
    use SoftDeletes;

    protected $table = "project_targets";

    protected $fillable = [
        'user_id', 
        'project_id', 
        'project_id',
        'activity_id',
        'department_id', 
        'designation_id',
        'assigned_by',
        'daily',
        'weekly',
        'monthly',
    ];

    public function designation () {
        return $this->hasOne(Designation::class, 'id', 'designation_id');
    }

    public function activity () {
        return $this->hasOne(Activity::class, 'id', 'activity_id');
    }
}

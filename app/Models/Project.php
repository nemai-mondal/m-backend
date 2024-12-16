<?php

namespace App\Models;

use App\Models\Technology;
use App\Models\User;
use App\Models\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Project extends Model implements HasMedia
{
    use SoftDeletes,InteractsWithMedia;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 
        'cost',
        'status',
        'user_id',
        'priority',
        'end_date',
        'client_id', 
        'manager_id',
        'experience',
        'start_date', 
        'description',
        'salary_range',
        'project_type',
        'department_id',
        'currency_type',
        'notice_period',
        'project_status',
        'no_of_openings',
        'department_name',
        'estimation_type',
        'estimation_value',
    ];

    public function client() {
        return $this->belongsTo(Client::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function updatedByUser() {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

    // public function user() {
    //     return $this->belongsToMany(User::class, 'project_users', 'project_id', 'user_id');
    // }

    public function resources() {
        return $this->belongsToMany(User::class, 'project_users', 'project_id', 'user_id');
    }

    public function technologies() {
        return $this->belongsToMany(Technology::class, 'project_technologies', 'project_id', 'technology_id');
    }

    public function projectManager() {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function documents() {
        return $this->hasMany(ProjectDocument::class);
    }

    public function projectResources() {
        return $this->hasMany(EmpProject::class);
    }

    public function department() {
        return $this->hasOne(Department::class,  'id', 'department_id');
    }

    public function departments() {
        return $this->hasMany(DepartmentProject::class,  'project_id', 'id')->with('department');
    }

}

<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Worklog extends Model
{
    use SoftDeletes;

    protected $table = "work_logs";

    protected $fillable = [
        "date",
        "user_id",
        "task_url",
        "client_id",
        "project_id",
        "time_spent",
        "activity_id",
        "description",
    ];


    public function userDetails()
    {
        return $this->belongsTo(User::class, 'user_id', 'id')->select([
            'id',
            'first_name',
            'middle_name',
            'last_name',
            'email'
        ]);
    }

    public function clientDetails()
    {
        return $this->belongsTo(Client::class, 'client_id', 'id')->select([
            'id',
            'name',
            'type',
            'site'
        ]);
    }

    public function projectDetails()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id')->select([
            'id',
            'name',
            'start_date',
            // 'duration'
        ]);
    }

    public function activityDetails()
    {
        return $this->belongsTo(Activity::class, 'activity_id', 'id')->select([
            'id',
            'name'
        ]);
    }
}

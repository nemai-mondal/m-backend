<?php

namespace App\Models;

use App\Models\Project;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Technology extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name','status',
        ];

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_technologies', 'technology_id', 'project_id');
    }
}

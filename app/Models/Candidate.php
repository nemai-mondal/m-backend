<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Designation;
use App\Models\Department;

class Candidate extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name','date_of_joining','department_id','designation_id',
    ];

    public function designation()
    {
        return $this->belongsTo(Designation::class)->select('id', 'name', 'created_at', 'updated_at');
    }

    public function department()
    {
        return $this->belongsTo(Department::class)->select('id', 'name', 'created_at', 'updated_at');
    }
}

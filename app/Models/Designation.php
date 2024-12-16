<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Designation extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name','status','department_id'
    ];

    public function candidate() {
        return $this->hasMany(Candidate::class);
    }

    public function department() {
        return $this->belongsTo(Department::class);
    }
}

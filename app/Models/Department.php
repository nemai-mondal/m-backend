<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;


class Department extends Model
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

    public function candidate() {
        return $this->hasMany(Candidate::class);
    }

    public function designations() {
        return $this->hasMany(Designation::class);
    }

    public function activities() {
        return $this->hasMany(Activity::class);
    }
}

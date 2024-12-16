<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Activity extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name', 'status', 'department_id'
    ];

    protected $dates = ['deleted_at'];

    public function department() {
        return $this->belongsTo(Department::class);
    }
}

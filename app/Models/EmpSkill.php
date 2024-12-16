<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpSkill extends Model
{
    use SoftDeletes;

    protected $table = 'employee_skills';

    protected $fillable = [
        'type',
        'name',
        'level',
        'user_id',
        'effective_date',
    ];
}

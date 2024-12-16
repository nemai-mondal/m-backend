<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpLanguage extends Model
{
    use SoftDeletes;

    protected $table = 'employee_languages';

    protected $fillable = [
        'name',
        'read',
        'write',
        'speak',
        'native',
        'user_id',
    ];
}

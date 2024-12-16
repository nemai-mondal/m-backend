<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmploymentType extends Model
{
    protected $table = "employment_types";

    Protected $fillable = [
        'name', 'duration', 'duration_type', 'status'
    ];
}

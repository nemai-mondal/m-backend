<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Shift extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 
        'shift_start', 
        'shift_end', 
        'timezone', 
        'converted_shift_start', 
        'converted_shift_end', 
        'converted_timezone', 
        'status'
    ];
}

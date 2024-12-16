<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShiftRule extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'time_in_minutes'
    ];
}

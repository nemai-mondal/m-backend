<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveType extends Model
{
    use SoftDeletes;
    
    Protected $fillable = [
        'name', 'abbreviation', 'comment', 'status'
    ];
}

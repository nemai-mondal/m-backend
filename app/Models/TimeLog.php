<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TimeLog extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'time',
        'date',
        'user_id',
        'activity',
        'terminal',
        'messages'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function shift() {
        return $this->belongsToMany(Shift::class, 'employee_shifts', 'user_id', 'shift_id');
        // return $this->belongsToMany(Shift::class, 'time_logs', 'user_id', 'shift_id')->via('employee_shifts');
        // return $this->hasOne(Shift::class, 'shift_id', 'id', 'user_id');
        // return $this->hasManyThrough(Shift::class, EmpShift::class, 'shift_id', 'user_id', 'id');
    }
}

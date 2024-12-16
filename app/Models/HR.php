<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HR extends Model
{
    
    protected $table = 'hr_announcements';

    protected $fillable = [
        'title', 'description', 'user_id', 'department_id', 'event_date', 'event_start_time', 'event_end_time'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function department()
    {
        return $this->hasOne(Department::class, 'id', 'department_id');
    }
}

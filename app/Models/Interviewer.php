<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Interviewer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'schedule_id',
        'interview_id',
        'assignment_id',
        'interview_round',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewSchedule extends Model
{
    use SoftDeletes;

    protected $table = "interview_schedules";

    protected $fillable = [
        "user_id",
        "interview_id",
        "interview_mode",
        "interview_date",
        "interview_time",
        "interview_duration",
        "interview_platform",
        "interview_url",
        "interview_agenda",
        "assignment_given",
        "assignment_id",
        "related_to",
        "reminder",
        "status",
    ];

    public function interviewScheduledBy() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function interview() {
        return $this->hasOne(Interview::class, 'id', 'interview_id');
    }

    public function assignment() {
        return $this->hasOne(InterviewAssignment::class, 'id', 'related_to');
    }
}

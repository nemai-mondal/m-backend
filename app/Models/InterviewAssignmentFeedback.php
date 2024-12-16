<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewAssignmentFeedback extends Model
{
    use SoftDeletes;

    protected $table = "interview_assignment_feedbacks";
    
    protected $fillable = [
        "status",
        "rating",
        "user_id",
        "feedback",
        "interview_id",
        "assignment_id",
        "overall_rating",
        "feedback_submission_date",
    ];

    public function feedbackGivenBy() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}

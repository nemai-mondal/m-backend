<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewHrFeedback extends Model
{
    use SoftDeletes;

    protected $table = 'interview_hr_feedbacks';
    
    protected $fillable = [
        "status",
        "user_id",
        "strength",
        "weakness",
        "feedback",
        "interview_id",
        "joining_date",
        "interview_date",
        "overall_assessment",
        "cultural_fit_assessment",
    ];

    public function feedbackGivenBy() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function interview() {
        return $this->hasOne(Interview::class, 'id', 'interview_id')->with('candidateAddedBy');
    }
}

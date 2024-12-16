<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewScheduleFeedback extends Model
{
    use SoftDeletes;
    
    protected $table = 'interview_schedule_feedbacks';
    
    protected $fillable = [
        'status',
        'user_id',
        'code_quality',
        'interview_id',
        'overall_rating',
        'problem_solving',
        'technical_feedback',
        'additional_feedback',
        'interview_schedule_id',
    ];
    
    public function feedbackGivedBy() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }    

}

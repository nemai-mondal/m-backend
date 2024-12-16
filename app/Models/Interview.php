<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Interview extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $appends = ['resume'];

    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'applied_designation_id',
        'applied_department_id',
        'source_name',
        'source_link',
        'total_experience',
        'previous_designation',
        'previous_company',
        'current_company',
        'current_ctc',
        'expected_ctc',
        'highest_qualification',
        'notice_period',
        'primary_skill',
        'secondary_skill',
        'remarks',
    ];

    public function interviewers()
    {
        return $this->hasMany(Interviewer::class)->with('user');
    }

    public function designation() {
        return $this->hasOne(Designation::class, 'id', 'applied_designation_id');
    }

    public function department() {
        return $this->hasOne(Department::class, 'id', 'applied_department_id');
    }

    public function candidateAddedBy() {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    
    public function screeningFeedback() {
        return $this->hasOne(InterviewScreening::class)->with('screeningFeedbackBy');
    }
    
    public function assignments() {
        return $this->hasMany(InterviewAssignment::class)->with('assignmentGivenBy');
    }
    
    public function assignmentFeedbacks() {
        return $this->hasMany(InterviewAssignmentFeedback::class)->with('feedbackGivenBy');
    }

    public function scheduledInterviews() {
        return $this->hasMany(InterviewSchedule::class)->with('interviewScheduledBy', 'assignment');
    }

    public function scheduledInterviewFeedbacks() {
        return $this->hasMany(InterviewScheduleFeedback::class)->with('feedbackGivedBy');
    }

    public function hrHeadFeedback() {
        return $this->hasOne(InterviewHrFeedback::class, 'interview_id', 'id')->with('feedbackGivenBy');
    }

    public function candidateResume() {
        return $this->morphMany(Media::class, 'model');
    }

    public function getResumeAttribute() {
        $resumeMedia = $this->media()->where('collection_name', 'interview-resume')->first();
        
        if ($resumeMedia) {
            return $resumeMedia->original_url;
        } else {
            return null; // Or handle the case when no resume is found
        }
    }
}

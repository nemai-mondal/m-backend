<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class InterviewAssignment extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $appends = ['assignment_document_url'];

    protected $fillable = [
        'name',
        'status',
        'details',
        'remarks',
        'user_id',
        'interview_id',
        'assignment_date',
        'submission_date',
        'interview_round',
    ];

    public function assignmentGivenBy() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    
    public function assignmentDocument() {
        return $this->morphMany(Media::class, 'model');
    }

    public function getAssignmentDocumentUrlAttribute() {
        $resumeMedia = $this->media()->where('collection_name', 'interview-assignment')->first();
        
        if ($resumeMedia) {
            return $resumeMedia->original_url;
        } else {
            return null; // Or handle the case when no resume is found
        }
    }

    public function interviewers() {
        return $this->hasMany(Interviewer::class, 'interview_id', 'interview_id')->with('user');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InterviewScreening extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'status',
        'remarks',
        'user_id',
        'attitude',
        'is_suitable',
        'interview_id',
        'work_exp_assessment',
        'interpersonal_skill_score',
        'communication_skill_score',
    ];

    public function screeningFeedbackBy() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}

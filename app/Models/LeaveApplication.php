<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class LeaveApplication extends Model implements HasMedia
{
    use SoftDeletes , InteractsWithMedia;

    protected $fillable = [
        'user_id',
        'remarks',
        'leave_to',
        'attachment',
        'total_days',
        'leave_from',
        'leave_status',
        'leave_type_id',
        'leave_value_end',
        'leave_value_start',
        'email_notification_to',
    ];

    public function userDetails()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class, 'leave_type_id', 'id');
    }

    public function actionTakenBy() 
    {
        return $this->belongsToMany(User::class, 'leave_reviews', 'application_id', 'user_id');
    }
}

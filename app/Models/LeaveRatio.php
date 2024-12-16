<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveRatio extends Model
{
    use SoftDeletes;
    
    Protected $fillable = [
        'employment_type_id', 'leave_type_id', 'leave_credit', 'frequency', 'status'
    ];

    public function employmentType() {
        return $this->belongsTo(EmploymentType::class, 'employment_type_id', 'id');
    }

    public function leaveType() {
        return $this->belongsTo(LeaveType::class, 'leave_type_id', 'id');
    }
}

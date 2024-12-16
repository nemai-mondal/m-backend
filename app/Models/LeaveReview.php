<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeaveReview extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'application_id',
        'remarks',
        'user_id'
    ];

    public function user() {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}

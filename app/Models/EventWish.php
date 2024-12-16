<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventWish extends Model
{
    protected $table = "event_wishes";

    protected $fillable = [
        "wish_from_id",
        "wish_to_id",
        "message",
    ];

    public function wishFrom() {
        return $this->hasOne(User::class, 'id', 'wish_from_id')->select(
            'id',
            'honorific',
            'first_name',
            'middle_name',
            'last_name'
        );
    }

    public function wishTo() {
        return $this->hasOne(User::class, 'id', 'wish_from_id')->select(
            'id',
            'honorific',
            'first_name',
            'middle_name',
            'last_name'
        );
    }
}

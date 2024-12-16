<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpShift extends Model
{
    protected $table = "employee_shifts";

    protected $fillable = [
        "user_id",
        "shift_id"
    ];

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}

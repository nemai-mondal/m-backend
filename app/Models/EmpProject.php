<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpProject extends Model
{
    use SoftDeletes;
    
    protected $table = "project_users";
    // public $timestamps = false;

    protected $fillable = [  
        "user_id",
        "project_id",
    ];

    public function user() {
        return $this->hasOne(User::class, "id", "user_id");
    }
}

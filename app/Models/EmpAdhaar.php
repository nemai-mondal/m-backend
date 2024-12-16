<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class EmpAdhaar extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $table = 'employee_adhaars';

    protected $fillable = [
        'name',
        'user_id',
        'adhaar_no',
        'enrollment_no',
    ];
}

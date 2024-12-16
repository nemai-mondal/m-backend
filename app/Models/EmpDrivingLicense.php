<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class EmpDrivingLicense extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $table = 'employee_driving_licenses';

    protected $fillable = [
        'user_id',
        'name',
        'number',
        'expiry_date',
    ];
}

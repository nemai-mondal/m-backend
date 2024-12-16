<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class EmpSeparation extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $table = 'employee_separations';

    protected $fillable = [
        'user_id',
        'remarks',
        'lwd_expected',
        'submission_date',
        'date_of_joining',
        'year_of_service',
        'lwd_after_serving_notice',
    ];
}

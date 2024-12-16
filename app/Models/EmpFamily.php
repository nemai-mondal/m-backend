<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class EmpFamily  extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;
    
    protected $table = "employee_families";

    protected $fillable = [
        'user_id',
        'title',
        'name',
        'gender',
        'relation',
        'address',
        'blood_group',
        'contact_number',
        'maritial_status',
        'marriage_date',
        'employment',
        'proffesion',
        'nationality',
        'insurance_name',
        'remarks',
        'is_depend',
        'health_insurance',
        'date_of_birth'
    ];
}

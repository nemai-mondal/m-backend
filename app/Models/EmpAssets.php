<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EmpAssets extends Model
{
    use SoftDeletes;

    protected $table = 'employee_assests';

    protected $fillable = [
        'user_id',
        'sr_no',
        'assets_type',
        'assets_name',
        'assets_status',
        'assign_date',
        'valid_till',
        'remarks',
    ];

    protected $dates = ['deleted_at'];
}

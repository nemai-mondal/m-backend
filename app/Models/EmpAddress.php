<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmpAddress extends Model
{
    protected $table = "employee_addresses";

    protected $fillable = [
        'wef',
        'user_id',
        'line1',
        'line2',
        'line3',
        'pincode',
        'country',
        'state',
        'city',
        'city_type',
        'phone1',
        'phone2',
        'land_line1',
        'address_type',
        'contact_name',
        'relation',
        'land_line2',
        'permanent_same_as_current',
    ];
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class EmpVoterCard extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $table = 'employee_voter_cards';

    protected $fillable = [
        'user_id',
        'name',
        'number',
    ];
}

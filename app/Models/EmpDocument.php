<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class EmpDocument extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;

    protected $table = 'employee_documents';

    protected $fillable = [
        'employee_family_id',
        'document_type',
        'active_user_id',
        'user_id',
        'issue_place',
        'issue_date',
        'expiry_date',
        'document_no',
        'remarks',
    ];
}

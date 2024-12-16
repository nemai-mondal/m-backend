<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class EmpQualificationDetails extends Model implements HasMedia
{
    use SoftDeletes, InteractsWithMedia;
    
    protected $appends = ['document_url'];

    protected $table = "employee_qualification_details";

    protected $fillable = [
        'user_id',
        'qualification',
        'stream_type',
        'qualification_course_type',
        'specialization',
        'nature_of_course',
        'qualification_status',
        'institute_name',
        'university_name',
        'from_date',
        'to_date',
        'date_of_passing',
        'percentage',
        'grade',
        'duration_of_course',
        'year',
        'remarks',
        'is_highest_qualification'
    ];


    public function getDocumentUrlAttribute() {
        $image = $this->media()->where('collection_name', 'employee-qualification-document')->first();
        
        if ($image) {
            return $image->original_url;
        } else {
            return null; // Or handle the case when no resume is found
        }
    }
}

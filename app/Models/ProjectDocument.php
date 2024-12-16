<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ProjectDocument extends Model implements HasMedia
{
    use InteractsWithMedia;
    use SoftDeletes;

    protected $appends = ['document_url', 'added_by_name'];
 
    protected $table = "project_documents";

    protected $fillable = [
        'name',
        'user_id',
        'project_id', 
        'description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getAddedByNameAttribute()
    {
        
        if($this->user->honorifics != "" || $this->user->honorifics != null) {
            $honorific = $this->user->honorifics . " ";
        } else {
            $honorific = "";
        }
        
        if($this->user->first_name != "" || $this->user->first_name != null) {
            $f_name = $this->user->first_name . " ";
        } else {
            $f_name = "";
        }
        
        if($this->user->middle_name != "" || $this->user->middle_name != null) {
            $m_name = $this->user->middle_name . " ";
        } else {
            $m_name = "";
        }
        
        if($this->user->last_name != "" || $this->user->last_name != null) {
            $l_name = $this->user->last_name;
        } else {
            $l_name = "";
        }

        return $honorific . $f_name . $m_name . $l_name;
    }

    public function getDocumentUrlAttribute() {
        $image = $this->media()->where('collection_name', 'project-document')->first();
        
        if ($image) {
            return $image->original_url;
        } else {
            return null; // Or handle the case when no resume is found
        }
    }
}

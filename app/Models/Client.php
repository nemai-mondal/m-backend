<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name','type','site','status','contact_person','email','phone','company_name','company_address','opportunity_source','industry','country',
    ];

    protected $dates = ['deleted_at'];

    // public function projects()
    // {
    //     return $this->belongsTo(Project::class, 'id', 'client_id');
    // }

    public function projects() {
        return $this->hasMany(Project::class, 'client_id', 'id')->with('department', 'departments');
    }
}

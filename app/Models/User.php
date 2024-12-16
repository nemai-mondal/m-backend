<?php

namespace App\Models;

use App\Models\Project;
use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Model implements AuthenticatableContract, AuthorizableContract, JWTSubject, HasMedia
{
    use Authenticatable, Authorizable, Notifiable, HasFactory, SoftDeletes, HasRoles, InteractsWithMedia;

    protected $appends = ['profile_image'];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'honorific',
        'first_name',
        'middle_name',
        'last_name',
        'employee_id',
        'email',
        'status',
        'password',
        'password_updated',
        'onboard_confirmed',
    ];

    protected $casts = [
        'deleted_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var string[]
     */
    protected $hidden = [
        'password',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    // public function roles() {
        // return $this->hasMany(Role::class);
    // }

    public function projects()
    {
        return $this->belongsToMany(Project::class, 'project_users', 'user_id', 'project_id');
    }

    public function employeeProfessionalDetail()
    {
        return $this->hasOne(EmpProfessionalDetail::class);
    }

    public function employeePersonalDetail()
    {
        return $this->hasOne(EmpPersonalDetail::class);
    }

    public function employmentType()
    {
        return $this->belongsToMany(EmploymentType::class, 'employee_employment_types', 'user_id', 'employment_type_id');
    }

    // public function designation()
    // {
    //     return $this->belongsToMany(Designation::class, 'employee_designations', 'user_id', 'designation_id');
    // }

    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function department()
    {
        return $this->belongsToMany(Department::class, 'employee_departments', 'user_id', 'department_id');
    }

    public function adhaar()
    {
        return $this->hasOne(EmpAdhaar::class)->withDefault([
            'original_url' => $this->getMedia("adhaar")->first()->original_url ?? ""
        ]);
    }

    public function pan()
    {
        return $this->hasOne(EmpPan::class);
    }

    public function drivingLicense()
    {
        return $this->hasOne(EmpDrivingLicense::class);
    }

    public function voterCard()
    {
        return $this->hasOne(EmpVoterCard::class);
    }

    public function passport()
    {
        return $this->hasOne(EmpPassport::class);
    }

    public function shift()
    {
        return $this->belongsToMany(Shift::class, 'employee_shifts', 'user_id', 'shift_id');
    }

    public function joining()
    {
        return $this->hasOne(EmpJoining::class);
    }

    public function attendance()
    {
        return $this->hasOne(EmpAttendance::class);
    }

    public function skill()
    {
        return $this->hasMany(EmpSkill::class);
    }

    public function language()
    {
        return $this->hasMany(EmpLanguage::class);
    }

    public function asset()
    {
        return $this->hasMany(EmpAssets::class);
    }

    public function address()
    {
        return $this->hasMany(EmpAddress::class);
    }

    public function qualification()
    {
        return $this->hasMany(EmpQualificationDetails::class);
    }

    public function family()
    {
        return $this->hasMany(EmpFamily::class);
    }

    public function separation()
    {
        return $this->hasOne(EmpSeparation::class);
    }

    public function organizations() {
        return $this->hasMany(EmpOrganization::class, 'user_id', 'id')->with('designation', 'department');
        // return $this->hasMany(EmpOrganization::class)->with('department');
    }

    public function getProfileImageAttribute() {
        $image = $this->media()->where('collection_name', 'profile-picture')->first();
        
        if ($image) {
            return $image->original_url;
        } else {
            return null; // Or handle the case when no resume is found
        }
    }

    /**
     * NOT IN USE RIGHT NOW
     * Calculate the profile completion percentage.
     *
     * @return int
     */
    public function profilePercentage()
    {
        $profileFields = [
            'employee_personal_detail.adhaar',
            'department.name',
            'email',
            'employee_id',
            'first_name'
        ];
        $filledFieldsCount = 0;

        foreach ($profileFields as $field) {
            $value = data_get($this, $field);
            if ($value !== null && $value !== '') {
                $filledFieldsCount++;
            }
        }

        return $filledFieldsCount * 20; // 20% for each filled field
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RolePermission extends Model
{
    protected $table = 'role_has_permissions';

    protected $fillable = [
        'permission_id',
        'role_id'
    ];

    public $timestamps = false;

    public function permission()
    {
        return $this->hasOne(Permission::class, 'id', 'permission_id');
    }

    public function role()
    {
        return $this->hasOne(Role::class, 'id', 'role_id');
    }
}

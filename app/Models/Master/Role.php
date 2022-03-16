<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public $table = 'roles';

    public const SUPER_USER = 'Super_User';
    public const User   = 'User';

    public function getRoleType()
    {
        return $this->hasMany(RoleType::class, 'role_id');
    }
}

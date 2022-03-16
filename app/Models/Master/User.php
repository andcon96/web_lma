<?php

namespace App\Models\Master;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getRole()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function getRoleType()
    {
        return $this->belongsTo(RoleType::class, 'role_type_id');
    }

    public function getSupplier()
    {
        return $this->belongsTo(Supplier::class, 'supp_id');
    }

    public function getDepartment()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function hasApproverBudget()
    {
        return $this->hasOne(Budgeting::class, 'approver_budget');
    }

    public function hasAltApproverBudget()
    {
        return $this->hasOne(Budgeting::class, 'alt_approver_budget');
    }
}

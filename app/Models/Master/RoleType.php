<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleType extends Model
{
    use HasFactory;

    public $table = 'role_types';
    protected $fillable = [
        'role_type',
        'role_id'
    ];

    public function getRole()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}

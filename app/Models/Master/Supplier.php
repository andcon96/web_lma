<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    public $table = 'suppliers';

    public function hasUser()
    {
        return $this->hasMany(User::class, 'supp_id');
    }

    public function getPOApprover()
    {
        return $this->hasMany(PoApprover::class, 'po_app_supp_code');
    }

    public function hasItemRelation()
    {
        return $this->hasMany(SupplierItemRelation::class, 'sir_supp_code');
    }
}

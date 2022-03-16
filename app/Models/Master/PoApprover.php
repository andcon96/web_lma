<?php

namespace App\Models\Master;

use App\Models\POMaster;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PoApprover extends Model
{
    use HasFactory;

    public $table = 'po_approvers';

    protected $fillable = [
        'id',
    ];

    public function getSuppInfo()
    {
        return $this->belongsTo(Supplier::class, 'po_app_supp_code');
    }

    public function getUserApprover()
    {
        return $this->belongsTo(User::class, 'po_app_approver');
    }

    public function getAltUserApprover()
    {
        return $this->belongsTo(User::class, 'po_app_alt_approver');
    }
}

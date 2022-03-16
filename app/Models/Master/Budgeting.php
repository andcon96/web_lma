<?php

namespace App\Models\Master;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budgeting extends Model
{
    use HasFactory;

    public $table = 'app_budgetings';
    
    protected $fillable = [
        'id'
    ];

    public function getApproverBudget()
    {
        return $this->belongsTo(User::class, 'approver_budget');
    }

    public function getAltApproverBudget()
    {
        return $this->belongsTo(User::class, 'alt_approver_budget');
    }
}

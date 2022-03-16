<?php

namespace App\Models\Master;

use App\Models\RFPMaster;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RFPAppr extends Model
{
    use HasFactory;

    public $table = 'rfps_approval';

    public function getDepartment()
    {
        return $this->belongsTo(Department::class, 'rfps_department_id');
    }
}

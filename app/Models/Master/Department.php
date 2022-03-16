<?php

namespace App\Models\Master;

use App\Models\RFPMaster;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    public $table = 'departments';

    public function hasRFPMaster()
    {
        return $this->hasMany(RFPMaster::class, 'rfp_department_id');
    }
}

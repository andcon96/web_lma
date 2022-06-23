<?php

namespace App\Models\Export;

use App\Models\Master\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExportPO extends Model
{
    use HasFactory;

    public $table = 'po_hist';

    public function getUserName(){
        return $this->belongsTo(User::class,'created_by');
    }
}

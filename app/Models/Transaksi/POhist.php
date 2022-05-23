<?php

namespace App\Models\Transaksi;

use App\Models\Master\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class POhist extends Model
{
    use HasFactory;

    public $table = 'po_hist';

    public function getUser(){
        return $this->belongsTo(User::class,'created_by');
    }

}
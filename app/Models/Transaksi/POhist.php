<?php

namespace App\Models\Transaksi;

use App\Models\Master\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Session;

class POhist extends Model
{
    use HasFactory;

    public $table = 'po_hist';

    public function getUser(){
        return $this->belongsTo(User::class,'created_by');
    }

    protected static function boot()
    {
        parent::boot();

        self::addGlobalScope(function(Builder $builder){
            // $builder->where('user_id', '=', Auth()->user()->id);
            if(auth()->user()->getRole->role != "Super_User"){
                $builder->where('ph_domain', Session::get('domain'));
                $builder->where('created_by', auth()->user()->id);

                // if(auth()->user()->getRoleType->usertype == "notoffice"){
                //     $builder->where('created_by', auth()->user()->id);
                // }
                // 
            }

        });
    }

}
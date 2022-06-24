<?php

namespace App\Exports;

use App\Models\Transaksi\SuratJalan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class SJExport implements FromView, ShouldAutoSize
{
    public function __construct($sjnbr,$sonbr,$customer,$status,$tanggalsj)
    {
        $this->sjnbr = $sjnbr;
        $this->sonbr = $sonbr;
        $this->customer = $customer;
        $this->status = $status;
        $this->tanggalsj = $tanggalsj;
    }

    public function view(): View
    {   
        $sjnbr = $this->sjnbr;
        $sonbr = $this->sonbr;
        $customer = $this->customer;
        $status = $this->status;
        $tanggalsj = $this->tanggalsj;


        $getsj = SuratJalan::with('getDetail','getDetailCust','getDetailShip');

        $getsj->when(isset($sjnbr), function($q) use ($sjnbr) {
            $q->where('sj_nbr', $sjnbr);
        });

        $getsj->when(isset($sonbr), function($q) use ($sonbr) {
            $q->where('sj_so_nbr', $sonbr);
        });

        $getsj->when(isset($customer), function($q) use ($customer) {
            $q->where('sj_so_cust', $customer);
        });

        $getsj->when(isset($status), function($q) use ($status) {
            $q->where('sj_status', $status);
        });

        $getsj->when(isset($tanggalsj), function($q) use ($tanggalsj) {
            $q->where('created_at','like', $tanggalsj.'%');
        });

        $getsj = $getsj->get();


        return view('export.sj', [
            'sj' => $getsj,
        ]);
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    
}

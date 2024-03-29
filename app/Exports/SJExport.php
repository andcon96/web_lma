<?php

namespace App\Exports;

use App\Models\Transaksi\SuratJalan;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Style\Color;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Style;

class SJExport implements FromView, ShouldAutoSize
{
    public function __construct($sjnbr,$sonbr,$customer,$status,$tanggalsj,$nopol)
    {
        $this->sjnbr = $sjnbr;
        $this->sonbr = $sonbr;
        $this->customer = $customer;
        $this->status = $status;
        $this->tanggalsj = $tanggalsj;
        $this->nopol = $nopol;
    }

    public function view(): View
    {   
        $sjnbr = $this->sjnbr;
        $sonbr = $this->sonbr;
        $customer = $this->customer;
        $status = $this->status;
        $tanggalsj = $this->tanggalsj;
        $nopol = $this->nopol;


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

        $getsj->when(isset($nopol), function($q) use ($nopol) {
            $q->where('sj_nopol', $nopol);
        });

        $getsj = $getsj->where('sj_domain','=', Session::get('domain'))->get();

        return view('export.sj', [
            'sj' => $getsj,
        ]);
    }

    public function defaultStyles(Style $defaultStyle)
    {
        // Configure the default styles
        return $defaultStyle->getFill()->setFillType(Fill::FILL_SOLID);
    
        // Or return the styles array
        return [
            'fill' => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => Color::RED],
            ],
        ];
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    
}

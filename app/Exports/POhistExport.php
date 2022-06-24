<?php

namespace App\Exports;

use App\Models\Transaksi\POhist;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStrictNullComparison;

class POhistExport implements FromView, ShouldAutoSize, WithStrictNullComparison
{
    public function __construct($ponbr,$supp,$receiptdate,$pocon)
    {
        $this->ponbr = $ponbr;
        $this->supp = $supp;
        $this->receiptdate = $receiptdate;
        $this->pocon = $pocon;
        
    }

    public function view():View
    {
        $ponbr = $this->ponbr;
        $supp = $this->supp;
        $receiptdate = $this->receiptdate;
        $pocon = $this->pocon;

        $po = POhist::with('getUser.getRoleType');

        $po->when(isset($ponbr), function($q) use ($ponbr) {
            $q->where('ph_ponbr', $ponbr);
        });

        $po->when(isset($supp), function($q) use ($supp) {
            $q->where('ph_supp', $supp);
        });

        $po->when(isset($receiptdate), function($q) use ($receiptdate) {
            $q->where('ph_receiptdate', $receiptdate);
        });

        $po->when(isset($pocon), function($q) use ($pocon) {
            $q->where('ph_pokontrak', $pocon);
        });

        $po = $po->get();

        return view('export.pohist', [
            'po' => $po
        ]);
    }
    
}

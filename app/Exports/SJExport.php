<?php

namespace App\Exports;

use App\Models\Transaksi\SuratJalan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class SJExport implements FromView
{
    public function view(): View
    {
        $getsj = SuratJalan::with('getDetail','getDetailCust','getDetailShip')->get();

        return view('export.sj', [
            'sj' => $getsj,
        ]);
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    
}

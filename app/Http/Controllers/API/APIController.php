<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\EmailtoReceiver;
use App\Models\Master\PoInvcEmail;
use App\Models\Transaksi\POInvcApprHist;
use Carbon\Carbon;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class APIController extends Controller
{
    //

    public function approvedInvcYes($ponbr, $invcnbr)
    {

        try {

            $param1 = Crypt::decrypt($ponbr);
            $param2 = Crypt::decrypt($invcnbr);

            $poinvc_hist = POInvcApprHist::firstOrNew(array('id' => '1'));
            $poinvc_hist->ponbr = $param1;
            $poinvc_hist->invcnbr = $param2;
            $poinvc_hist->status = 'approved';

            $poinvc_hist->save();

            // dd($emailto);

            $pesan = 'PO Invoice Information';
            $pesan2 =  'PO '.$param1.' dengan invoice '.$param2.' sudah diapprove';

            $ponbr = $param1;
            $invcnbr = $param2;

            EmailtoReceiver::dispatch(
                $pesan,
                $pesan2,
                $ponbr,
                $invcnbr,
            );


            // dd($emailto);


            return view('Invc_yes');
        } catch (DecryptException $error) {
            // dd($error);
            abort('404');
        }
    }

    public function approvedInvcNo($ponbr, $invcnbr)
    {
        try {

            $param1 = Crypt::decrypt($ponbr);
            $param2 = Crypt::decrypt($invcnbr);

            $poinvc_hist = POInvcApprHist::firstOrNew(array('id' => '1'));
            $poinvc_hist->ponbr = $param1;
            $poinvc_hist->invcnbr = $param2;
            $poinvc_hist->status = 'reject';

            $poinvc_hist->save();

            $pesan = 'PO Invoice Information';
            $pesan2 =  'PO '.$param1.' dengan invoice '.$param2.' sudah direject';

            $ponbr = $param1;
            $invcnbr = $param2;

            EmailtoReceiver::dispatch(
                $pesan,
                $pesan2,
                $ponbr,
                $invcnbr,
            );

            return view('Invc_no');
        } catch (DecryptException $error) {
            abort('404');
        }
    }
}

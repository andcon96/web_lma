<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\EmailtoReceiver;
use App\Models\Master\PoInvcEmail;
use App\Models\Transaksi\POInvc;
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

            // dd('aa');

            $param1 = Crypt::decrypt($ponbr);
            $param2 = Crypt::decrypt($invcnbr);
            // dd($param1);

            $poinvc_hist = POInvcApprHist::where('ponbr','=',$param1)->where('invcnbr','=',$param2)->first();
            // dd($poinvc_hist);

            if(!$poinvc_hist){
                // dd($param1);

                $poinvc1 = new POInvcApprHist();
                $poinvc1->ponbr = $param1;
                $poinvc1->invcnbr = $param2;
                $poinvc1->status = 'approved';

                $poinvc1->save();

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
            }else{
                return view('Invc_Ada');
            }
            
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

            $poinvc_hist = POInvcApprHist::where('ponbr','=',$param1)->where('invcnbr','=',$param2)->first();
            
            if(!$poinvc_hist){


                $poinvc2 = new POInvcApprHist();
                $poinvc2->ponbr = $param1;
                $poinvc2->invcnbr = $param2;
                $poinvc2->status = 'reject';

                $poinvc2->save();

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
            }else{
                return view('Invc_Ada');
            }
            
        } catch (DecryptException $error) {
            abort('404');
        }
    }
}

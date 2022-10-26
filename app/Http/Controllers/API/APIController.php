<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Jobs\EmailtoReceiver;
use App\Models\Master\PoInvcEmail;
use App\Models\Transaksi\POInvc;
use App\Models\Transaksi\POInvcApprHist;
use Carbon\Carbon;
use Exception;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class APIController extends Controller
{
    //

    public function approvedInvcYes($ponbr, $invcnbr, $supp, $postingdate, $amt, $dom)
    {

        try {

            // dd('aa');

            $param1 = Crypt::decrypt($ponbr);
            $param2 = Crypt::decrypt($invcnbr);
            $param3 = Crypt::decrypt($supp);
            $param4 = Crypt::decrypt($postingdate);
            $param5 = Crypt::decrypt($amt);
            $param6 = Crypt::decrypt($dom);
            // dd($param1,$param2,$param3,$param4,$param5,$param6);

            $poinvc_hist = POInvcApprHist::where('dom','=',$param6)->where('invcnbr','=',$param2)->first();
            // dd($poinvc_hist);

            if(!$poinvc_hist){
                // dd($param1);

                $poinvc1 = new POInvcApprHist();
                $poinvc1->ponbr = $param1 ?? '-';
                $poinvc1->dom = $param6;
                $poinvc1->invcnbr = $param2;
                $poinvc1->status = 'approved';

                $poinvc1->save();

                // dd($emailto);

                $pesan = 'PO Invoice Information';
                $pesan2 = 'Invoice sudah diapprove';
                $ponbr =  $param1;
                $invcnbr = $param2;
                $supp = $param3;
                $postingdate = $param4;
                $amt = $param5;

                EmailtoReceiver::dispatch(
                    $pesan,
                    $pesan2,
                    $ponbr,
                    $invcnbr,
                    $supp,
                    $postingdate,
                    $amt,
                );


                // dd($emailto);


                return view('Invc_yes');
            }else{
                return view('Invc_Ada');
            }
            
        } catch (DecryptException $error) {
            // dd($error);
            abort('404');
        } catch (Exception $err) {
            abort('500');
        }
    }

    public function approvedInvcNo($ponbr, $invcnbr, $supp, $postingdate, $amt, $dom)
    {
        try {

            $param1 = Crypt::decrypt($ponbr);
            $param2 = Crypt::decrypt($invcnbr);
            $param3 = Crypt::decrypt($supp);
            $param4 = Crypt::decrypt($postingdate);
            $param5 = Crypt::decrypt($amt);
            $param6 = Crypt::decrypt($dom);

            $poinvc_hist = POInvcApprHist::where('dom','=',$param6)->where('invcnbr','=',$param2)->first();
            
            if(!$poinvc_hist){


                $poinvc2 = new POInvcApprHist();
                $poinvc2->ponbr = $param1 ?? '-';
                $poinvc2->dom = $param6;
                $poinvc2->invcnbr = $param2;
                $poinvc2->status = 'rejected';

                $poinvc2->save();

                $pesan = 'PO Invoice Information';
                $pesan2 = 'Invoice sudah direject';
                $ponbr =  $param1;
                $invcnbr = $param2;
                $supp = $param3;
                $postingdate = $param4;
                $amt = $param5;

                EmailtoReceiver::dispatch(
                    $pesan,
                    $pesan2,
                    $ponbr,
                    $invcnbr,
                    $supp,
                    $postingdate,
                    $amt,
                );

                return view('Invc_no');
            }else{
                return view('Invc_Ada');
            }
            
        } catch (DecryptException $error) {
            abort('404');
        } catch (Exception $err) {
            abort('500');
        }
    }
}

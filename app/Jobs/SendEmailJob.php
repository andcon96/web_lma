<?php

namespace App\Jobs;

use App\Models\Master\Company;
use App\Models\Master\User;
use App\Models\RFQDetail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use PDF;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $rfqnbr, $itemcode, $itemdesc, $qtyreq, $proqty, $duedate, $prodate, $remarks, $supplier;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($rfqnbr,$itemcode,$itemdesc,$qtyreq,$proqty,$duedate,$prodate,$remarks,$supplier)
    {
        $this->rfqnbr = $rfqnbr;
        $this->itemcode = $itemcode;
        $this->itemdesc = $itemdesc;
        $this->qtyreq = $qtyreq;
        $this->proqty = $proqty;
        $this->duedate = $duedate;
        $this->prodate = $prodate;
        $this->remarks = $remarks;
        $this->supplier = $supplier;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = User::where('role_id','=','3')->get(); // Email Purchasing
        $company = Company::firstOrFail();
        $rfqnbr = $this->rfqnbr;
        $itemcode = $this->itemcode;
        $itemdesc = $this->itemdesc;
        $qtyreq = $this->qtyreq;
        $proqty = $this->proqty;
        $duedate = $this->duedate;
        $prodate = $this->prodate;
        $remarks = $this->remarks;
        $supplier = $this->supplier;

        if($email->count() > 0){
            $listemail = '';
            foreach($email as $emails){
                $listemail .= $emails->email.',';
            }
            $listemail = substr($listemail, 0, strlen($listemail) - 1);
            $array_email = explode(',', $listemail);

            // Kirim Email Notif Ke Purchasing
            Mail::send('email.emailrfqbid', 
                ['pesan' => 'Supplier : '.$supplier.' has made an offer for following RFQ',
                    'note1' => $rfqnbr,
                    'note2' => $itemcode,
                    'note3' => $itemdesc,
                    'note4' => $qtyreq,
                    'note5' => number_format($proqty,2),
                    'note6' => $duedate,
                    'note7' => $prodate,
                    'note8' => $remarks], 
                function ($message) use ($supplier,$array_email,$company)
            {
                $message->subject('PhD - Request for Quotation Feedback - '.$supplier);
                $message->from($company->company_email); // Email Admin Fix
                $message->to($array_email);
            });
        }
    }
}

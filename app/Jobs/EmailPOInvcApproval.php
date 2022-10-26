<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Mail;

class EmailPOInvcApproval
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $pesan;
    protected $dom;
    protected $ponbr;
    protected $supplier;
    protected $posdate;
    protected $invcnbr;
    protected $invcamt;
    protected $penerima;
    protected $alamatemail;

    public function __construct($pesan, $dom, $ponbr, $supplier, $posdate, $invcnbr, $invcamt, $penerima, $alamatemail)
    {
        //
        $this->pesan = $pesan;
        $this->dom = $dom;
        $this->ponbr = $ponbr;
        $this->supplier = $supplier;
        $this->posdate = $posdate;
        $this->invcnbr = $invcnbr;
        $this->invcamt = $invcamt;
        $this->penerima = $penerima;
        $this->alamatemail = $alamatemail;

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        //
        $pesan = $this->pesan;
        $dom = $this->dom;
        $ponbr = $this->ponbr;
        $supplier = $this->supplier;
        $posdate = $this->posdate;
        $invcnbr = $this->invcnbr;
        $invcamt = $this->invcamt;
        $penerima = $this->penerima;
        $alamatemail = $this->alamatemail;

        $param1 = Crypt::encrypt($ponbr);
        $param2 = Crypt::encrypt($invcnbr);
        $param3 = Crypt::encrypt($supplier);
        $param4 = Crypt::encrypt($posdate);
        $param5 = Crypt::encrypt($invcamt);
        $param6 = Crypt::encrypt($dom);

        // dd($pesan,$ponbr,$invcnbr,$invcamt, $penerima, $alamatemail, $dom);

        Mail::send(
            'email.EmailPOApproval',
            [
                'pesan' => $pesan,
                'note1' => $ponbr,
                'note2' => $invcnbr,
                'note3' => $invcamt,
                'note4' => $supplier,
                'note5' => $posdate,
                'note6' => $dom,
                'param1' => $param1,
                'param2' => $param2,
                'param3' => $param3,
                'param4' => $param4,
                'param5' => $param5,
                'param6' => $param6, 
            ],
            function ($message) use ($alamatemail) {
                $message->subject('Purchase Order Invoice Approval Task');
                $message->to($alamatemail);
            }
        );

    }
}

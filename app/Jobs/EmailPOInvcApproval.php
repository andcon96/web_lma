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
    protected $ponbr;
    protected $supplier;
    protected $posdate;
    protected $invcnbr;
    protected $invcamt;
    protected $penerima;
    protected $alamatemail;

    public function __construct($pesan, $ponbr, $supplier, $posdate, $invcnbr, $invcamt, $penerima, $alamatemail)
    {
        //
        $this->pesan = $pesan;
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

        // dump($pesan,$ponbr,$invcnbr,$invcamt, $penerima, $alamatemail);

        Mail::send(
            'email.EmailPOApproval',
            [
                'pesan' => $pesan,
                'note1' => $ponbr,
                'note2' => $invcnbr,
                'note3' => $invcamt,
                'note4' => $supplier,
                'note5' => $posdate,
                'param1' => $param1,
                'param2' => $param2,
                'param3' => $param3,
                'param4' => $param4,
                'param5' => $param5, 
            ],
            function ($message) use ($alamatemail) {
                $message->subject('Purchase Order Invoice Approval Task');
                $message->to($alamatemail);
            }
        );

    }
}

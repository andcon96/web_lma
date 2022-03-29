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
    protected $invcnbr;
    protected $invcamt;
    protected $penerima;
    protected $alamatemail;

    public function __construct($pesan, $ponbr, $invcnbr, $invcamt, $penerima, $alamatemail)
    {
        //
        $this->pesan = $pesan;
        $this->ponbr = $ponbr;
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
        $invcnbr = $this->invcnbr;
        $invcamt = $this->invcamt;
        $penerima = $this->penerima;
        $alamatemail = $this->alamatemail;

        $param1 = Crypt::encrypt($ponbr);
        $param2 = Crypt::encrypt($invcnbr);

        // dump($pesan,$ponbr,$invcnbr,$invcamt, $penerima, $alamatemail);

        Mail::send(
            'email.EmailPOApproval',
            [
                'pesan' => $pesan,
                'note1' => $ponbr,
                'note2' => $invcnbr,
                'note3' => $invcamt,
                'param1' => $param1,
                'param2' => $param2, 
            ],
            function ($message) use ($alamatemail) {
                $message->subject('Purchase Order Invoice Approval Task');
                $message->to($alamatemail);
            }
        );

    }
}

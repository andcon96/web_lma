<?php

namespace App\Jobs;

use App\Models\Master\PoInvcEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EmailtoReceiver
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $pesan;
    protected $pesan2;
    protected $ponbr;
    protected $invcnbr;
    protected $supp;
    protected $postingdate;
    protected $amt;
    protected $dom;

    public function __construct($pesan, $pesan2, $ponbr, $invcnbr, $supp, $postingdate, $amt, $dom)
    {
        //
        $this->pesan = $pesan;
        $this->pesan2 = $pesan2;
        $this->ponbr = $ponbr;
        $this->invcnbr = $invcnbr;
        $this->supp = $supp;
        $this->postingdate = $postingdate;
        $this->amt = $amt;
        $this->dom = $dom;
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
        $pesan2 = $this->pesan2;
        $ponbr = $this->ponbr;
        $invcnbr = $this->invcnbr;
        $supp = $this->supp;
        $postingdate = $this->postingdate;
        $amt = $this->amt;
        $dom = $this->dom;

        // dd($this->emailto);

        $emailto = PoInvcEmail::first();

        $emailto = explode(';', $emailto->email_receiver);

        Mail::send(
            'email.EmailtoReceiver',
            [
                'pesan' => $pesan,
                'pesan2' => $pesan2,
                'ponbr' => $ponbr,
                'invcnbr' => $invcnbr,
                'supp' => $supp,
                'postingdate' => $postingdate,
                'amt' => $amt,
                'dom' => $dom,

            ],
            function ($message) use ($emailto) {
                $message->subject('Purchase Order Invoice Approval Information');
                $message->to($emailto);
            }
        );

    }
}

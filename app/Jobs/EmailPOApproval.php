<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EmailPOApproval implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    protected $pesan;
    protected $po_nbr;
    protected $po_ord_date;
    protected $po_due_date;
    protected $po_total;
    protected $emails;
    protected $com_name;
    protected $com_email;

    public function __construct($pesan, $po_nbr, $po_ord_date, $po_due_date, $po_total, $emails, $com_name, $com_email)
    {
        $this->pesan = $pesan;
        $this->po_nbr = $po_nbr;
        $this->po_ord_date = $po_ord_date;
        $this->po_due_date = $po_due_date;
        $this->po_total = $po_total;
        $this->emails = $emails;
        $this->com_name = $com_name;
        $this->com_email = $com_email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $pesan = $this->pesan;
        $po_nbr = $this->po_nbr;
        $po_ord_date = $this->po_ord_date;
        $po_due_date = $this->po_due_date;
        $po_total = $this->po_total;
        $emails = $this->emails;
        $com_name = $this->com_name;
        $com_email = $this->com_email;
        
        // dd($pesan, $po_nbr, $po_ord_date, $po_due_date, $po_total, $emails, $com_name, $com_email);

        Mail::send(
            'email.emailapproval',
            [
                'pesan' => $pesan,
                'note1' => $po_nbr,
                'note2' => $po_ord_date,
                'note3' => $po_due_date,
                'note4' => $po_total,
                'note5' => 'Please Check'
            ],
            function ($message) use ($emails, $com_name, $com_email) {
                $message->subject('PhD - Purchase Order Approval Task - ' . $com_name);
                $message->from($com_email);
                $message->to($emails);
            }
        );
    }
}

<?php

namespace App\Console\Commands;

use App\Jobs\EmailtoReceiver;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ResendReceiver extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'resendreceiver:process {dom} {ponbr} {invcnbr} {supp} {postingdate} {amt}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Resened Email Receiver';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $param1 = $this->argument('ponbr');
        $param2 = $this->argument('invcnbr');
        $param3 = $this->argument('supp');
        $param4 = $this->argument('postingdate');
        $param5 = $this->argument('amt');
        $param6 = $this->argument('dom');


        $pesan = 'PO Invoice Information';
        $pesan2 = 'Invoice sudah diapprove';
        $ponbr =  $param1;
        $invcnbr = $param2;
        $supp = $param3;
        $postingdate = $param4;
        $amt = $param5;
        $dom = $param6;

        EmailtoReceiver::dispatch(
            $pesan,
            $pesan2,
            $ponbr,
            $invcnbr,
            $supp,
            $postingdate,
            $amt,
            $dom
        );

        Log::channel('resendreceiver')->info('Email Receiver Berhasil di Resend dengan informasi : '.$ponbr.' '.$invcnbr.' '.$supp.' '.$postingdate.' '.$amt.' '.$dom.' ');


    }
}

<?php

namespace App\Jobs;

use App\Models\Master\User;
use App\Notifications\eventNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EmailRFP implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    protected $rfpnumber;
    protected $rfp_duedate;
    protected $created_by;
    protected $rfp_dept;
    protected $emailreject;
    protected $company;
    protected $rfp_apr;
    protected $rfp_altapr;
    protected $array_email;
    protected $parameter;

    public function __construct($rfpnumber, $rfp_duedate, $created_by, $rfp_dept, $emailreject, $company, $rfp_apr, $rfp_altapr, $array_email, $parameter)
    {
        $this->rfpnumber = $rfpnumber;
        $this->rfp_duedate = $rfp_duedate;
        $this->created_by = $created_by;
        $this->rfp_dept = $rfp_dept;
        $this->emailreject = $emailreject;
        $this->company = $company;
        $this->rfp_apr = $rfp_apr;
        $this->rfp_altapr = $rfp_altapr;
        $this->array_email = $array_email;
        $this->parameter = $parameter;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rfpnbr = $this->rfpnumber;
        $rfpduedate = $this->rfp_duedate;
        $created_by = $this->created_by;
        $rfp_dept = $this->rfp_dept;
        $array_email = $this->array_email;
        $company = $this->company;
        $rfp_apr = $this->rfp_apr;
        $rfp_altapr = $this->rfp_altapr;
        $emailreject = $this->emailreject;
        $parameter = $this->parameter;

        switch ($parameter) {
            case '1':
                Mail::send(
                    'email.emailrfpapproval',
                    [
                        'pesan' => 'Following Request for Purchasing has been rejected :',
                        'note1' => $rfpnbr,
                        'note2' => $rfpduedate,
                        'note3' => $created_by,
                        'note4' => $rfp_dept,
                        'note5' => 'Please Check.'
                    ],
                    function ($message) use ($rfpnbr, $emailreject, $company) {
                        $message->subject('Notifikasi : Request for Purchasing Approval Rejected - ' . $company->company_name);
                        $message->from($company->company_email);
                        $message->to($emailreject->createdBy->email);
                    }
                );

                $notifUser = User::where('username', $created_by)->first();

                $details = [
                    'body' => 'Following Request for Purchasing has been rejected',
                    'url' => 'inputrfp',
                    'nbr' => $rfpnbr,
                    'note' => 'Please check'
                ];

                $notifUser->notify(new eventNotification($details));

                break;
            case '2':
                Mail::send(
                    'email.emailrfpapproval',
                    [
                        'pesan' => 'There is a RFP awaiting for approval',
                        'note1' => $rfpnbr,
                        'note2' => $rfpduedate,
                        'note3' => $created_by->name,
                        'note4' => $rfp_dept->department_name,
                        'note5' => 'Please Check.'
                    ],
                    function ($message) use ($rfpnbr, $array_email, $company) {
                        $message->subject('PhD - RFP Approval Task -' . $company->company_name);
                        $message->from($company->company_email);
                        $message->to($array_email);
                    }
                );

                // ditambahkan 03/11/2020
                $notifyUser = User::where('id', '=', $rfp_apr)->first(); // user siapa yang terima notif (lewat id)
                $notifyAltUser = User::where('id', '=', $rfp_altapr)->first();

                $details = [
                    'body' => 'There is a RFP awaiting for approval',
                    'url' => 'rfpapproval',
                    'nbr' => $rfpnbr,
                    'note' => 'Please check'
                ]; // isi data yang dioper


                $notifyUser->notify(new eventNotification($details));
                $notifyAltUser->notify(new eventNotification($details));

                break;
            case '3':
                // Kirim Email Notif Ke approver
                Mail::send(
                    'email.emailrfp',
                    [
                        'pesan' => 'There is a new RFP exceed budget awaiting your response',
                        'note1' => $rfpnbr,
                        'note2' => $rfpduedate,
                        'note3' => $created_by->name,
                        'note4' => $rfp_dept->department_name
                    ],
                    // 'note3' => $rfpmstrs->xrfp_duedate,
                    // 'note4' => $rfpmstrs->created_by,
                    // 'note5' => $rfpmstrs->xrfp_dept],
                    function ($message) use ($array_email, $company) {
                        $message->subject('PhD - RFP Approval Task - ' . $company->company_name);
                        $message->from($company->company_email); // Email Admin Fix
                        $message->to($array_email);
                    }
                );
                break;
            case '4':
                Mail::send('email.emalrfp', [
                        'pesan' => 'There is a new RFP awaiting your response',
                        'note1' => $rfpnbr,
                        'note2' => $rfpduedate,
                        'note3' => $created_by->name,
                        'note4' => $rfp_dept->department_name
                    ],
                    // 'note3' => $rfpmstrs->xrfp_duedate,
                    // 'note4' => $rfpmstrs->created_by,
                    // 'note5' => $rfpmstrs->xrfp_dept],
                    function ($message) use ($array_email, $company) {
                        $message->subject('PhD - RFP Approval Task - ' . $company->company_name);
                        $message->from($company->company_email); // Email Admin Fix
                        $message->to($array_email);
                    }
                );
                break;
        }
    }
}

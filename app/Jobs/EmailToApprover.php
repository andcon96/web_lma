<?php

namespace App\Jobs;

use App\Models\Master\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EmailToApprover implements ShouldQueue
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
    protected $department;
    protected $emails;
    protected $company;
    protected $userApprover;
    protected $altUserApprover;

    public function __construct($rfpnumber, $rfp_duedate, $created_by, $department, $emails, $company, $userApprover, $altUserApprover)
    {
        $this->rfpnumber = $rfpnumber;
        $this->rfp_duedate = $rfp_duedate;
        $this->created_by = $created_by;
        $this->department = $department;
        $this->emails = $emails;
        $this->company = $company;
        $this->userApprover = $userApprover;
        $this->altUserApprover = $altUserApprover;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rfpnumber = $this->rfpnumber;
        $rfp_duedate = $this->rfp_duedate;
        $created_by = $this->created_by;
        $department = $this->department;
        $emails = $this->emails;
        $company = $this->company;

        $userApprover = $this->userApprover;
        $altUserApprover = $this->altUserApprover;

        // dd($rfpnumber,$rfp_duedate,$created_by,$department,$tampungemail,$company);

        Mail::send(
            'email.emailrfp',
            [
                'pesan' => 'There are updates on following RFP. Approval is needed, Please check.',
                'note1' => $rfpnumber,
                'note2' => $rfp_duedate,
                'note3' => $created_by,
                'note4' => $department
            ],
            // 'note3' => $rfpmstrs->xrfp_duedate,
            // 'note4' => $rfpmstrs->created_by,
            // 'note5' => $rfpmstrs->xrfp_dept],
            function ($message) use ($emails, $company) {
                $message->subject('PhD - RFP Approval Task - ' . $company->com_name);
                $message->from($company->com_email); // Email Admin Fix
                $message->to($emails);
            }
        );

        $user = User::where('id', '=', $userApprover)->first(); // user siapa yang terima notif (lewat id)
        $useralt = User::where('id', '=', $altUserApprover)->first();

        $details = [
            'body' => 'There are updates on following RFP',
            'url' => 'rfpapproval',
            'nbr' => $rfpnumber,
            'note' => 'Approval is needed, Please check'
        ]; // isi data yang dioper


        $user->notify(new \App\Notifications\eventNotification($details));
        $useralt->notify(new \App\Notifications\eventNotification($details));
    }
}

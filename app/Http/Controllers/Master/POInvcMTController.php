<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\PoInvcEmail;
use Illuminate\Http\Request;
use Carbon\Carbon;

class POInvcMTController extends Controller
{
    //
    
    public function index(){

        $emailpoinvc = PoInvcEmail::first();
        $listemailrcv = '';

        is_null($emailpoinvc) ? $listemailrcv = [] : $listemailrcv = explode(';',$emailpoinvc->email_receiver);

        return view('setting.poinvcappr.emailset_approver',['data'=>$emailpoinvc,'list'=>$listemailrcv]);

    }

    public function store(Request $req){
        // dd($req->all());

        

        $this->validate($req, [
            'nameappr' => 'required',
            'emailappr' => 'required',
        ]);

        if(is_null($req->emailrcv)){
            alert()->error('Error', 'Please add at least ADD one email receiver');
            return redirect()->route('poinvcemail.index');
        }

        $listrcv = '';

        $poinvc_email = PoInvcEmail::firstOrNew(array('id' => '1'));
        $poinvc_email->email_invc = $req->emailappr;
        $poinvc_email->name_invc = $req->nameappr;
        foreach($req->emailrcv as $key => $v){
            if($req->op[$key] != 'R'){
                $listrcv .= $req->emailrcv[$key].';';
            }
                    
        }

        $listrcv = substr($listrcv, 0, strlen($listrcv) - 1);

        // dd($listrcv);

        $poinvc_email->email_receiver = $listrcv;

        $poinvc_email->save();


        alert()->success('Success', 'Invoice PO Email Successfully Updated');
        return redirect()->route('poinvcemail.index');
    }
}

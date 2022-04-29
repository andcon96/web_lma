<?php

namespace App\Services;

use App\Models\Master\Domain;
use App\Models\Master\PoInvcEmail;
use App\Models\Transaksi\POInvc;
use App\Models\Transaksi\SuratJalan;
use App\Models\Transaksi\SuratJalanDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class CreateTempTable
{
    public function createPOTemp($data){
        // WSA -> LMA_getPO
        Schema::create('temp_group', function ($table) {
            $table->string('po_nbr');
            $table->string('po_cust');
            $table->string('po_custname');
            $table->string('pod_line');
            $table->string('pod_part');
            $table->string('pod_partdesc');
            $table->string('pod_qty_ord');
            $table->string('pod_qty_rcvd');
            $table->string('pod_loc');
            $table->temporary();
        });

        foreach($data as $datas){
            DB::table('temp_group')->insert([
                'po_nbr' => $datas->t_ponbr,
                'po_cust' => $datas->t_suppnbr,
                'po_custname' => $datas->t_suppname,
                'pod_line' => $datas->t_line,
                'pod_part' => $datas->t_part,
                'pod_partdesc' => $datas->t_partdesc,
                'pod_qty_ord' => $datas->t_ord,
                'pod_qty_rcvd' => $datas->t_rcvd,
                'pod_loc' => $datas->t_loc,
            ]);
        }

        $table_po = DB::table('temp_group')->get();

        Schema::dropIfExists('temp_group');

        return $table_po;
    }

    public function createPOSessionTemp($data){
        // dd($data);

        $ponbr = $data['po_nbr'];
        $supp = $data['supphidden'];
        $partloc = $data['partloc'];
        $poline = $data['poline'];
        $qtyfg = $data['qtyfg'];
        $qtyreject = $data['qtyreject'];
        $qtyord = $data['poqtyord'];
        $qtyrcvd = $data['poqtyrcvd'];
        $popart = $data['popart'];
        $receiptdate = $data['receiptdate'];
        // $listnopol = implode(" , ", $datas['nopol']);
        $nopol = $data['nopol'];

        $remark = $data['remarkreceipt'];

        Schema::create('po_session', function ($table) {
            $table->string('po_nbr');
            $table->string('po_cust');
            $table->string('pod_line');
            $table->string('pod_part');
            $table->string('pod_qty_ord');
            $table->string('pod_qty_rcvd');
            $table->string('pod_loc');
            $table->decimal('pod_qty_fg');
            $table->decimal('pod_qty_rjct');
            $table->string('pod_remarks');
            $table->longText('pod_nopol')->nullable();
            $table->temporary();
        });

        foreach($poline as $datas => $a){
            DB::table('po_session')->insert([
                'po_nbr' => $ponbr,
                'po_cust' => $supp,
                'pod_line' => $a,
                'pod_part' => $popart[$datas],
                'pod_qty_ord' => $qtyord[$datas],
                'pod_qty_rcvd' => $qtyrcvd[$datas],
                'pod_loc' => $partloc[$datas],
                'pod_qty_fg' => $qtyfg[$datas],
                'pod_qty_rjct' => $qtyreject[$datas],
                'pod_remarks' => $remark[$datas],
                'pod_nopol' => $nopol,
            ]);
        }

        $po_session = DB::table('po_session')->get();

        Schema::dropIfExists('po_session');

        return $po_session;
    }
    
    public function createPOInvcTemp($data){
        Schema::dropIfExists('temp_poinvc');

        Schema::create('temp_poinvc',function ($table){
            $table->string('po_nbr');
            $table->string('invoice_nbr');
            $table->float('invoice_amt');
            $table->string('invoice_status');
            $table->string('email_status')->nullable();
        });

        foreach($data as $datas){
            $checksendemail =  POInvc::where('eh_ponbr','=',$datas->t_ponbr)->where('eh_invcnbr','=',$datas->t_invcnbr)->first();

            if(is_null($checksendemail)){
                DB::table('temp_poinvc')->insert([
                    'po_nbr' => $datas->t_ponbr,
                    'invoice_nbr' => $datas->t_invcnbr,
                    'invoice_amt' => $datas->t_bcinvcamt,
                    'invoice_status' => $datas->t_invcstatus,
                    'email_status' => 'Not Send'
                ]);
            }else{
                DB::table('temp_poinvc')->insert([
                    'po_nbr' => $datas->t_ponbr,
                    'invoice_nbr' => $datas->t_invcnbr,
                    'invoice_amt' => $datas->t_bcinvcamt,
                    'invoice_status' => $datas->t_invcstatus,
                    'email_status' => 'Send'
                ]);
            }
        }

        $table_poinvc = DB::table('temp_poinvc')->get();

        Schema::dropIfExists('temp_poinvc');

        return $table_poinvc;
    }

    public function createSOTemp($data){
        // WSA -> LMA_getPO
        Schema::create('temp_group', function ($table) {
            $table->string('so_nbr');
            $table->string('so_cust');
            $table->string('so_ship');
            $table->string('so_bill');
            $table->string('sod_line');
            $table->string('sod_part');
            $table->string('sod_part_desc');
            $table->string('sod_loc');
            $table->string('sod_qty_ord');
            $table->string('sod_qty_ship');
            $table->string('sod_qty_ongoing');
            $table->string('sod_price_ls');
            $table->temporary();
        });

        foreach($data as $datas){
            // Current Surat Jalan
            $listsj = SuratJalanDetail::with('getMaster')
                            ->whereRelation('getMaster','sj_status','New')
                            ->whereRelation('getMaster','sj_so_nbr',$datas->t_sonbr)
                            ->where('sj_line',$datas->t_soline)
                            ->where('sj_part',$datas->t_sopart)    
                            ->get();
            $qtysj = 0;
            foreach($listsj as $lists){
                $qtysj += $lists->sj_qty_input;
            }
            
            DB::table('temp_group')->insert([
                'so_nbr' => $datas->t_sonbr,
                'so_cust' => $datas->t_socust,
                'so_ship' => $datas->t_soship,
                'so_bill' => $datas->t_sobill,
                'sod_line' => $datas->t_soline,
                'sod_part' => $datas->t_sopart,
                'sod_part_desc' => $datas->t_sopartdesc,
                'sod_loc' => $datas->t_soloc,
                'sod_qty_ord' => $datas->t_soqtyord,
                'sod_qty_ship' => $datas->t_soqtyship,
                'sod_price_ls' => $datas->t_listprc,
                'sod_qty_ongoing' => $qtysj,
            ]);
        }

        $table_so = DB::table('temp_group')->get();


        Schema::dropIfExists('temp_group');

        return $table_so;
    }

    public function createNewLineSO($data){
        Schema::create('temp_group', function ($table) {
            $table->string('sod_nbr');
            $table->string('sod_part');
            $table->string('sod_qty_sj');
            $table->string('sod_price_ls');
            $table->temporary();
        });

        foreach($data['iddetail'] as $key => $datas){
            if($data['qtysj'][$key] != $data['qtyinp'][$key]){
                DB::table('temp_group')->insert([
                    'sod_nbr' => $data['sonbr'],
                    'sod_part' => 'Reject - '.$data['part'][$key],
                    'sod_qty_sj' => (int)$data['qtyinp'][$key] - (int)$data['qtysj'][$key],
                    'sod_price_ls' => $data['price'][$key]
                ]);
            }
        }

        $tableso = DB::table('temp_group')->get();

        Schema::dropIfExists('temp_group');

        return $tableso;
        
    }

    public function getRNSJ(){
        $prefix = Domain::where('domain_code',Session::get('domain'))->firstOrFail();
        if(substr($prefix->domain_sj_rn,0,2) != date('y')){
            $newrn = date('y').'0001';
        }else{
            $newrn = str_pad($prefix->domain_sj_rn + 1, 6, '0', STR_PAD_LEFT);
        }

        $newprefix = $prefix->domain_sj_prefix . $newrn;

        return [$newprefix,$newrn];
    }
}

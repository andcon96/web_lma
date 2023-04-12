<?php

namespace App\Services;

use App\Models\Master\CustMstr;
use App\Models\Master\Domain;
use App\Models\Master\PoInvcEmail;
use App\Models\Transaksi\POInvc;
use App\Models\Transaksi\POInvcApprHist;
use App\Models\Transaksi\SuratJalan;
use App\Models\Transaksi\SuratJalanDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Session;

class CreateTempTable
{
    public function createPOTemp($data){
        // WSA -> LMA_getPO
        Schema::dropIfExists('temp_group');
        Schema::create('temp_group', function ($table) {
            $table->string('po_domain');
            $table->string('po_nbr');
            $table->string('po_contract');
            $table->string('po_cust');
            $table->string('po_custname');
            $table->string('pod_line');
            $table->string('pod_part');
            $table->string('pod_partdesc');
            $table->string('pod_qty_ord');
            $table->string('pod_qty_rcvd');
            $table->string('pod_loc');
            $table->string('pod_site');
            $table->temporary();
        });

        foreach($data as $datas){
            DB::table('temp_group')->insert([
                'po_domain' => $datas->t_domain,
                'po_nbr' => $datas->t_ponbr,
                'po_contract' => $datas->t_pokontrak,
                'po_cust' => $datas->t_suppnbr,
                'po_custname' => $datas->t_suppname,
                'pod_line' => $datas->t_line,
                'pod_part' => $datas->t_part,
                'pod_partdesc' => $datas->t_partdesc,
                'pod_qty_ord' => $datas->t_ord,
                'pod_qty_rcvd' => $datas->t_rcvd,
                'pod_loc' => $datas->t_loc,
                'pod_site'=>$datas->t_site,
            ]);
        }

        $table_po = DB::table('temp_group')->get();

        $grouppo = DB::table('temp_group')->groupBy('po_nbr')->get();

        Schema::dropIfExists('temp_group');

        return [$table_po,$grouppo];
    }

    public function createPOSessionTemp($data){
        // dd($data);
        // $dom_search = $data['dom_search'];
        $ponbr = $data['po_nbr'];
        $supp = $data['supphidden'];
        $partloc = $data['partloc'];
        $partlot = $data['partlot'];
        $poline = $data['poline'];
        $qtyterima = $data['qtyterima'];
        $qtyfg = $data['qtyfg'];
        $qtyord = $data['poqtyord'];
        $qtyrcvd = $data['poqtyrcvd'];
        $popart = $data['popart'];
        $receiptdate = $data['receiptdate'];
        $effdate = $data['effdate'];
        // $listnopol = implode(" , ", $datas['nopol']);
        $nopol = $data['nopol'];

        $remark = $data['remarkreceipt'];

        Schema::create('po_session', function ($table) {
            // $table->string('dom_search');
            $table->string('po_nbr');
            $table->string('po_cust');
            $table->string('pod_line');
            $table->string('pod_part');
            $table->string('pod_qty_ord');
            $table->string('pod_qty_rcvd');
            $table->string('pod_loc')->nullable();
            $table->string('pod_lot')->nullable();
            $table->decimal('pod_qty_terima',15,2);
            $table->decimal('pod_qty_fg',15,2);
            $table->string('pod_remarks')->nullable();
            $table->longText('pod_nopol')->nullable();
            $table->date('pod_receiptdate');
            $table->date('pod_effdate');
            $table->temporary();
        });

        foreach($poline as $datas => $a){
            DB::table('po_session')->insert([
                // 'dom_search' => $dom_search,
                'po_nbr' => $ponbr,
                'po_cust' => $supp,
                'pod_line' => $a,
                'pod_part' => $popart[$datas],
                'pod_qty_ord' => $qtyord[$datas],
                'pod_qty_rcvd' => $qtyrcvd[$datas],
                'pod_loc' => $partloc[$datas],
                'pod_lot' => $partlot[$datas],
                'pod_qty_terima' => $qtyterima[$datas],
                'pod_qty_fg' => $qtyfg[$datas],
                'pod_remarks' => $remark,
                'pod_nopol' => $nopol,
                'pod_receiptdate' => $receiptdate,
                'pod_effdate' => $effdate,
            ]);
        }

        $po_session = DB::table('po_session')->get();

        Schema::dropIfExists('po_session');

        return $po_session;
    }
    
    public function createPOInvcTemp($data){
        Schema::dropIfExists('temp_poinvc');

        Schema::create('temp_poinvc',function ($table){
            $table->string('po_dom')->nullable();
            $table->string('po_nbr')->nullable();
            $table->longText('supp');
            $table->string('invoice_nbr');
            $table->float('invoice_amt_tc',15,2);
            $table->float('invoice_amt_bc',15,2);
            $table->string('invoice_status');
            $table->date('posting_date');
            $table->string('email_status')->nullable();
        });

        foreach($data as $datas){
            $checksendemail =  POInvc::where('dom','=',$datas->t_dom)->where('eh_invcnbr','=',$datas->t_invcnbr)->first();

            if(is_null($checksendemail)){
                DB::table('temp_poinvc')->insert([
                    'po_dom' => $datas->t_dom,
                    'po_nbr' => $datas->t_ponbr,
                    'supp' => $datas->t_suppcode.' - '.$datas->t_suppname,
                    'invoice_nbr' => $datas->t_invcnbr,
                    'invoice_amt_tc' => $datas->t_tcinvcamt,
                    'invoice_amt_bc' => $datas->t_bcinvcamt,
                    'invoice_status' => $datas->t_invcstatus,
                    'posting_date' => $datas->t_posdate,
                    'email_status' => 'Not Send'
                ]);
            }else{
                DB::table('temp_poinvc')->insert([
                    'po_dom' => $datas->t_dom,
                    'po_nbr' => $datas->t_ponbr,
                    'supp' => $datas->t_suppcode.' - '.$datas->t_suppname,
                    'invoice_nbr' => $datas->t_invcnbr,
                    'invoice_amt_tc' => $datas->t_tcinvcamt,
                    'invoice_amt_bc' => $datas->t_bcinvcamt,
                    'invoice_status' => $datas->t_invcstatus,
                    'posting_date' => $datas->t_posdate,
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
            $table->string('so_cust_name');
            $table->string('so_ship');
            $table->string('so_ship_name');
            $table->string('so_bill');
            $table->string('so_bill_name');
            $table->string('so_po'); // * 170522
            $table->string('so_duedate');
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

        $thisdomain = Session::get('domain');

        foreach($data as $datas){
            // Current Surat Jalan
            $listsj = SuratJalanDetail::with('getMaster')
                            ->whereRelation('getMaster','sj_status','New')
                            ->whereRelation('getMaster','sj_so_nbr',$datas->t_sonbr)
                            ->whereRelation('getMaster','sj_domain', $thisdomain)
                            ->where('sj_line',$datas->t_soline)
                            ->where('sj_part',$datas->t_sopart)    
                            ->get();
            $qtysj = 0;
            foreach($listsj as $lists){
                $qtysj += $lists->sj_qty_input;
            }

            $cust_name = CustMstr::where('cust_code','=',$datas->t_socust)->first();
            $ship_name = CustMstr::where('cust_code','=',$datas->t_soship)->first();
            $bill_name = CustMstr::where('cust_code','=',$datas->t_sobill)->first();
            
            if($datas->t_soqtyord > 0){
                DB::table('temp_group')->insert([
                    'so_nbr' => $datas->t_sonbr,
                    'so_cust' => $datas->t_socust,
                    'so_cust_name' => $cust_name->cust_name ?? '',
                    'so_ship' => $datas->t_soship,
                    'so_ship_name' => $ship_name->cust_name ?? '',
                    'so_bill' => $datas->t_sobill,
                    'so_bill_name' => $bill_name->cust_name ?? '',
                    'so_po' => $datas->t_sopo, // *170522
                    'so_duedate' => $datas->t_soduedate,
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
        }

        $table_so = DB::table('temp_group')->get();

        $groupso = DB::table('temp_group')->groupBy('so_nbr')->get();


        Schema::dropIfExists('temp_group');

        return [$table_so,$groupso];
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
                    'sod_part' => $data['part'][$key],
                    'sod_qty_sj' => number_format($data['qtyinp'][$key] - $data['qtysj'][$key],2),
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

    public function tempDetailItem($data){
        Schema::dropIfExists('temp_detailitem');

        Schema::create('temp_detailitem',function ($table){
            $table->string('t_dom')->nullable();
            $table->string('t_part')->nullable();
            $table->longText('t_desc1')->nullable();
            $table->longText('t_desc2')->nullable();
            $table->string('t_um')->nullable();
            $table->string('t_location')->nullable();
            $table->string('t_lot')->nullable();
            $table->float('t_qtyoh',15,2);
            $table->float('t_qtyinput_web',15,2);
            $table->float('t_qtysisa',15,2);
        });

        // dd($data);

        foreach($data as $datawsa){
            $location = (string) $datawsa->t_location;
            if($location == ""){ //jika location kosong jadi null
                $location = null;
            }

            $lot = (string) $datawsa->t_lot;
            if($lot == ""){
                $lot = null;
            }
            $sjweb = SuratJalanDetail::with('getMaster')
                    ->whereRelation('getMaster','sj_status','New')
                    ->whereRelation('getMaster','sj_domain', $datawsa->t_dom)
                    ->where('sj_part', $datawsa->t_part)
                    ->where('sj_loc', $location)
                    ->where('sj_lot', $lot)
                    ->get();

            // dump($sjweb);

            $qtyinput_web = 0;
            foreach($sjweb as $sjweblist){
                $qtyinput_web += $sjweblist->sj_qty_input;
            }

            DB::table('temp_detailitem')->insert([
                't_dom' => $datawsa->t_dom,
                't_part' => $datawsa->t_part,
                't_desc1' => $datawsa->t_desc1,
                't_desc2' => $datawsa->t_desc2,
                't_um' => $datawsa->t_um,
                't_location' => $datawsa->t_location,
                't_lot' => $datawsa->t_lot,
                't_qtyoh' => $datawsa->t_qtyoh,
                't_qtyinput_web' => $qtyinput_web,
                't_qtysisa' => $datawsa->t_qtyoh - $qtyinput_web,
            ]);

        }

        $table_detail = DB::table('temp_detailitem')->orderBy('t_location','asc')->get();

        Schema::dropIfExists('temp_detailitem');
        return $table_detail;

    }

    
    public function tempDetailItemAll($data){
        Schema::dropIfExists('temp_detailitem');

        Schema::create('temp_detailitem',function ($table){
            $table->string('t_dom')->nullable();
            $table->string('t_part')->nullable();
            $table->longText('t_desc1')->nullable();
            $table->longText('t_desc2')->nullable();
            $table->string('t_um')->nullable();
            $table->string('t_location')->nullable();
            $table->string('t_lot')->nullable();
            $table->float('t_qtyoh',15,2);
            $table->float('t_qtyinput_web',15,2);
            $table->float('t_qtysisa',15,2);
        });

        foreach($data as $datawsa){
            $location = (string) $datawsa['t_location'];
            if($location == ""){ //jika location kosong jadi null
                $location = null;
            }

            $lot = (string) $datawsa['t_lot'];
            if($lot == ""){
                $lot = null;
            }
            $sjweb = SuratJalanDetail::with('getMaster')
                    ->whereRelation('getMaster','sj_status','New')
                    ->whereRelation('getMaster','sj_domain', $datawsa['t_dom'])
                    ->where('sj_part', $datawsa['t_part'])
                    ->where('sj_loc', $location)
                    ->where('sj_lot', $lot)
                    ->get();
            
            // dump($sjweb);

            $qtyinput_web = 0;
            foreach($sjweb as $sjweblist){
                $qtyinput_web += $sjweblist->sj_qty_input;
            }

            DB::table('temp_detailitem')->insert([
                't_dom' => $datawsa['t_dom'],
                't_part' => $datawsa['t_part'],
                't_desc1' => $datawsa['t_desc1'],
                't_desc2' => $datawsa['t_desc2'],
                't_um' => $datawsa['t_um'],
                't_location' => $datawsa['t_location'],
                't_lot' => $datawsa['t_lot'],
                't_qtyoh' => $datawsa['t_qtyoh'],
                't_qtyinput_web' => $qtyinput_web,
                't_qtysisa' => $datawsa['t_qtyoh'] - $qtyinput_web,
            ]);

        }

        $table_detail = DB::table('temp_detailitem')->select(
                    DB::raw('COUNT(*) as count'),
                    DB::raw('t_part'),
                    DB::raw('SUM(t_qtyinput_web) as total')
                )
                ->groupBy('t_part')
                ->pluck('total','t_part')
                ->toArray();

        Schema::dropIfExists('temp_detailitem');
        return $table_detail;

    }


}

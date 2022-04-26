<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Domain;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DomainController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $domain = Domain::get();

        return view('setting.domain.index',compact('domain'));
    }

    public function store(Request $request)
    {
        DB::beginTransaction();
        try{
            foreach($request->iddomain as $key => $data){
                $domain = Domain::firstOrNew(['id' => $data]);
                if($request->op[$key] == 'R'){
                    $domain->delete();
                }else{
                    $domain->domain_code = $request->code[$key];
                    $domain->domain_desc = $request->desc[$key];
                    $domain->save();
                }
            }

            DB::commit();
            alert()->success('Success','Domain Berhasil Diupdate');
        }catch(Exception $e){

            DB::rollBack();
            alert()->error('Error','Terjadi kesalahan, Silahkan dicoba lagi');
        }

        return back();
    }

    
}

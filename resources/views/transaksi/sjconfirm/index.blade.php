@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Confirm Surat Jalan</li>
</ol>
@endsection


@section('content')

<form action="{{route('sjconfirm.index')}}" method="GET">
    <div class="form-group row">
        <div class="col-lg-2 col-md-4">
            <label for="sjnbr" class="col-form-label text-md-right">{{ __('SJ Number') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <input id="sjnbr" type="text" class="form-control" name="sjnbr" value="{{ request()->input('sjnbr') }}">
        </div>
        <div class="col-lg-2 col-md-4">
            <label for="sonbr" class="col-form-label text-md-right">{{ __('SO Number') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <input id="sonbr" type="text" class="form-control" name="sonbr" value="{{ request()->input('sonbr')}}">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-2 col-md-4">
            <label for="cust" class="col-form-label text-md-right">{{ __('Customer') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <select name="cust" id="cust" class="form-control">
                <option value="">Select Data</option>
                @foreach($cust as $custs)
                <option value="{{$custs->sj_so_cust}}">{{$custs->sj_so_cust}} -- {{$custs->getDetailCust->cust_name ?? ''}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2 col-md-4">
            <label for="status" class="col-form-label text-md-right">{{ __('Status') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <select name="status" id="status" class="form-control">
                <option value="">Select Data</option>
                <option value="New">New</option>
                <option value="Closed">Closed</option>
                <option value="On Process">Cancelled</option>
            </select>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-2 col-md-4">
            <label for="tanggalsj" class="col-form-label text-md-right">{{ __('Tanggal SJ') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <input id="tanggalsj" type="text" class="form-control" name="tanggalsj" autocomplete="off" value="{{ request()->input('tanggalsj') ? request()->input('tanggalsj') : '' }}">
        </div>
        <div class="col-lg-2 col-md-4">
            <label for="nopol" class="col-form-label text-md-right">{{ __('No. Polisi') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <input id="nopol" type="text" class="form-control" name="nopol" value="{{ request()->input('nopol') }}">
        </div>
        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12 mt-xl-0 mt-lg-0 mt-3">
            <button class="btn bt-ref" id="btnsearch" value="search">Search</button>
            <button class="btn bt-action ml-2" id='btnrefresh' style="width: 40px !important"><i class="fa fa-sync"></i></button>
        </div>
    </div>
</form>

<form method="get" action="{{ route('sjconfirmexcel') }}">
    <div class="form-group row">
        <div class="col-lg-4 col-md-4">  
            <input id="h_sjnbr" type="hidden" class="form-control" name="h_sjnbr" value="{{ request()->input('sjnbr') }}">
            <input id="h_sonbr" type="hidden" class="form-control" name="h_sonbr" value="{{ request()->input('sonbr')}}">
            <input id="h_status" type="hidden" class="form-control" name="h_status">
            <input id="h_tanggalsj" type="hidden" class="form-control" name="h_tanggalsj" value="{{ request()->input('tanggalsj') ? request()->input('tanggalsj') : '' }}">
            <input id="h_customer" type="hidden" class="form-control" name="h_customer">
            <input id="h_nopol" type="hidden" class="form-control" name="h_nopol" value="{{ request()->input('nopol') }}">

            <button type="submit" class="btn btn-success my-1">EXPORT EXCEL</button>

        </div>
    </div>
</form>

<div id="tabledata">
    @include('transaksi.sjconfirm.index-table')
</div>

@endsection

@section('scripts')
<script>
    $("#status,#cust").select2({
        width: '100%'
    });
    
    function resetSearch(){
        $('#cust').val('');
        $('#sjnbr').val('');
        $('#sonbr').val('');
        $('#status').val('');
        $('#tanggalsj').val('');
        $('#nopol').val('');
    }

    $(document).ready(function() {
        var cur_url = window.location.href;

        let paramString = cur_url.split('?')[1];
        let queryString = new URLSearchParams(paramString);

        let customer = queryString.get('cust');
        let status = queryString.get('status');

        document.getElementById('h_customer').value= customer;
        document.getElementById('h_status').value= status;

        $('#cust').val(customer).trigger('change');
        $('#status').val(status).trigger('change');
    });
    
    $(document).on('click', '#btnrefresh', function(){
        resetSearch();
    });
</script>
@endsection
@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Confirm Surat Jalan</li>
</ol>
@endsection


@section('content')

<form action="{{route('sjconfirm.index')}}" method="GET">
    <div class="form-group row offset-lg-1">
        <div class="col-lg-2 col-md-4">
            <label for="sjnbr" class="col-form-label text-md-right" style="margin-left:25px">{{ __('SJ Number') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <input id="sjnbr" type="text" class="form-control" name="sjnbr" value="{{ request()->input('sjnbr') }}">
        </div>
        <div class="col-lg-2 col-md-4">
            <label for="sonbr" class="col-form-label text-md-right" style="margin-left:25px">{{ __('SO Number') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <input id="sonbr" type="text" class="form-control" name="sonbr" value="{{ request()->input('sonbr')}}">
        </div>
    </div>
    <div class="form-group row offset-lg-1">
        <div class="col-lg-2 col-md-4">
            <label for="cust" class="col-form-label text-md-right" style="margin-left:25px">{{ __('Customer') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <select name="cust" id="cust" class="form-control">
                <option value="">Select Data</option>
                @foreach($cust as $custs)
                <option value="{{$custs->sj_so_cust}}">{{$custs->sj_so_cust}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2 col-md-4">
            <label for="status" class="col-form-label text-md-right" style="margin-left:25px">{{ __('Status') }}</label>
        </div>
        <div class="col-xl-2 col-lg-2 col-md-8 col-sm-12 col-xs-12">
            <select name="status" id="status" class="form-control">
                <option value="">Select Data</option>
                <option value="New">New</option>
                <option value="Closed">Closed</option>
                <option value="On Process">Cancelled</option>
            </select>
        </div>
        <div class="offset-md-3 offset-lg-0 offset-xl-0 offset-sm-0 offset-xs-0" id='btn'>
            <button class="btn bt-ref" id="btnsearch" value="search">Search</button>
            <button class="btn bt-action ml-2" id='btnrefresh' style="width: 40px !important"><i class="fa fa-sync"></i></button>

        </div>
    </div>
</form>

<div class="row col-12">
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
    }

    $(document).ready(function() {
        var cur_url = window.location.href;

        let paramString = cur_url.split('?')[1];
        let queryString = new URLSearchParams(paramString);

        let customer = queryString.get('cust');
        let status = queryString.get('status');

        $('#cust').val(customer).trigger('change');
        $('#status').val(status).trigger('change');
    });
    
    $(document).on('click', '#btnrefresh', function(){
        resetSearch();
    });
</script>
@endsection
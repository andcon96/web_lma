@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Browse Sent Email Approval</li>
</ol>
@endsection

@section('content')

<form action="{{route('browseHistSent')}}" method="GET">
    <div class="form-group row">
        <div class="col-lg-2 col-md-4">
            <label for="ponbr" class="col-form-label text-md-right">{{ __('PO No.') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
            <input id="ponbr" type="text" class="form-control" name="ponbr" value="{{ request()->input('ponbr') }}">
        </div>
        <div class="col-lg-2 col-md-4">
            <label for="pocon" class="col-form-label text-md-right">{{ __('PO Contract') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
            <input id="pocon" type="text" class="form-control" name="pocon" value="{{ request()->input('pocon') }}">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-2 col-md-4">
            <label for="supp" class="col-form-label text-md-right">{{ __('Supplier') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
            <select name="supp" id="supp" class="form-control">
                <option value="">Select Data</option>
                @foreach ($supps as $supp )
                <option value="{{$supp->ph_supp}}">{{$supp->ph_supp}} - {{$supp->ph_suppname}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2 col-md-4">
            <label for="receiptdate" class="col-form-label text-md-right">{{ __('Receipt Date') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
            <input id="receiptdate" type="text" class="form-control" name="receiptdate" autocomplete="off" value="{{request()->input('receiptdate') ? request()->input('receiptdate') : '' }}">
        </div>
        <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 col-xs-12 mt-xl-0 mt-lg-0 mt-3">
            <button class="btn bt-ref" id="btnsearch" value="search">Search</button>
            <button class="btn bt-action ml-2" id='btnrefresh' style="width: 40px !important"><i class="fa fa-sync"></i></button>
        </div>
    </div>
</form>

<!-- tombol expert excel -->
<form method="get" action="{{ route('exportExcel') }}">
    <div class="form-group row">
        <div class="col-lg-4 col-md-4">
            <input type="hidden" id="h_ponbr" name="h_ponbr" value="{{ request()->input('ponbr') }}"/>
            <input type="hidden" id="h_pocon" name="h_pocon" value="{{ request()->input('pocon') }}"/>
            <input type="hidden" id="h_supp" name="h_supp" value="" />
            <input type="hidden" id="h_receiptdate" name="h_receiptdate" value="{{ request()->input('receiptdate') }}"/>

            <button type="submit" class="btn btn-success my-3">EXPORT EXCEL</button>
        </div>
    </div>
</form>




<div id="tabledata">
    @include('transaksi.poapproval.table-browsehist_sentmail')
</div>

@endsection

@section('scripts')
<script>
    $("#supp").select2({
        width: '100%'
    });

    $("#receiptdate").datepicker({
        dateFormat: 'yy-mm-dd',
    });

    function resetSearch() {
        $('#ponbr').val('');
        $('#supp').val('');
        $('#pocon').val('');
        $('#receiptdate').val('');
    }

    $(document).ready(function() {
        var cur_url = window.location.href;

        let paramString = cur_url.split('?')[1];
        let queryString = new URLSearchParams(paramString);

        let supp = queryString.get('supp');


        console.log(supp);
        document.getElementById('h_supp').value = supp;

        $('#supp').val(supp).trigger('change');
    });

    $(document).on('click', '#btnrefresh', function() {
        resetSearch();
    });
        
</script>
@endsection
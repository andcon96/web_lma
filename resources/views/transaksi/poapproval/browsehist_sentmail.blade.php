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
            <label for="invno" class="col-form-label text-md-right">{{ __('Invoice No.') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
            <input id="invno" type="text" class="form-control" name="invno" value="{{ request()->input('invno') }}">
        </div>
        <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 col-xs-12 mt-xl-0 mt-lg-0 mt-3">
            <button class="btn bt-ref" id="btnsearch" value="search">Search</button>
            <button class="btn bt-action ml-2" id='btnrefresh' style="width: 40px !important"><i class="fa fa-sync"></i></button>
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
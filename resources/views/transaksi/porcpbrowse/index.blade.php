@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Browse PO Reciept</li>
</ol>
@endsection

@section('content')

<form action="{{route('poreceiptbrw.index')}}" method="GET">
    <div class="form-group row offset-lg-1">
        <div class="col-lg-2 col-md-4">
            <label for="ponbr" class="col-form-label text-md-right" style="margin-left:25px">{{ __('PO No.') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <input id="ponbr" type="text" class="form-control" name="ponbr" value="{{ request()->input('ponbr') }}">
        </div>

    </div>
    <div class="form-group row offset-lg-1">
        <div class="col-lg-2 col-md-4">
            <label for="supp" class="col-form-label text-md-right" style="margin-left:25px">{{ __('Supplier') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <select name="supp" id="supp" class="form-control">
                <option value="">Select Data</option>
                @foreach ($supps as $supp )
                    <option value="{{$supp->ph_supp}}">{{$supp->ph_supp}} - {{$supp->ph_suppname}}</option>
                @endforeach
            </select>
        </div>
        <div class="offset-md-3 offset-lg-0 offset-xl-0 offset-sm-0 offset-xs-0" id='btn'>
            <button class="btn bt-ref" id="btnsearch" value="search">Search</button>
            <button class="btn bt-action ml-2" id='btnrefresh' style="width: 40px !important"><i class="fa fa-sync"></i></button>
        </div>
    </div>
</form>

<div id="tabledata">
    @include('transaksi.porcpbrowse.table-index')
</div>

@endsection

@section('scripts')
<script>
    $("#supp").select2({
        width: '100%'
    });
    
    function resetSearch(){
        $('#ponbr').val('');
        $('#supp').val('');
    }

    $(document).ready(function() {
        var cur_url = window.location.href;

        let paramString = cur_url.split('?')[1];
        let queryString = new URLSearchParams(paramString);

        let supp = queryString.get('supp');

        $('#supp').val(supp).trigger('change');
    });
    
    $(document).on('click', '#btnrefresh', function(){
        resetSearch();
    });
</script>
@endsection
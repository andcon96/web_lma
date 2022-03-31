@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Browse Surat Jalan</li>
</ol>
@endsection


@section('content')

<form action="{{route('browseSJ')}}" method="GET">
    <div class="form-group row offset-lg-1">
        <label for="sjnbr" class="col-form-label text-md-right" style="margin-left:25px">{{ __('SO Number') }}</label>
        <div class="col-xl-2 col-lg-2 col-md-8 col-sm-12 col-xs-12">
            <input id="sjnbr" type="text" class="form-control" name="sjnbr">
        </div>

        <div class="offset-md-3 offset-lg-0 offset-xl-0 offset-sm-0 offset-xs-0" id='btn'>
            <input type="submit" class="btn bt-ref" id="btnsearch" value="Search" style="margin-left:15px;" />
        </div>
    </div>
</form>

<div class="row col-12">
    @include('transaksi.suratjalan.browse-table')
</div>

@endsection

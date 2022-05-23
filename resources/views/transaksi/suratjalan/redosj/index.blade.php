@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Create Surat Jalan</li>
</ol>
@endsection


@section('content')

<form action="{{route('searchChangeSJ')}}" method="GET">
    <div class="form-group row">
        <label for="sonbr" class="col-form-label text-md-right" style="margin-left:25px">{{ __('SJ Number') }}</label>
        <div class="col-xl-2 col-lg-2 col-md-8 col-sm-12 col-xs-12">
            <input type="text" name="sj" class="form-control" value="{{$data->sj_nbr}}" readonly>
            <input type="hidden" name="idsj" value="{{$data->id}}">
            <input type="hidden" name="nopol" value="{{$data->sj_nopol}}">
        </div>
        <label for="sonbr" class="col-form-label text-md-right" style="margin-left:25px">{{ __('SO Number') }}</label>
        <div class="col-xl-2 col-lg-2 col-md-8 col-sm-12 col-xs-12">
            <input id="sonbr" type="text" class="form-control" name="sonbr">
        </div>

        <div class="offset-md-3 offset-lg-0 offset-xl-0 offset-sm-0 offset-xs-0" id='btn'>
            <input type="submit" class="btn bt-ref" id="btnsearch" value="Search" style="margin-left:15px;" />
            <a href="{{route('browseSJ')}}" class="btn bt-ref">Back</a>
        </div>
    </div>
</form>

@endsection

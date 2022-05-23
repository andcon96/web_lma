@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Create Surat Jalan</li>
</ol>
@endsection


@section('content')


<form method="post" action="{{route('updateChangeSJ')}}" id='submit'>
    @method('POST')
    @csrf

    <div class="form-group row md-form offset-lg-1">
        <label for="sj" class="col-md-2 col-form-label text-md-right">{{ __('SJ Number') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <input id="sj" type="text" class="form-control" name="sj" value="{{$sjnbr}}" readonly>
        </div>
    </div>
    <div class="form-group row md-form offset-lg-1">
        <label for="sonbr" class="col-md-2 col-form-label text-md-right">{{ __('SO Number') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <input id="sonbr" type="text" class="form-control" name="sonbr" value="{{$so[0]->so_nbr ?? ''}}" readonly>
        </div>
        <label for="customer" class="col-md-2 col-form-label text-md-right">{{ __('Customer') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <input id="customer" type="text" class="form-control" value="{{$so[0]->so_cust  ?? ''}} -- {{$so[0]->so_cust_name  ?? ''}}" readonly>
            <input id="customer" type="hidden" class="form-control" name="customer" value="{{$so[0]->so_cust  ?? ''}}" readonly>
        </div>
    </div>
    
    <input id="shipto" type="hidden" class="form-control" name="shipto" value="{{$so[0]->so_ship}}" readonly>
    <input id="billto" type="hidden" class="form-control" name="billto" value="{{$so[0]->so_bill}}" readonly>

    <div class="form-group row md-form offset-lg-1">
        <label for="sopo" class="col-md-2 col-form-label text-md-right">{{ __('SO PO') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <input id="sopo" type="text" class="form-control" name="sopo" value="{{$so[0]->so_po  ?? ''}}" readonly>
        </div>
        <label for="nopol" class="col-md-2 col-form-label text-md-right">{{ __('No Polis') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <input id="nopol" type="text" class="form-control" name="nopol" value="{{$nopol}}">
        </div>
    </div>

    @include('transaksi.suratjalan.redosj.create-table')

    @if($so)    
    <div class="form-group row md-form offset-lg-1">
        <div class="col-md-10" style="text-align: center;">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="cbsubmit" required>
                <label class="custom-control-label" for="cbsubmit">Confirm to submit</label>
            </div>
        </div>
    </div>

    <div class="form-group row offset-lg-1 col-lg-10">
        <div class="float-right col-lg-12">
            <input type="submit" name="submit" id='s_btnconf' value='Submit' class="btn bt-action float-right mt-3">
            <a href="{{route('browseSJ')}}" class="btn bt-action float-right mt-3 mr-3">Cancel</a>
            <button type="button" class="btn btn-info float-right mt-3" id="s_btnloading" style="display:none;">
                <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
            </button>

        </div>
    </div>
    @endif
</form>


@endsection

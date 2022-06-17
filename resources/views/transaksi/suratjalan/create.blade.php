@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Create Surat Jalan</li>
</ol>
@endsection


@section('content')


<form method="post" action="{{route('suratjalan.store')}}" id='submit'>
    @method('POST')
    @csrf

    <div class="form-group row md-form offset-lg-1">
        <label for="sonbr" class="col-md-2 col-form-label text-md-right">{{ __('SO Number') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <input id="sonbr" type="text" class="form-control" name="sonbr" value="{{$so[0]->so_nbr}}" readonly>
        </div>
        <label for="customer" class="col-md-2 col-form-label text-md-right">{{ __('Customer') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <input id="customer" type="text" class="form-control" value="{{$so[0]->so_cust}} -- {{$so[0]->so_cust_name}}" readonly>
            <input id="customer" type="hidden" class="form-control" name="customer" value="{{$so[0]->so_cust}}" readonly>
        </div>
    </div>
    <input id="shipto" type="hidden" class="form-control" name="shipto" value="{{$so[0]->so_ship}}" readonly>
    <input id="billto" type="hidden" class="form-control" name="billto" value="{{$so[0]->so_bill}}" readonly>

    {{-- <div class="form-group row md-form offset-lg-1">
        <label for="shipto" class="col-md-2 col-form-label text-md-right">{{ __('Ship To') }}</label>
    <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
        <input id="shipto" type="text" class="form-control" value="{{$so[0]->so_ship}} -- {{$so[0]->so_ship_name}}" readonly>
    </div>
    <label for="billto" class="col-md-2 col-form-label text-md-right">{{ __('Bill To') }}</label>
    <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
        <input id="billto" type="text" class="form-control" value="{{$so[0]->so_bill}} -- {{$so[0]->so_bill_name}}" readonly>
    </div>
    </div> --}}
    <div class="form-group row md-form offset-lg-1">
        <label for="sopo" class="col-md-2 col-form-label text-md-right">{{ __('SO PO') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <input id="sopo" type="text" class="form-control" name="sopo" value="{{$so[0]->so_po}}" readonly>
        </div>
        <label for="nopol" class="col-md-2 col-form-label text-md-right">{{ __('No Polis') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <input id="nopol" type="text" class="form-control" name="nopol" value="" required>
        </div>
    </div>

    @include('transaksi.suratjalan.create-table')

    <div class="row md-form offset-lg-1">
        <div class="col-md-10" style="text-align: center;">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" class="custom-control-input" id="cbsubmit" required>
                <label class="custom-control-label" for="cbsubmit">Confirm to submit</label>
            </div>
        </div>
    </div>


    <div class="row">
        <div class="col-lg-2 col-md-2 col-sm-12 py-2 ml-auto">
            <input type="submit" name="submit" id='s_btnconf' value='Submit' class="btn bt-action" style="width: 100% !important;">
        </div>
        <div class="col-lg-2 col-md-2 col-sm-12 py-2">
            <a href="{{route('searchSO',['sjnbr'=>$so[0]->so_cust])}}" class="btn bt-action" style="width: 100% !important;">Cancel</a>
            <button type="button" class="btn btn-info" id="s_btnloading" style="display:none;">
                <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
            </button>
        </div>
    </div>


</form>


@endsection


@section('scripts')

<script>
    $(function() {
        $("#effdate").datepicker({
            dateFormat: 'dd/mm/yy'
        });
        $("#shipdate").datepicker({
            dateFormat: 'dd/mm/yy'
        });
    });

    $(document).on('hide.bs.modal', '#detailModal,#deleteModal', function() {
        if (confirm("Are you sure, you want to close?")) return true;
        else return false;
    });

    $('#update').submit(function(event) {
        var regqty = /^(\s*|\d+\.\d*|\d+)$/;
        var qtyreq = document.getElementById("m_qtyrec").value;

        if (!regqty.test(qtyreq)) {
            alert('Qty Requested Must be number or "." ');
            return false;
        } else {
            document.getElementById('btnclose').style.display = 'none';
            document.getElementById('btnconf').style.display = 'none';
            document.getElementById('btnloading').style.display = '';
        }

    });


    $('#submit').submit(function(event) {
        document.getElementById('s_btnconf').style.display = 'none';
        document.getElementById('s_btnloading').style.display = '';
    });
</script>

@endsection
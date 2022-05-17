@extends('layout.layout')

@section('menu_name','Edit Surat Jalan')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">Edit Surat Jalan</li>
</ol>
@endsection

@section('content')
<form action="{{ route('suratjalan.update',$data->id) }}" id="submit" method="POST">
    @csrf
    @method('PUT')
    <div class="row">
        <input type="hidden" name="idmaster" value="{{$data->id}}">
        <div class="form-group row col-md-12">
            <label for="sjnbr" class="col-md-2 col-form-label text-md-right">SJ Number</label>
            <div class="col-md-3">
                <input id="sjnbr" type="text" class="form-control" name="sjnbr" value="{{$data->sj_nbr}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
            <label for="sonbr" class="col-md-3 col-form-label text-md-right">SO Number</label>
            <div class="col-md-3">
                <input id="sonbr" type="text" class="form-control" name="sonbr" value="{{$data->sj_so_nbr}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="socust" class="col-md-2 col-form-label text-md-right">Customer</label>
            <div class="col-md-3">
                <input id="socust" type="text" class="form-control" name="socust" value="{{$data->sj_so_cust}} -- {{$data->getDetailCust->cust_name}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
            {{-- <label for="shipto" class="col-md-3 col-form-label text-md-right">Ship To</label>
            <div class="col-md-3">
                <input id="shipto" type="text" class="form-control" name="shipto" value="{{$data->sj_so_ship}} -- {{$data->getDetailShip->cust_name}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="billto" class="col-md-2 col-form-label text-md-right">Bill To</label>
            <div class="col-md-3">
                <input id="billto" type="text" class="form-control" name="billto" value="{{$data->sj_so_bill}} -- {{$data->getDetailBill->cust_name}}" autocomplete="off" maxlength="24" autofocus disabled>
            </div> --}}
            <label for="status" class="col-md-3 col-form-label text-md-right">Status</label>
            <div class="col-md-3">
                <input id="status" type="text" class="form-control" name="status" value="{{$data->sj_status}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="sopo" class="col-md-2 col-form-label text-md-right">{{ __('SO PO') }}</label>
            <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
                <input id="sopo" type="text" class="form-control" name="sopo" value="{{$data->sj_so_po}}" readonly>
            </div>
            <label for="nopol" class="col-md-3 col-form-label text-md-right">{{ __('No Polis') }}</label>
            <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
                <input id="nopol" type="text" class="form-control" name="nopol" value="{{$data->sj_nopol}}" required>
            </div>
        </div>
        <div class="form-group row col-md-12">
            @include('transaksi.suratjalan.edit-browse-table')
        </div>
        <div class="form-group row col-md-12">
            <div class="offset-md-1 col-md-10" style="margin-top:90px;">
                <div class="float-right">
                    <a href="{{route('browseSJ')}}" id="btnback" class="btn btn-success bt-action">Back</a>
                    <button type="submit" class="btn btn-success bt-action btn-focus" id="btnconf">Save</button>
                    <button type="button" class="btn bt-action" id="btnloading" style="display:none">
                        <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
                    </button>
                </div>
            </div>
        </div>
    </div>

</form>
@endsection


@section('scripts')
<script>
    $(document).on('submit', '#submit', function(e) {
        document.getElementById('btnconf').style.display = 'none';
        document.getElementById('btnback').style.display = 'none';
        document.getElementById('btnloading').style.display = '';
    });

    $(document).on('change', '.qaddel', function(){
        var checkbox = $(this), // Selected or current checkbox
            value = checkbox.val(); // Value of checkbox

        if (checkbox.is(':checked')) {
            $(this).closest("tr").find('.operation').val('R');
        } else {
            $(this).closest("tr").find('.operation').val('M');
        }
    });
</script>
@endsection
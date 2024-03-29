@extends('layout.layout')

@section('menu_name','Confirm Surat Jalan')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">Confirm Surat Jalan</li>
</ol>
@endsection

@section('content')
<form action="{{ route('sjconfirm.update',$data->id) }}" id="submit" method="POST">
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
                <input id="socust" type="text" class="form-control" name="socust" value="{{$data->sj_so_cust}} -- {{$data->getDetailCust->cust_name ?? ''}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
            {{-- <label for="shipto" class="col-md-3 col-form-label text-md-right">Ship To</label>
            <div class="col-md-3">
                <input id="shipto" type="text" class="form-control" name="shipto" value="{{$data->sj_so_ship}} -- {{$data->getDetailShip->cust_name ?? ''}}" autocomplete="off" maxlength="24" autofocus readonly>
        </div>
    </div>
    <div class="form-group row col-md-12">
        <label for="billto" class="col-md-2 col-form-label text-md-right">Bill To</label>
        <div class="col-md-3">
            <input id="billto" type="text" class="form-control" name="billto" value="{{$data->sj_so_bill}} -- {{$data->getDetailBill->cust_name ?? ''}}" autocomplete="off" maxlength="24" autofocus disabled>
        </div> --}}
        <label for="sopo" class="col-md-3 col-form-label text-md-right">SO PO</label>
        <div class="col-md-3">
            <input id="sopo" type="text" class="form-control" name="sopo" value="{{$data->sj_so_po}}" autocomplete="off" maxlength="24" autofocus readonly>
        </div>
    </div>
    <div class="form-group row col-md-12">
        <label for="effdate" class="col-md-2 col-form-label text-md-right">Date</label>
        <div class="col-md-3">
            <input id="effdate" type="text" class="form-control" name="effdate" value="{{\Carbon\Carbon::now()->toDateString()}}" autocomplete="off" maxlength="24" required>
        </div>
        <label for="nopol" class="col-md-3 col-form-label text-md-right">No Polis</label>
        <div class="col-md-3">
            <input id="nopol" type="text" class="form-control" name="nopol" value="{{$data->sj_nopol}}" autocomplete="off" maxlength="24" required>
        </div>
    </div>
    <div class="form-group row col-md-12">
        <label for="remarks" class="col-md-2 col-form-label text-md-right">Remarks</label>
        <div class="col-md-9">
            <input id="remarks" type="text" class="form-control" name="remarks" value="{{$data->sj_nbr}}" maxlength="24" autocomplete="off">
        </div>
    </div>
    <div class="form-group row col-md-12 py-4">
        @include('transaksi.sjconfirm.edit-table')
    </div>
    <div class="form-group row col-md-12">
        <label for="potongdp" class="col-form-label col-md-2" style="margin-left:95px">{{ __('Potong DP') }}</label>
        <div class="col-md-2">
            <input id="potongdp" type="number" class="form-control" name="potongdp" min="0" step="0.01" value="{{ old('potongdp') ? old('potongdp') : $data->sj_potongdp }}" autocomplete="off">
        </div>
    </div>
    <div class="form-group row col-md-12">
        <label for="exkapal" class="col-form-label col-md-2" style="margin-left:95px">{{ __('Ex Kapal') }}</label>
        <div class="col-md-2">
            <input id="exkapal" type="text" class="form-control" name="exkapal" value="{{ old('exkapal') ? old('exkapal') : $data->sj_exkapal }}" maxlength="24" autocomplete="on">
        </div>
    </div>
    <div class="form-group row col-md-12">
        <label for="exgudang" class="col-form-label col-md-2" style="margin-left:95px">{{ __('Ex Gudang') }}</label>
        <div class="col-md-2">
            <input id="exgudang" type="text" class="form-control" name="exgudang" value="{{ old('exgudang') ? old('exgudang') : $data->sj_exgudang }}" maxlength="24" autocomplete="on">
        </div>
    </div>
    <div class="form-group row col-md-12">
        <label for="qtykarung" class="col-form-label col-md-2" style="margin-left:95px">{{ __('Jumlah Karung') }}</label>
        <div class="col-md-2">
            <input id="qtykarung" type="number" class="form-control" name="qtykarung" min="0" step="1" value="{{ old('qtykarung') ? old('qtykarung') : $data->sj_qtykarung }}" autocomplete="off">
        </div>
    </div>
    <div class="form-group row col-md-12">
        <label for="transportirname" class="col-form-label col-md-2" style="margin-left:95px">{{ __('Nama Transportir') }}</label>
        <div class="col-md-2">
            <input id="transportirname" type="text" class="form-control" name="transportirname" value="{{ old('transportirname') ? old('transportirname') : $data->sj_transportir_name }}" maxlength="24" autocomplete="on">
        </div>
    </div>
    <div class="form-group row col-md-12">
        <div class="offset-md-1 col-md-10" style="margin-top:90px;">
            <div class="float-right">
                <a href="{{route('sjconfirm.index')}}" id="btnback" class="btn btn-success bt-action">Back</a>
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
    $("#effdate").datepicker({
        dateFormat: 'yy-mm-dd'
    });

    $(document).on('submit', '#submit', function(e) {
        document.getElementById('btnconf').style.display = 'none';
        document.getElementById('btnback').style.display = 'none';
        document.getElementById('btnloading').style.display = '';
    });

    $("#btnconf").click(function(e) {
        e.preventDefault();
        Swal.fire({
            title: "Confirm Surat Jalan ?",
            text: "Data will be receipt in QAD",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Send",
            closeOnConfirm: false
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                $('#submit').submit();
            }
        })
    });

    $(document).on('change', '.qaddel', function() {
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
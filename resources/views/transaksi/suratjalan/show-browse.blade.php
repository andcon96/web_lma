@extends('layout.layout')

@section('menu_name','Confirm Surat Jalan')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">View Surat Jalan</li>
</ol>
@endsection

@section('content')
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
                <input id="socust" type="text" class="form-control" name="socust" value="{{$data->sj_so_cust}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
            {{-- <label for="shipto" class="col-md-3 col-form-label text-md-right">Ship To</label>
            <div class="col-md-3">
                <input id="shipto" type="text" class="form-control" name="shipto" value="{{$data->sj_so_ship}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="billto" class="col-md-2 col-form-label text-md-right">Bill To</label>
            <div class="col-md-3">
                <input id="billto" type="text" class="form-control" name="billto" value="{{$data->sj_so_bill}}" autocomplete="off" maxlength="24" autofocus disabled>
            </div> --}}
            <label for="status" class="col-md-3 col-form-label text-md-right">Status</label>
            <div class="col-md-3">
                <input id="status" type="text" class="form-control" name="status" value="{{$data->sj_status}}" autocomplete="off" maxlength="24" autofocus readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="effdate" class="col-md-2 col-form-label text-md-right">Eff Date</label>
            <div class="col-md-3">
                <input id="effdate" type="text" class="form-control" name="effdate" value="{{$data->sj_eff_date}}" autocomplete="off" maxlength="24" readonly>
            </div>
            <label for="nopol" class="col-md-3 col-form-label text-md-right">No Polis</label>
            <div class="col-md-3">
                <input id="nopol" type="text" class="form-control" name="nopol" value="{{$data->sj_nopol}}" autocomplete="off" maxlength="24" readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="sopo" class="col-md-2 col-form-label text-md-right">SO PO</label>
            <div class="col-md-3">
                <input id="sopo" type="text" class="form-control" name="sopo" value="{{$data->sj_so_po}}" autocomplete="off" readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="remarks" class="col-md-2 col-form-label text-md-right">Remarks</label>
            <div class="col-md-9">
                <input id="remarks" type="text" class="form-control" name="remarks" value="{{$data->sj_remark}}" autocomplete="off" readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            @include('transaksi.suratjalan.show-browse-table')
        </div>
        <div class="form-group row col-md-12">
            <label for="exkapal" class="col-form-label col-md-2" style="margin-left:95px">{{ __('Ex Kapal') }}</label>
            <div class="col-md-2">
                <input id="exkapal" type="text" class="form-control" name="exkapal" value="{{ $data->sj_exkapal }}" maxlength="24" autocomplete="off" readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="exgudang" class="col-form-label col-md-2" style="margin-left:95px">{{ __('Ex Gudang') }}</label>
            <div class="col-md-2">
                <input id="exgudang" type="text" class="form-control" name="exgudang" value="{{ $data->sj_exgudang }}" maxlength="24" autocomplete="off" readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="qtykarung" class="col-form-label col-md-2" style="margin-left:95px">{{ __('Jumlah Karung') }}</label>
            <div class="col-md-2">
                <input id="qtykarung" type="number" class="form-control" name="qtykarung" min="0" step="1" value="{{ $data->sj_qtykarung }}" autocomplete="off" readonly>
            </div>
        </div>
        <div class="form-group row col-md-12">
            <label for="transportirname" class="col-form-label col-md-2" style="margin-left:95px">{{ __('Nama Transportir') }}</label>
            <div class="col-md-2">
                <input id="transportirname" type="text" class="form-control" name="transportirname" value="{{ $data->sj_transportir_name }}" maxlength="24" autocomplete="off" readonly >
            </div>
        </div>
        <div class="form-group row col-md-12">
            <div class="offset-md-1 col-md-10" style="margin-top:90px;">
                <div class="float-right">
                    <a href="{{route('browseSJ')}}" id="btnback" class="btn btn-success bt-action">Back</a>
                    <button type="button" class="btn bt-action" id="btnloading" style="display:none">
                        <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection


@section('scripts')
<script>
    $( "#effdate" ).datepicker({
        dateFormat : 'yy-mm-dd'
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
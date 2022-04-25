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
            <input id="customer" type="text" class="form-control" name="customer" value="{{$so[0]->so_cust}}" readonly>
        </div>
    </div>

    <div class="form-group row md-form offset-lg-1">
        <label for="shipto" class="col-md-2 col-form-label text-md-right">{{ __('Ship To') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <input id="shipto" type="text" class="form-control" name="shipto" value="{{$so[0]->so_ship}}" readonly>
        </div>
        <label for="billto" class="col-md-2 col-form-label text-md-right">{{ __('Bill To') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <input id="billto" type="text" class="form-control" name="billto" value="{{$so[0]->so_bill}}" readonly>
        </div>
    </div>
    <div class="form-group row md-form offset-lg-1">
        <label for="nopol" class="col-md-2 col-form-label text-md-right">{{ __('No Polis') }}</label>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <input id="nopol" type="text" class="form-control" name="nopol" value="" readonly>
        </div>
    </div>

    @include('transaksi.suratjalan.create-table')

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
            <a href="{{route('suratjalan.index')}}" class="btn bt-action float-right mt-3 mr-3">Cancel</a>
            <button type="button" class="btn btn-info float-right mt-3" id="s_btnloading" style="display:none;">
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

    $(document).on('click', '.editUser', function() { // Click to only happen on announce links

        var id = $(this).data('id');
        var suratjalan = $(this).data('sj');
        var ponbr = $(this).data('nbr');
        var line = $(this).data('line');
        var part = $(this).data('part');
        var desc = $(this).data('desc');
        var qtyord = $(this).data('qtyord');
        var qtyship = $(this).data('qtyship');
        var qtyopen = $(this).data('qtyopen');
        var effdate = $(this).data('effdate');
        var shipdate = $(this).data('shipdate');
        //var um = $(this).data('um');
        var site = $(this).data('site');
        var loc = $(this).data('loc');
        var lot = $(this).data('lot');
        var ref = $(this).data('ref');
        var qtyrec = $(this).data('qtyrcvd');


        if (effdate == '') {
            var new_shipdate = '';
            var new_effdate = '';
        } else {
            var split_effdate = effdate.split('-');
            var split_shipdate = shipdate.split('-');

            var new_effdate = split_effdate[2].concat('/', split_effdate[1], '/', split_effdate[0]);
            var new_shipdate = split_shipdate[2].concat('/', split_shipdate[1], '/', split_shipdate[0]);
        }



        document.getElementById('rcpid').value = id;
        document.getElementById("m_sj").value = suratjalan;
        document.getElementById("m_ponbr").value = ponbr;
        document.getElementById("m_line").value = line;
        document.getElementById("m_itemcode").value = part;
        document.getElementById("m_itemdesc").value = desc;
        document.getElementById("m_qtyord").value = qtyord;
        //document.getElementById("m_qtyopen").value = qtyopen;
        document.getElementById("m_qtyship").value = qtyship;
        document.getElementById("m_qtyrec").value = qtyrec;

        document.getElementById("effdate").value = new_effdate;
        document.getElementById("shipdate").value = new_shipdate;
        //document.getElementById("m_site").value = site;
        document.getElementById("m_loc").value = loc;
        document.getElementById("m_lot").value = lot;
        document.getElementById("m_ref").value = ref;

        $('#m_qtyrec').attr({
            "max": qtyship,
        });

        jQuery.ajax({
            type: "get",
            url: "{{URL::to("
            detailreceipt ") }}",
            data: {
                suratjalan: suratjalan,
                ponbr: ponbr,
                line: line
            },
            success: function(data) {
                //$('tbody').html(data);
                console.log(data);
                document.getElementById("d_um").innerHTML = data[0]['xpod_um'];
                document.getElementById("m_um").value = data[0]['xpod_um'];
                document.getElementById("m_loc").value = data[0]['xpod_loc'];
                document.getElementById("m_lot").value = data[0]['xpod_lot'];
            }
        });
    });
</script>

@endsection
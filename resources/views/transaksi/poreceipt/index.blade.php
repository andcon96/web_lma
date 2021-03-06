@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Purchase Order Receipt</li>
</ol>
@endsection


@section('content')

<form action="{{route('searchPO')}}" method="GET">
    <div class="form-group row">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <label for="sjnbr" class="col-form-label text-md-right">PO No.</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <input id="sjnbr" type="text" class="form-control" name="sjnbr" required>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <label for="pokontrak" class="col-form-label text-md-right">PO Kontrak</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <input id="pokontrak" type="text" class="form-control" name="pokontrak" required>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <label for="suppcode" class="col-form-label text-md-right">Supplier Name</label>
        </div>
        <div class="col-xl-4 col-lg-4 col-md-9 col-sm-12 col-xs-12">
            <select name="suppcode" id="suppcode" class="form-control" required>
                <option value="">Select Data</option>
                @foreach ($suppdat as $supps )
                <option value="{{$supps->supp_code}}">{{$supps->supp_code}} - {{$supps->supp_name}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-xl-3 col-lg-3 col-md-12 col-sm-12 col-xs-12">
            <input type="submit" class="btn bt-ref" style="width: 100%;" id="btnsearch" value="Search" />
        </div>
    </div>
</form>

@endsection

@section('scripts')

<script>
    $("#suppcode").select2({
        width: '100%'
    });

    $(document).on('hide.bs.modal', '#detailModal', function() {
        if (confirm("Are you sure, you want to close?")) return true;
        else return false;
    });

    $('#update').submit(function(event) {
        document.getElementById('btnclose').style.display = 'none';
        document.getElementById('btnconf').style.display = 'none';
        document.getElementById('btnloading').style.display = '';
    });

    $(document).on('change', '#sjnbr', function(e) {
        var valsj = document.getElementById('sjnbr').value;
        if (valsj === "") {
            
            document.getElementById('pokontrak').required = true;
            document.getElementById('suppcode').required = true;
        } else {
            document.getElementById('pokontrak').required = false;
            document.getElementById('suppcode').required = false;
        }
    });
    $(document).on('change', '#pokontrak', function(e) {
        var valkon = document.getElementById('pokontrak').value;
        if (valkon === "") {
            
            document.getElementById('sjnbr').required = true;
            document.getElementById('suppcode').required = true;
        } else {
            document.getElementById('sjnbr').required = false;
            document.getElementById('suppcode').required = false;
        }
    });

    $(document).on('change', '#suppcode', function(e) {
        
        var val = document.getElementById('suppcode').value;
        var valsj = document.getElementById('sjnbr').value;
        var valkon = document.getElementById('pokontrak').value;

        if (val === "") {
            document.getElementById('sjnbr').required = true;
            document.getElementById('pokontrak').required = true;
        } else {
            
            document.getElementById('sjnbr').required = false;
            document.getElementById('pokontrak').required = false;
        }
        
    });


</script>

@endsection
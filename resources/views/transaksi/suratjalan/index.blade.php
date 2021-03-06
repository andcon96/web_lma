@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Create Surat Jalan</li>
</ol>
@endsection


@section('content')

<form action="{{route('searchSO')}}" method="GET">
    <div class="form-group row">
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <label for="sonbr" class="col-form-label text-md-right">SO No.</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <input type="text" id="sonbr" name="sonbr" class="form-control" value="" required /> 
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <label for="sjnbr" class="col-form-label text-md-right">{{ __('Customer') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-xs-12">
            <select id="sjnbr" class="form-control" name="sjnbr" required>
                    <option value="">Select Data</option>
                @foreach ( $custdat as $show )
                    <option value="{{$show->cust_code}}">{{$show->cust_code}} -- {{$show->cust_name}}</option>
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
    $("#sjnbr").select2({
        width: '100%'
    });

    $(document).on('change', '#sonbr', function(e) {
        
        var sonbr = document.getElementById('sonbr').value;

        if (sonbr === "") {
            document.getElementById('sjnbr').required = true;
        } else {
            document.getElementById('sjnbr').required = false;
        }
        
    });

    $(document).on('change', '#sjnbr', function(e) {
        
        var sjnbr = document.getElementById('sjnbr').value;

        if (sjnbr === "") {
            document.getElementById('sonbr').required = true;
        } else {
            document.getElementById('sonbr').required = false;
        }
        
    });
</script>

@endsection

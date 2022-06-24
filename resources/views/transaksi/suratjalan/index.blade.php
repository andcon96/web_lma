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
        <label for="sonbr" class="col-form-label text-md-right">{{ __('SO No.') }}</label>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <input type="text" id="sonbr" name="sonbr" class="form-control" value="" required /> 
        </div>
        <label for="sjnbr" class="col-form-label text-md-right" style="margin-left:25px">{{ __('Customer') }}</label>
        <div class="col-xl-4 col-lg-4 col-md-8 col-sm-12 col-xs-12">
            <select id="sjnbr" class="form-control" name="sjnbr" required>
                    <option value="">Select Data</option>
                @foreach ( $custdat as $show )
                    <option value="{{$show->cust_code}}">{{$show->cust_code}} -- {{$show->cust_name}}</option>
                @endforeach
            </select>
        </div>

        <div class="offset-md-3 offset-lg-0 offset-xl-0 offset-sm-0 offset-xs-0 col-sm-12 col-xs-12 mt-sm-2 mt-xs-2" id='btn'>
            <input type="submit" class="btn bt-ref" id="btnsearch" value="Search" style="margin-left:15px;" />
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

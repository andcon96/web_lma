@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Purchase Order Invoice Approval</li>
</ol>
@endsection


@section('content')

<form id="searchinvc" action="{{route('searchpoinvc')}}" method="GET">
    <div class="form-group row">
        <label for="ponbr" class="col-form-label text-md-right">{{ __('Invoice Reference') }}</label>
        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-xs-12">
            <input id="ponbr" type="text" class="form-control" name="ponbr">
        </div>
        <!-- <label for="receiptdate" class="col-form-label text-md-right" style="margin-left:25px">{{ __('Receipt Date') }}</label>
        <div class="col-xl-2 col-lg-2 col-md-8 col-sm-12 col-xs-12">
            <input id="receiptdate" type="text" class="form-control" name="receiptdate"
                value="{{ Carbon\Carbon::parse(now())->format('d-m-Y')  }}" readonly>
        </div> -->

        <div class="col-xl-2 col-lg-2 col-md-2 col-sm-12 col-xs-12">
            <input type="submit" class="btn bt-ref" id="btnsearch" value="Search" style="width: 100%;" />
            <button type="button" class="btn btn-info" id="s_btnloading" style="display:none;width: 100%;">
                <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
            </button>
        </div>
    </div>
</form>

@endsection

@section('scripts')

<script>

    $('#searchinvc').submit(function(event) {
        document.getElementById('btnsearch').style.display = 'none';
        document.getElementById('s_btnloading').style.display = '';
    });

</script>

@endsection
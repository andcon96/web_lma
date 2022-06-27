@extends('layout.layout')

@section('menu_name','Stock Item Report')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Report</a></li>
    <li class="breadcrumb-item active">Hutang Customer Report</li>
</ol>
@endsection

@section('content')
<form action="{{route('hutangcust.store')}}" method="post" id="submit">
    <div class="form-group row mb-3" style="margin-bottom: 0px !important;">
        {{ method_field('post') }}
        {{ csrf_field() }}
        <div class="col-md-2">
            <input type="submit" class="btn bt-ref" id="btnload" value="Load Data" />
        </div>
        <div class="col-md-4">
            @if ($lastrun =="")
                <label style="display: flex; align-items: center;">Last Load :  - </label>
            @else
                <label style="display: flex; align-items: center;">Last Load : {{ $lastrun->created_at }}</label>
            @endif
        </div>
    </div>
</form>

<form action="{{route('hutangcust.index')}}" method="GET">
    <div class="form-group row mt-4" style="margin-bottom: 0px !important;">
        <label for="invoice_nbr" class="col-form-label col-md-2">Invoice No.</label>
        <div class="col-md-3">
            <input id="invoice_nbr" name="invoice_nbr" type="text" class="form-control" autocomplete="off" value="{{ request()->input('invoice_nbr') }}" />
        </div>
        <label for="cust" class="col-form-label col-md-1">Customer</label>
        <div class="col-md-3">
            <select id="cust" name="cust" class="form-control">
                <option value="">Select Data</option>
                @foreach ($cust as $custshow )
                    <option value="{{$custshow->hutang_custnbr}}">{{$custshow->hutang_custnbr}} - {{$custshow->hutang_cust}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <button class="btn bt-ref" id="btnsearch" value="search">Search</button>
            <button class="btn bt-action ml-2" id='btnrefresh' style="width: 40px !important"><i class="fa fa-sync"></i></button>
        </div>
    </div>
</form>

<div class="table-responsive col-lg-12 col-md-2 tag-container mt-3">

    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 10%;">Domain</th>
                <th style="width: 15%;">Invoice No.</th>
                <th style="width: 15%;">Invoice Date</th>
                <th style="width: 30%;">Customer</th>
                <th style="width: 20%;">Total</th>
            </tr>
        </thead>
        <tbody>
            @include('transaksi.report.hutangcustomer.table-view')
        </tbody>
        <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
        <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
        <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
    </table>

</div>




@endsection

@section('scripts')

<script type="text/javascript">
  $("#cust").select2({
    width: '100%'
  });

  function resetSearch(){
        $('#invoice_nbr').val('');
        $('#cust').val('');
  }

  $(document).ready(function() {
        var cur_url = window.location.href;

        let paramString = cur_url.split('?')[1];
        let queryString = new URLSearchParams(paramString);

        let cust = queryString.get('cust');


        $('#cust').val(cust).trigger('change');
  });

  $(document).on('click', '#btnrefresh', function(){
        resetSearch();
  });
</script>

@endsection
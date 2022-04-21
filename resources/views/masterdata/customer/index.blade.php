@extends('layout.layout')

@section('menu_name','Stock Item Report')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{url('/')}}">Master Data</a></li>
  <li class="breadcrumb-item active">Customer Master</li>
</ol>
@endsection

@section('content')

<!-- Page Heading -->

<div class="form-group row" style="margin-bottom:0px !important;margin-left:1px;">

  <form action="{{route('custmstr.store')}}" method="post" id="submit">
    {{ method_field('post') }}
    {{ csrf_field() }}
    <div class="col-md-2">
      <input type="submit" class="btn bt-ref" id="btnload" value="Load Data" />
      <!-- <button class="btn bt-action" id='btnrefresh' style="margin-left: 10px; width: 40px !important"><i class="fa fa-sync"></i></button> -->
    </div>
  </form>

  <div class="col-md-4">
    @if ($lastrun =="")
    <label style="display: flex; align-items: center;">Last Load : - </label>
    @else
    <label style="display: flex; align-items: center;">Last Load : {{ $lastrun->created_at }}</label>
    @endif
  </div>

</div>


<form class="mt-3" action="{{route('custmstr.index')}}" method="GET">
  <div class="form-group row" style="margin-bottom:0px !important;margin-left:1px;">
    <label for="cust" class="col-form-label col-md-3">{{ __('Customer') }}</label>
    <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
      <select name="cust" id="cust" class="form-control">
        <option value="">Select Data</option>
        @foreach ($custsearch as $custshow )
        <option value="{{$custshow->cust_code}}">{{$custshow->cust_code}} - {{$custshow->cust_name}}</option>
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
        <!-- <th style="width: 15%;">Site</th> -->
        <th style="width: 10%;">Customer Code</th>
        <th style="width: 25%;">Customer Name</th>
        <th style="width: 35%;">Customer Address</th>
      </tr>
    </thead>
    <tbody>
      @include('masterdata.customer.table-view')
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

  function resetSearch() {
    $('#cust').val('');
  }

  $(document).ready(function() {
    var cur_url = window.location.href;

    let paramString = cur_url.split('?')[1];
    let queryString = new URLSearchParams(paramString);

    let cust = queryString.get('cust');

    $('#cust').val(cust).trigger('change');
  });

  $(document).on('click', '#btnrefresh', function() {
    resetSearch();
  });
</script>

@endsection
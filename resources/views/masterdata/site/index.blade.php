@extends('layout.layout')

@section('menu_name','Stock Item Report')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{url('/')}}">Master Data</a></li>
  <li class="breadcrumb-item active">Site Master</li>
</ol>
@endsection

@section('content')

<!-- Page Heading -->

<div class="form-group row" style="margin-bottom:0px !important;margin-left:1px;">

  <form action="{{route('sitemstr.store')}}" method="post" id="submit">
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


<div class="table-responsive col-lg-12 col-md-2 tag-container mt-3">

  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
      <tr>
        <!-- <th style="width: 15%;">Site</th> -->
        <th style="width: 10%;">Domain</th>
        <th style="width: 30%;">Entity</th>
        <th style="width: 35%;">Site</th>
      </tr>
    </thead>
    <tbody>
      @include('masterdata.site.table-view')
    </tbody>
    <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
    <input type="hidden" name="hidden_column_name" id="hidden_column_name" value="id" />
    <input type="hidden" name="hidden_sort_type" id="hidden_sort_type" value="asc" />
  </table>

</div>

@endsection

@section('scripts')

<script type="text/javascript">
  $("#loc").select2({
    width: '100%'
  });

  function resetSearch() {
    $('#loc').val('');
  }

  $(document).ready(function() {
    var cur_url = window.location.href;

    let paramString = cur_url.split('?')[1];
    let queryString = new URLSearchParams(paramString);

    // let cust = queryString.get('loc');

    // $('#loc').val(loc).trigger('change');
  });

  $(document).on('click', '#btnrefresh', function() {
    resetSearch();
  });
</script>

@endsection
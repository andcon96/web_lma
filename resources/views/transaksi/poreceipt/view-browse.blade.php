@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
  <li class="breadcrumb-item active">Purchase Order Invoice Approval</li>
</ol>
@endsection


@section('content')

  @include('transaksi.poreceipt.table-view-browse')

  <div class="form-group col-md-12 p-0">
    <div class="col-md-12" style="float: right;">
      <a id="back_btn" class="btn btn-danger float-right" href="{{url()->previous()}}" style="margin-top:10px; margin-left:5px;">Back</a>
    </div>
  </div>



@endsection


@section('scripts')

<script>

</script>

@endsection
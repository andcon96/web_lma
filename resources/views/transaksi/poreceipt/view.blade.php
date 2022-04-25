@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
  <li class="breadcrumb-item active">Purchase Order Receipt</li>
</ol>
@endsection


@section('content')


<form method="post" action="{{route('submitReceipt')}}" id='submit'>
  <div class="row">
    <label for="receiptdate" class="col-form-label col-md-3" style="margin-left:25px">{{ __('Receipt Date') }}</label>
    <div class="col-xl-2 col-lg-2 col-md-8 col-sm-12 col-xs-12">
      <input id="receiptdate" type="text" class="form-control" name="receiptdate" value="{{ Carbon\Carbon::parse(now())->format('d-m-Y')  }}">
    </div>
  </div>


  @method('POST')
  @csrf

  @include('transaksi.poreceipt.table-view')

  <div class="row">
    <label for="remarkreceipt" class="col-form-label col-md-3">{{ __('Remark') }}</label>
    <div class="col-md-8">
      <input type="text" class="form-control" name="remarkreceipt" maxlength="24"/>
    </div>
  </div>

  <div class="table-responsive col-lg-12 col-md-12 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
    <table class="table table-bordered table-nopol" id="nopolTable" width="100%" cellspacing="0">
      <thead>
        <tr>
          <th>Nomor Polisi</th>
          <th></th>
        </tr>
      </thead>
      <tbody id="nopolDetail">
      </tbody>
    </table>
  </div>


  <div class="form-group row md-form">
    <div class="col-md-12" style="text-align: center;">
      <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="cbsubmit" required>
        <label class="custom-control-label" for="cbsubmit">Confirm to submit</label>
      </div>
    </div>
  </div>

  <div class="form-group col-md-12">
    <div class="col-md-12" style="float: right;">
      <a id="back_btn" class="btn btn-danger float-right" href="{{url()->previous()}}" style="margin-top:10px; margin-left:5px;">Cancel</a>
      <input type="submit" name="submit" id='s_btnconf' value='Submit' class="btn btn-info float-right" style="margin-top:10px">
      <button type="button" class="btn btn-info float-right" id="s_btnloading" style="display:none;margin-top: 10px;">
        <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
      </button>
    </div>
  </div>

</form>


@endsection


@section('scripts')

<script>
  $("#receiptdate").datepicker({
    dateFormat: 'yy-mm-dd',
    maxDate: 0,
  });

  $("#addrow").on("click", function() {



    var rowCount = $('#nopolTable tr').length;

    var currow = rowCount - 2;

    // alert(currow);

    var lastline = parseInt($('#nopolTable tr:eq(' + currow + ') td:eq(0) input[type="number"]').val()) + 1;

    if (lastline !== lastline) {
      // check apa NaN
      lastline = 1;
    }

    // alert(lastline);

    var newRow = $("<tr>");
    var cols = "";

    cols += '<td>';
    cols += '<input type="text" class="form-control nopol" name="nopol[]" required />';
    cols += '</td>';

    cols += '<td data-title="Action"><input type="button" class="ibtnDel btn btn-danger btn-focus"  value="Delete"></td>';
    cols += '<input type="hidden" class="op" name="op[]" value="A"/>';
    cols += '</tr>'
    counter++;

    newRow.append(cols);
    $("#nopolDetail").append(newRow);

    // selectRefresh();
  });

  $("table.table-nopol").on("click", ".ibtnDel", function(event) {
    var row = $(this).closest("tr");
    var line = row.find(".line").val();
    // var colCount = $("#createTable tr").length;


    if (line == counter - 1) {
      // kalo line terakhir delete kurangin counter
      counter -= 1
    }

    $(this).closest("tr").remove();

    // if(colCount == 2){
    //   // Row table kosong. sisa header & footer
    //   counter = 1;
    // }

  });

  $('#submit').submit(function(event) {
    document.getElementById('s_btnconf').style.display = 'none';
    document.getElementById('back_btn').style.display = 'none';
    document.getElementById('s_btnloading').style.display = '';
  });
</script>

@endsection
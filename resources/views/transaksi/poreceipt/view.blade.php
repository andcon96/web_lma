@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
  <li class="breadcrumb-item active">Purchase Order Receipt</li>
</ol>
@endsection


@section('content')


<form method="post" action="{{route('submitReceipt')}}" id='submit'>
  <div class="row py-2">
    <label for="receiptdate" class="col-form-label col-md-2 text-md-right">{{ __('Receipt Date') }}</label>
    <div class="col-md-2">
      <input id="receiptdate" type="text" class="form-control" name="receiptdate" value="{{ ($sessionpo!=null) ? $sessionpo->first()->pod_receiptdate : Carbon\Carbon::parse(now())->format('Y-m-d')  }}" required>
    </div>
    <label for="po_nbr" class="col-form-label col-md-1 text-md-right">{{ __('PO No.') }}</label>
    <div class="col-md-2">
      <input id="po_nbr" type="text" class="form-control" name="po_nbr" value="{{$receiptdetail->first()->po_nbr}}" readonly>
    </div>
    <label for="po_kontrak" class="col-form-label col-md-3 text-md-right">{{ __('PO Contract') }}</label>
    <div class="col-md-2">
      <input id="po_kontrak" type="text" class="form-control" name="po_kontrak" value="{{$receiptdetail->first()->po_contract}}" readonly>
    </div>
  </div>
  <div class="row py-2">
    <label for="supp" class="col-form-label col-md-2 text-md-right">{{ __('Supplier') }}</label>
    <div class="col-md-4">
      <input id="supp" type="text" class="form-control" name="supp" value="{{$receiptdetail->first()->po_cust}} -- {{$receiptdetail->first()->po_custname}}" readonly>
      <input type="hidden" name="supphidden" value="{{$receiptdetail->first()->po_cust}}" />
      <input type="hidden" name="suppnamehidden" value="{{$receiptdetail->first()->po_custname}}" />
    </div>
  </div>

  @method('POST')
  @csrf

  @include('transaksi.poreceipt.table-view')
  </div>
  <div class="row py-2">
    <label for="remarkreceipt" class="col-form-label col-md-3" style="margin-left:25px">{{ __('Remark') }}</label>
    <div class="col-md-8">
      <input type="text" class="form-control" name="remarkreceipt" maxlength="24" value="{{($sessionpo!=null) ? $sessionpo->first()->pod_remarks : ''}}" />
    </div>
  </div>
  <div class="row py-2">
    <label for="nopol" class="col-form-label col-md-3" style="margin-left:25px">{{ __('No. Polisi') }}</label>
    <div class="col-md-3">
      <textarea type="text" class="form-control" name="nopol" rows="2" maxlength="30">{{($sessionpo!=null) ? $sessionpo->first()->pod_nopol : ''}}</textarea>
    </div>
  </div>
  <div class="row py-2">
    <label for="exkapal" class="col-form-label col-md-3" style="margin-left: 25px;">{{ __('Ex Kapal') }}</label>
    <div class="col-md-3">
      <input type="text" id="exkapal" class="form-control" name="exkapal" maxlength="24" value="" />
    </div>
  </div>
  <div class="row py-2">
    <label for="exgudang" class="col-form-label col-md-3" style="margin-left: 25px;">{{ __('Ex Gudang') }}</label>
    <div class="col-md-3">
      <input type="text" id="exgudang" class="form-control" name="exgudang" maxlength="24" value="" />
    </div>
  </div>
  <div class="row py-2">
    <label for="qtykarung" class="col-form-label col-md-3" style="margin-left: 25px;">{{ __('Jumlah Karung') }}</label>
    <div class="col-md-3">
      <input type="number" class="form-control" name="qtykarung" min="0" step="0.01" value="" />
    </div>
  </div>

  <!-- <div class="table-responsive col-lg-6 col-md-6 tag-container offset-3" style="overflow-x: auto; display: block;white-space: nowrap;">
    <table class="table table-bordered table-nopol" id="nopolTable" width="100%" cellspacing="0">
      <thead>
        <tr>
          <th>Nomor Polisi</th>
          <th></th>
        </tr>
      </thead>
      <tbody id="nopolDetail">
      </tbody>
      <tfoot>
        <tr>
          <td colspan="2">
            <input type="button" class="btn btn-lg btn-block btn-focus" id="addrow" value="Add Nomor Polisi" style="background-color:#1234A5; color:white; font-size:16px" />
          </td>
        </tr>
      </tfoot>
    </table>
  </div> -->

  <div class="form-group row md-form">
    <div class="col-md-12" style="text-align: center;">
      <div class="custom-control custom-checkbox">
        <input type="checkbox" class="custom-control-input" id="cbsubmit" required>
        <label class="custom-control-label" for="cbsubmit">Confirm to submit</label>
      </div>
    </div>
  </div>

  <div class="row">
    <div class="col-lg-2 col-md-2 col-sm-12 py-2 ml-auto">
      <a id="back_btn" class="btn btn-danger" style="width: 100%;" href="{{route('searchPO',['ponbr'=>$receiptdetail->first()->po_contract,'supp'=>$receiptdetail->first()->po_cust])}}">Cancel</a>
    </div>
    <div class="col-lg-2 col-md-2 col-sm-12 py-2">
      <input type="submit" name="submit" id='s_btnconf' value='Submit' class="btn btn-info" style="width: 100%;">
      <button type="button" class="btn btn-info float-right" id="s_btnloading" style="display:none;">
        <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
      </button>
    </div>
  </div>

</form>


@endsection


@section('scripts')

<script>
  var counter = 1;
  $("#receiptdate").datepicker({
    dateFormat: 'yy-mm-dd',
    maxDate: 0,
  });



  // $("#addrow").on("click", function() {



  //   var rowCount = $('#nopolTable tr').length;

  //   var currow = rowCount - 2;

  //   // alert(currow);

  //   var lastline = parseInt($('#nopolTable tr:eq(' + currow + ') td:eq(0) input[type="number"]').val()) + 1;

  //   if (lastline !== lastline) {
  //     // check apa NaN
  //     lastline = 1;
  //   }

  //   // alert(lastline);

  //   var newRow = $("<tr>");
  //   var cols = "";

  //   cols += '<td>';
  //   cols += '<input type="text" class="form-control nopol" name="nopol[]" maxlength="14" required />';
  //   cols += '</td>';

  //   cols += '<td data-title="Action"><input type="button" class="ibtnDel btn btn-danger btn-focus"  value="Delete"></td>';
  //   cols += '<input type="hidden" class="op" name="op[]" value="A"/>';
  //   cols += '</tr>'
  //   counter++;

  //   newRow.append(cols);
  //   $("#nopolDetail").append(newRow);

  //   // selectRefresh();
  // });

  // $("table.table-nopol").on("click", ".ibtnDel", function(event) {
  //   var row = $(this).closest("tr");
  //   var line = row.find(".line").val();
  //   // var colCount = $("#createTable tr").length;


  //   if (line == counter - 1) {
  //     // kalo line terakhir delete kurangin counter
  //     counter -= 1
  //   }

  //   $(this).closest("tr").remove();

  //   // if(colCount == 2){
  //   //   // Row table kosong. sisa header & footer
  //   //   counter = 1;
  //   // }

  // });

  $('#submit').submit(function(event) {
    document.getElementById('s_btnconf').style.display = 'none';
    document.getElementById('back_btn').style.display = 'none';
    document.getElementById('s_btnloading').style.display = '';
  });
</script>

@endsection
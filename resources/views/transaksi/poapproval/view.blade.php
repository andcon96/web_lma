@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
  <li class="breadcrumb-item active">Purchase Order Invoice Approval</li>
</ol>
@endsection


@section('content')


<form method="post" action="{{route('sendMailApproval')}}" id='submit'>
  @method('POST')
  @csrf

  @include('transaksi.poapproval.table-view')

  <div class="form-group col-md-12 p-0">
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
  $(function() {
    $("#effdate").datepicker({
      dateFormat: 'dd/mm/yy'
    });
    $("#shipdate").datepicker({
      dateFormat: 'dd/mm/yy'
    });
  });

  $(document).on('hide.bs.modal', '#detailModal,#deleteModal', function() {
    if (confirm("Are you sure, you want to close?")) return true;
    else return false;
  });

  $('#update').submit(function(event) {
    var regqty = /^(\s*|\d+\.\d*|\d+)$/;
    var qtyreq = document.getElementById("m_qtyrec").value;

    if (!regqty.test(qtyreq)) {
      alert('Qty Requested Must be number or "." ');
      return false;
    } else {
      document.getElementById('btnclose').style.display = 'none';
      document.getElementById('btnconf').style.display = 'none';
      document.getElementById('btnloading').style.display = '';
    }

  });


  $('#submit').submit(function(event) {

    var checkboxes = document.querySelectorAll('input[type="checkbox"]');
    var checkedOne = Array.prototype.slice.call(checkboxes).some(x => x.checked);

    if (checkedOne === false) {
      alert('Checked at least one');
      event.preventDefault();
    } else {
      document.getElementById('s_btnconf').style.display = 'none';
      document.getElementById('back_btn').style.display = 'none';
      document.getElementById('s_btnloading').style.display = '';
    }

  });

  $(document).on('click', '.editUser', function() { // Click to only happen on announce links

    var id = $(this).data('id');
    var suratjalan = $(this).data('sj');
    var ponbr = $(this).data('nbr');
    var line = $(this).data('line');
    var part = $(this).data('part');
    var desc = $(this).data('desc');
    var qtyord = $(this).data('qtyord');
    var qtyship = $(this).data('qtyship');
    var qtyopen = $(this).data('qtyopen');
    var effdate = $(this).data('effdate');
    var shipdate = $(this).data('shipdate');
    //var um = $(this).data('um');
    var site = $(this).data('site');
    var loc = $(this).data('loc');
    var lot = $(this).data('lot');
    var ref = $(this).data('ref');
    var qtyrec = $(this).data('qtyrcvd');


    if (effdate == '') {
      var new_shipdate = '';
      var new_effdate = '';
    } else {
      var split_effdate = effdate.split('-');
      var split_shipdate = shipdate.split('-');

      var new_effdate = split_effdate[2].concat('/', split_effdate[1], '/', split_effdate[0]);
      var new_shipdate = split_shipdate[2].concat('/', split_shipdate[1], '/', split_shipdate[0]);
    }



    document.getElementById('rcpid').value = id;
    document.getElementById("m_sj").value = suratjalan;
    document.getElementById("m_ponbr").value = ponbr;
    document.getElementById("m_line").value = line;
    document.getElementById("m_itemcode").value = part;
    document.getElementById("m_itemdesc").value = desc;
    document.getElementById("m_qtyord").value = qtyord;
    //document.getElementById("m_qtyopen").value = qtyopen;
    document.getElementById("m_qtyship").value = qtyship;
    document.getElementById("m_qtyrec").value = qtyrec;

    document.getElementById("effdate").value = new_effdate;
    document.getElementById("shipdate").value = new_shipdate;
    //document.getElementById("m_site").value = site;
    document.getElementById("m_loc").value = loc;
    document.getElementById("m_lot").value = lot;
    document.getElementById("m_ref").value = ref;

    $('#m_qtyrec').attr({
      "max": qtyship,
    });

    jQuery.ajax({
      type: "get",
      url: "{{URL::to("
      detailreceipt ") }}",
      data: {
        suratjalan: suratjalan,
        ponbr: ponbr,
        line: line
      },
      success: function(data) {
        //$('tbody').html(data);
        console.log(data);
        document.getElementById("d_um").innerHTML = data[0]['xpod_um'];
        document.getElementById("m_um").value = data[0]['xpod_um'];
        document.getElementById("m_loc").value = data[0]['xpod_loc'];
        document.getElementById("m_lot").value = data[0]['xpod_lot'];
      }
    });
  });
</script>

@endsection
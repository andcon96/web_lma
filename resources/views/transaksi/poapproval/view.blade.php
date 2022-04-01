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
  
  $(document).on('click','.sendmail',function(){
        var checkbox = $(this), // Selected or current checkbox
        value = checkbox.val(); // Value of checkbox

        if (checkbox.is(':checked')) {
            $(this).closest("tr").find('.hide_check').val('M');
        } else {
            $(this).closest("tr").find('.hide_check').val('R');
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
</script>

@endsection
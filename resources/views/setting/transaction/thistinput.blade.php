@extends('layout.layout')

@section('menu_name','Transaction Synchronization')

@section('content')
<div class="">
  <button class="btn bt-action ml-3" style="margin-left:10px;" data-toggle="modal" data-target="#createModal">Add</button>
  <!--Table-->

  <div class="table-responsive col-lg-12 col-md-12 tag-container mt-3">
    <table class="table table-bordered" width="100%" cellspacing="0">
      <thead>
        <tr>
          <th>Transaction Type</th>
          <th>Code</th>
          <th width="7%">Action</th>
        </tr>
      </thead>
      <tbody>
        @forelse ($data as $show)
        <tr>
          <td>{{ $show->transaction_type }}</td>
          <td>{{ $show->transaction_desc }}</td>
          <td data-title="Delete" class="action">
            <a href="" class="deleteHist" data-id="{{$show->id}}" data-role="{{$show->transaction_type}}" data-toggle='modal' data-target="#deleteModal"><i class="fas fa-trash-alt"></i></a>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="4" style="color:red">
            <center><b>NO DATA AVAILABLE</b></center>
          </td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">Delete Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="{{route('transaction.destroy','delete')}}" method="post">
        {{ method_field('delete') }}
        {{ csrf_field() }}

        <div class="modal-body">

          <input type="hidden" name="temp_id" id="temp_id" value="">

          <div class="container">
            <div class="row">
              Are you sure you want to delete<strong><a name="temp_thist" id="temp_thist"></a></strong> &nbsp;?
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-info bt-action" id="d_btnclose" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger bt-action" id="d_btnconf">Save</button>
          <button type="button" class="btn bt-action" id="d_btnloading" style="display:none">
            <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
          </button>
        </div>

      </form>
    </div>
  </div>
</div>


<div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">Create New</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="{{route('transaction.store')}}" method="post">
        {{ method_field('post') }}
        {{ csrf_field() }}

        <div class="modal-body">
          <div class="form-group row">
            <label for="xtr_type" class="col-md-4 col-form-label text-md-right">{{ __('Transaction Type') }}</label>
            <div class="col-md-5">
              <select id="xtr_type" type="text" class="form-control-sm" name="xtr_type">
                <option value=""> Select Data </option>
                <option value="ADD-PO"> Purchase Order Maintenance </option>
                <option value="ORD-PO"> Purchase Order Booking </option>
                <option value="ISS-PRV"> PO Return to Vendor </option>
                <option value="RCT-PO"> Purchase Order Receipt</option>
                <option value="ISS-TR"> Loc Transfer-Issue </option>
                <option value="RCT-TR"> Loc Transfer-Receipt </option>
                <option value="ISS-UNP"> Issue Unplanned </option>
                <option value="RCT-UNP"> Unplanned Receipt </option>
                <option value="ISS-CHL"> Inv Detail Maint-Issue </option>
                <option value="RCT-CHL"> Inv Detail Maint-Receipt </option>
                <option value="ORD-SO"> Sales Order Booking (Order) </option>
                <option value="ISS-SO"> Sales Order Shipments </option>
                <option value="RCP-SOR"> Sales Order Return </option>
              </select>
            </div>
          </div>

          <div class="form-group row">
            <label for="xtr_code" class="col-md-4 col-form-label text-md-right">{{ __('Code') }}</label>
            <div class="col-md-3">
              <input type="text" id="xtr_code" name="xtr_code" class="form-control-sm" readonly=""> </input>
              <input type="hidden" id="xtr_desc" name="xtr_desc" value="">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-info bt-action" id="btnclose" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success bt-action" id="btnconf">Save</button>
            <button type="button" class="btn bt-action" id="btnloading" style="display:none">
              <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
            </button>
          </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('scripts')

<script type="text/javascript">
  $("#xtr_type").select2({
    width: '100%'
  });


  $(document).on('change', '#xtr_type', function() {
    var value = $(this).val();
    var text = $("#xtr_type option:selected").text();
    $('#xtr_code').val(value);
    $('#xtr_desc').val(text);
  });

  $(document).on('click', '.deleteHist', function() {
    var trid = $(this).data('id');
    document.getElementById("temp_id").value = trid;
  });
</script>

@endsection
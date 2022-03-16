@extends('layout.layout')

@section('menu_name','Item Inventory Control')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
  <li class="breadcrumb-item active">Item Inventory Control</li>
</ol>
@endsection

@section('content')

<!-- Page Heading -->
<div>
  <div class="card-body ">
    <div class="row">
      <div class="col-xl-2">
        <form action="{{route('iteminventorycontrol.create')}}" method="get">
          <div>
            <button class="btn bt-action mb-3" type="submit" value="Create" style="width:150px !important">Create
              Data</button>
          </div>
        </form>
      </div>
      <form action="{{route('iteminventorycontrol.loaditem')}}" id="loaditm" method="post">
        @method('post')
        @csrf

        <div>
          <button class="btn bt-action mb-3" type="submit" value="Create" style="width:150px !important">Load
            Data</button>
        </div>
      </form>
    </div>

    <div class="table-responsive">
      <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
          <tr>
            <th>Id</th>
            <th>Item Number</th>
            <th>Prod Line</th>
            <th>Item Type</th>
            <th>Design Group</th>
            <th>Promo Group</th>
            <th>Group</th>
            <th colspan="2">Option</th>
          </tr>
        </thead>
        <tbody>
          @forelse ($items as $index => $show)
          <tr>
            <td>{{ $index + 1 }}</td>
            <td>{{ $show->iic_item_part }}</td>
            <td>{{ $show->iic_item_prod_line }}</td>
            <td>{{ $show->iic_item_type }}</td>
            <td>{{ $show->iic_item_design }}</td>
            <td>{{ $show->iic_item_promo }}</td>
            <td>{{ $show->iic_item_group }}</td>
            <td>
              <form action="{{route('iteminventorycontrol.edit', $show->id)}}" method="get">
                {{ csrf_field() }}
                <input type="hidden" name="id" class="form-control" value='{{ $show->id }} '>
                <input type="hidden" name="part" class="form-control" value='{{ $show->iic_item_part }} '>
                <input type="hidden" name="line" class="form-control" value={{ $show->iic_item_prod_line }}>
                <input type="hidden" name="type" class="form-control" value={{ $show->iic_item_type }}>
                <input type="hidden" name="dsgn" class="form-control" value={{ $show->iic_item_design }}>
                <input type="hidden" name="promo" class="form-control" value={{ $show->iic_item_promo }}>
                <input type="hidden" name="grp" class="form-control" value={{ $show->iic_item_group }}>

                <button class='btn' style="color:#007bff" type="submit" value="EDIT"><i class="fas fa-edit"></i>
              </form>
            </td>
            <td>
              <a href="" class="deleteUser" data-toggle="modal" data-target="#deleteModal" data-id="{{ $show->id }}"
                data-line="{{$index + 1}}">
                <i class="fas fa-trash"></i></a>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan='12' class="text-danger">
              <center><b>No Data Available</b></center>
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</div>

<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <b>Delete Data </b>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="{{route('iteminventorycontrol.destroy', 'delete')}}" method="post">
        @method('delete')
        {{ csrf_field() }}
        <div class="modal-body">
          <input type="hidden" name="delete_id" id="delete_id" value="">

          <div class="container">
            <div class="row">
              Delete Line:
              &nbsp; <strong><a name="xid" id="xid"></a></strong>
              &nbsp;?
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-info bt-action" id='d_btnclose' data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-danger bt-action" id='d_btnconf'>Delete</button>
          <button type="button" class="btn bt-action" id="d_btnloading" style="display:none">
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
  $('#loaditm').on('submit',function(){
        $('#loader').removeClass('hidden')
    });


    $(document).on('click','.deleteUser',function(){
       var uid = $(this).data('id');
       var line = $(this).data('line');
       document.getElementById('delete_id').value = uid; 
       document.getElementById('xid').innerHTML = line;       

    });


 
</script>
@endsection
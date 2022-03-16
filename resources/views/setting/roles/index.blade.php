@extends('layout.layout')

@section('menu_name','Role Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
  <li class="breadcrumb-item active">Role Maintenance</li>
</ol>
@endsection

@section('content')

<!-- Page Heading -->
<div class="col-lg-12">
  <button class="btn btn-info bt-action newRole" data-toggle="modal" data-target="#myModal">
    Create Role Type</button>
</div>

<div class="table-responsive col-lg-12 col-md-12 mt-3">
  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th>Role</th>
        <th>Role Type</th>
        <th width="8%">Edit</th>
        <th width="8%">Delete</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($roleTypes as $index => $show)
      <tr>
        <td>
          @if ($show->getRole->role == 'Super_User')
            Admin
          @else
            {{$show->getRole->role}}
          @endif
        </td>
        <td>
          @if ($show->role_type == 'Super_User')
          Super User
          @else
          {{$show->role_type}}
          @endif
        </td>
        @if($show->getRole->role == 'Super_User' && $show->role_type == 'Super_User')

        @else
        <td>
          <a href="" class="editModal" data-userid="{{$show->id}}" data-role="{{$show->getRole->role}}"
            data-role_type="{{$show->role_type}}" data-toggle='modal' data-target="#editModal"><i
              class="fas fa-edit"></i></button>
        </td>
        <td>
          <a href="" class="deleteRole" data-userid="{{$show->id}}" data-role="{{$show->getRole->role}}"
            data-toggle='modal' data-target="#deleteModal"><i class="fas fa-trash-alt"></i></button>
        </td>
        @endif
      </tr>
      @endforeach
    </tbody>
  </table>
</div>


<!--Create Modal-->
<div id="myModal" class="modal fade" role="dialog" data-backdrop="static">
  <div class="modal-dialog">
    <!-- konten modal-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">Create Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="panel-body">
        <!-- heading modal -->
        <form class="form-horizontal" role="form" method="POST" action="{{route('rolemaint.store')}}">
          {{ method_field('post') }}
          {{ csrf_field() }}
          <div class="modal-body">
            <div class="form-group row">
              <label for="role" class="col-md-3 col-form-label text-md-right">{{ __('Role') }}</label>
              <div class="col-md-7">
                <select id="role" class="form-control role" name="role" required autofocus>
                  <option value=""> Select Data </option>
                  <option value="Super_User"> Admin </option>
                  <option value="User"> User </option>
                </select>
              </div>
            </div>
            <div class="form-group row">
              <label for="class" class="col-md-3 col-form-label text-md-right">{{ __('Role Type') }}</label>
              <div class="col-md-7">
                <input id="class" type="text" class="form-control" name="role_type" autocomplete="off" value=""
                  required>
              </div>
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
</div>

<!--Edit Modal-->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog">
    <!-- konten modal-->
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">Edit Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="panel-body">
        <!-- heading modal -->
        <form class="form-horizontal" role="form" method="POST" action="{{route('rolemaint.update', 'role')}}">
          @csrf
          @method('put')
          <div class="modal-body">
            <input type="hidden" name="e_id" id="e_id">
            <div class="form-group row">
              <label for="e_role" class="col-md-3 col-form-label text-md-right">{{ __('Role') }}</label>
              <div class="col-md-7">
                <input id="e_role" type="text" class="form-control" name="e_role" readonly>
              </div>
            </div>
            <div class="form-group row">
              <label for="e_roleType" class="col-md-3 col-form-label text-md-right">{{ __('Role Type') }}</label>
              <div class="col-md-7">
                <input id="e_roleType" type="text" class="form-control" name="e_roleType" value="" required>
              </div>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-info bt-action" id="e_btnclose" data-dismiss="modal">Cancel</button>
            <button type="submit" class="btn btn-success bt-action" id="e_btnconf">Save</button>
            <button type="button" class="btn bt-action" id="e_btnloading" style="display:none">
              <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
            </button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- MODAL DELETE -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">Delete Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="{{route('rolemaint.destroy', 'role')}}" method="post">

        {{ method_field('delete') }}
        {{ csrf_field() }}

        <div class="modal-body">

          <input type="hidden" name="_method" value="delete">

          <input type="hidden" name="temp_id" id="temp_id" value="">

          <div class="container">
            <div class="row">
              Are you sure you want to delete Role :&nbsp; <strong><a name="temp_uname" id="temp_uname"></a></strong>
              &nbsp;?
            </div>
          </div>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-info bt-action" id="d_btnclose" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success bt-action" id="d_btnconf">Save</button>
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
  $(document).ready(function() {
      $('form').on("submit",function(){
          document.getElementById('btnclose').style.display = 'none';
          document.getElementById('btnconf').style.display = 'none';
          document.getElementById('btnloading').style.display = '';
          document.getElementById('e_btnclose').style.display = 'none';
          document.getElementById('e_btnconf').style.display = 'none';
          document.getElementById('e_btnloading').style.display = '';
          document.getElementById('d_btnclose').style.display = 'none';
          document.getElementById('d_btnconf').style.display = 'none';
          document.getElementById('d_btnloading').style.display = '';
      });
  });


  $(document).on('click','.newRole',function(){
      document.getElementById('role').value = '';
      document.getElementById('desc').value = '';       
  });

  $(document).on('click','.deleteRole',function(){ // Click to only happen on announce links
     
     //alert('tst');
     var uid = $(this).data('userid');
     if($(this).data('role') == 'Super_User') {
       var uname = 'Admin';
     } else if ($(this).data('role') == 'Purchasing') {
       var uname = 'Internal';
     } else {
       var uname = 'External';
     }

     document.getElementById("temp_id").value = uid;
     document.getElementById("temp_uname").innerHTML = uname;

     });

  $(document).on('click','.editModal',function(){ // Click to only happen on announce links
     
     //alert('tst');
     var uid = $(this).data('userid');
     if($(this).data('role') == 'Super_User') {
       var role = 'Admin';
     } else if ($(this).data('role') == 'Purchasing') {
       var role = 'Internal';
     } else {
       var role = 'External';
     }
     var role_type = $(this).data('role_type');

     
     document.getElementById("e_id").value = uid;
     document.getElementById('e_role').value = role;
     document.getElementById("e_roleType").value = role_type;

  });
</script>
@endsection
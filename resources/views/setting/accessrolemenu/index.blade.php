@extends('layout.layout')

@section('menu_name','Role Menu Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
  <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
  <li class="breadcrumb-item active">Role Menu Maintenance</li>
</ol>
@endsection

@section('content')

<!-- Page Heading -->
<div class="table-responsive col-lg-12 col-md-12 mt-3">
  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th>Role</th>
        <th>Role Type</th>
        <th>Edit</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($roleAccess as $show)
      <tr>
        <td data-title="Role">{{str_replace('_', ' ', $show->getRole->role)}}</td>
        <td data-title="Role Type">{{str_replace('_', ' ', $show->role_type)}}</td>
        <td data-title="Edit" class="action">
          @if($show->getRole->role !== 'Super_User')
          <a href="" class="editUser" data-toggle="modal" data-target="#editModal" data-id="{{$show->id}}"
            data-role="{{$show->getRole->role}}" data-desc="{{$show->role_type}}"><i class="fas fa-edit"></i></a>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>




<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
  aria-hidden="true" data-backdrop="static">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title text-center" id="exampleModalLabel">Edit Data</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <form action="{{route('accessrolemenu.update', 'test')}}" method="post">

        {{ method_field('patch') }}
        {{ csrf_field() }}

        <input type="hidden" name="edit_id" id="edit_id" value="">

        <div class="modal-body">
          <div class="form-group row">
            <label for="role" class="col-md-3 col-form-label text-md-right">{{ __('Role') }}</label>
            <div class="col-md-7">
              <input id="role" type="text" class="form-control" name="role" value="" disabled>
            </div>
          </div>
          <div class="form-group row">
            <label for="desc" class="col-md-3 col-form-label text-md-right">{{ __('Desc') }}</label>
            <div class="col-md-7">
              <input id="desc" type="text" class="form-control" name="desc" value="" disabled>
            </div>
          </div>

          <div class="form-group">
            <h5>
              <center><strong>Menu Access</strong></center>
            </h5>
            <hr>
          </div>

          <div class="form-group">
            <h6>
              <center><strong>PO</strong></center>
              </h5>
              <hr>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('PO List') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbPOBrowse">
                <input type="checkbox" id="cbPOBrowse" name="cbPOBrowse" value="PO01" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('PO Receipt') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbPOReceipt">
                <input type="checkbox" id="cbPOReceipt" name="cbPOReceipt" value="PO02" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('PO Approval') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbPOApproval">
                <input type="checkbox" id="cbPOApproval" name="cbPOApproval" value="PO03" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('PO Approval Utility') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbResetApp">
                <input type="checkbox" id="cbResetApp" name="cbResetApp" value="PO05" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Last 10 RFQ & PO') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbLast10PO">
                <input type="checkbox" id="cbLast10PO" name="cbLast10PO" value="PO04" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Audit Trail PO') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbAuditPO">
                <input type="checkbox" id="cbAuditPO" name="cbAuditPO" value="PO06" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Audit Trail PO Approval') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbAuditPOApp">
                <input type="checkbox" id="cbAuditPOApp" name="cbAuditPOApp" value="PO07" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>

          <div class="form-group">
            <h6>
              <center><strong>Surat Jalan</strong></center>
              </h5>
              <hr>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Create SJ') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbCreateSJ">
                <input type="checkbox" id="cbCreateSJ" name="cbCreateSJ" value="SJ01" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Browse SJ') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbBrowseSJ">
                <input type="checkbox" id="cbBrowseSJ" name="cbBrowseSJ" value="SJ02" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>

          <div class="form-group row">
            <label for="level" class="col-md-6 col-form-label text-md-right">{{ __('Confirm SJ') }}</label>
            <div class="col-md-6">
              <label class="switch" for="cbConfSJ">
                <input type="checkbox" id="cbConfSJ" name="cbConfSJ" value="SJ03" />
                <div class="slider round"></div>
              </label>
            </div>
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-info bt-action" data-dismiss="modal">Cancel</button>
          <button type="submit" class="btn btn-success bt-action">Save</button>
        </div>

      </form>

    </div>
  </div>
</div>

@endsection

@section('scripts')


<!-- Pass Value Modal Edit & Checkbox Setting -->
<script type="text/javascript">
  $(document).on('click','.editUser',function(){ // Click to only happen on announce links
     
     //alert('tst');
     
     var idrole = $(this).data('id');
     var role = $(this).data('role');
     var desc = $(this).data('desc');
     if (desc == "Super_User") {
       desc = 'Super User'
     }

     //alert(idrole)

     document.getElementById("edit_id").value = idrole;
     document.getElementById("role").value = role;
     document.getElementById("desc").value = desc;


     jQuery.ajax({
          type : "get",
          url : "{{route("accessmenu") }}",
          data:{
            search : idrole,
          },
          success:function(data){
            // /alert(data);
            var totmenu = data;
            
            // Centang Checkbox berdasarkan data
            if(totmenu.search("SJ01") >= 0){
              document.getElementById("cbCreateSJ").checked = true;  
            }else{
              document.getElementById("cbCreateSJ").checked = false;
            }
            if(totmenu.search("SJ02") >= 0){
              document.getElementById("cbBrowseSJ").checked = true;  
            }else{
              document.getElementById("cbBrowseSJ").checked = false;
            }
            if(totmenu.search("SJ03") >= 0){
              document.getElementById("cbConfSJ").checked = true;  
            }else{
              document.getElementById("cbConfSJ").checked = false;
            }
          }
      });
     
     });

</script>
@endsection
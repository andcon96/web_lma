@extends('layout.layout')

@section('menu_name','RFP Approval Control')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">RFP Approval Control</li>
</ol>
@endsection

@section('content')

    <!-- Page Heading -->

  @include('setting.rfpapproval.table')

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
      <div class="modal-dialog modal-xl" role="document">
          <div class="modal-content">
              <div class="modal-header">
                  <h5 class="modal-tilte text-center" id="exampleModalLabel">Create Approval</h5>
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                  </button>
              </div>
                <form class="form-horizontal" method="POST" id="edit" action="{{route('rfpapproval.update', 'update')}}" onkeydown="return event.key != 'Enter';">
                    @method('patch')
                    {{csrf_field()}}
                    <div class="modal-body">
                        <input type="hidden" name="edit_id" id="edit_id">
                        <div class="form-group row">
                            <label for="dept_name" class="col-md-3 col-form-label text-md-right">{{ __('Department')}}</label>
                            <div class="col-md-4">
                                <input id="dept_name" type="text" class="form-control" name="dept_name" value="" readonly>
                            </div>
                        </div>

                        <div class="form-group row">
                            <table id="rfptable" class="table order-list">
                                <thead>
                                    <tr>
                                        <th style="width: 30%;">Approver</th>
                                        <th style="width: 30%;">Alt Approver</th>
                                        <th style="width: 15%;">Order</th>
                                        <th style="width: 10%">Delete</th>
                                    </tr>
                                </thead>
                                <tbody id="oldsupplier">
                                    <!-- belom ada isi -->
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="5">
                                            <input type="button" class="btn btn-lg btn-block"
                                            id="addrow" value="Add Row" style="background-color: #1234A5; color:white; font-size:16px"/>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
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

@endsection

@section('scripts')


<script type="text/javascript">
    $(document).on('click', '.editApprover', function(e){
        var deptid = $(this).data('deptid');
        var deptcode = $(this).data('deptcode');
        var deptname = $(this).data('deptname');

        document.getElementById('edit_id').value = deptid;
        document.getElementById('dept_name').value = deptcode.concat(' - ',deptname);
        jQuery.ajax({
            type : "get",
            url : "{{route("getData")}}",
            data:{
                search : deptcode, 
            },
            success:function(data){
                console.log(data);
                $('#oldsupplier').html(data);
            }
        });
        

    });

    $(document).ready(function(){
        var counter = 0;

        $("#addrow").on("click", function(){
            
            var newRow = $("<tr>");
            var cols = "";

            cols += '<td>';
            cols += '<select id="suppname[]" class="form-control suppname" name="suppname[]" required autofocus>';
            @foreach($users as $names)
            cols += '<option value="{{$names->id}}"> {{$names->name." - ".$names->getRoleType->role_type}} </option>';
            @endforeach
            cols += '</select>';
            cols += '</td>';

            cols += '<td>';
            cols += '<select id="altname[]" class="form-control altname" name="altname[]" required autofocus>';
            @foreach($users as $names1)
            cols += '<option value="{{$names1->id}}"> {{$names1->name." - ".$names1->getRoleType->role_type}} </option>';
            @endforeach
            cols += '</select>';
            cols += '</td>';

            cols += '<td data-title="order[]"><input type="number" min="1" class="form-control form-control-sm order" autocomplete="off" name="order[]" style="height:37px" required/></td>';

            cols += '<td data-title="Action"><input type="button" class="ibtnDel btn btn-danger" value="delete">';
            cols += '</tr>';

            newRow.append(cols);
            $("table.order-list").append(newRow);
            counter++;
        });

        function formatNumber(num) {
            return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
        }

        $("table.order-list").on('click', '.ibtnDel', function(event){
            $(this).closest("tr").remove();
            counter-=1;
        });

        $("#edit").submit(function(){
                document.getElementById('e_btnclose').style.display = 'none';
                document.getElementById('e_btnconf').style.display = 'none';
                document.getElementById('e_btnloading').style.display = '';
         });
    });
</script>
@endsection
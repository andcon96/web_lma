@extends('layout.layout')

@section('menu_name','PO Approval Control')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">PO Approval Control</li>
</ol>
@endsection

@section('content')

<div class="table-responsive col-lg-12 tag-container">
    @include('setting.poapprover.tablepo')
</div>

<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-xl " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title text-center" id="exampleModalLabel">Create Approval</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <form class="form-horizontal" method="POST" id='update' action="{{route('poapprover.store')}}" onkeydown="return event.key != 'Enter';" id="edit">
                {{ csrf_field() }}
                <div class="modal-body">
                    <input type="hidden" name="edit_id" id="edit_id">
                    <div class="form-group row">
                        <label for="app_name" class="col-md-4 col-form-label text-md-right">{{ __('Supplier') }}</label>
                        <div class="col-md-6">
                            <input id="app_name" type="text" class="form-control" name="app_name" value="" readonly>
                            <input id="supp_code" type="hidden" class="form-control" name="supp_code" value="" readonly>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="reapprove" class="col-md-4 col-form-label text-md-right">{{ __('Need Reapprove') }}</label>
                        <div class="col-md-2">
                            <select class="form-control" name='reapprove' id='reapprove'>
                                <option value='1'>Yes</option>
                                <option valie='0'>No</option>>
                            </select>
                        </div>
                        <label for="int_rem" class="col-md-2 col-form-label text-md-right">{{ __('Interval Reminder') }}</label>
                        <div class="col-md-2">
                            <input id="int_rem" type="text" class="form-control" name="int_rem" placeholder="Days" value="" autocomplete="off">
                        </div>
                    </div>

                    <div class="form-group row mr-5 ml-5">
                        <table id='suppTable' class='table order-list'>
                            <thead>
                                <tr>
                                    <th style="width:30%">Approver</th>
                                    <th style="width:15%">Min Amt</th>
                                    <th style="width:15%">Max Amt</th>
                                    <th style="width:30%">Alt Approver</th>
                                    <th style="width:10%">Delete</th>
                                </tr>
                            </thead>
                            <tbody id='oldsupplier'>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="5">
                                        <input type="button" class="btn btn-lg btn-block" id="addrow" value="Add Row" style="background-color:#1234A5; color:white; font-size:16px" />
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
    $(document).on('click', '.editUser', function() { // Click to only happen on announce links
        var idsupp = $(this).data('idsupp');
        var suppcode = $(this).data('suppcode');
        var suppname = $(this).data('suppname');
        var interval = $(this).data('interval');

        document.getElementById("edit_id").value = idsupp;
        document.getElementById("app_name").value = suppcode.concat(' - ', suppname);
        document.getElementById("supp_code").value = suppcode;
        document.getElementById("reapprove").selectedIndex = "0";
        document.getElementById('int_rem').value = interval;

        jQuery.ajax({
            type: "get",
            url: "{{URL::to("getdetailapp") }}",
            data: {
                search: idsupp,
            },
            success: function(data) {
                $('#oldsupplier').html(data);
            }
        });

    });

    $(document).ready(function() {
        var counter = 0;

        $("#addrow").on("click", function() {

            var newRow = $("<tr>");
            var cols = "";


            cols += '<td>';
            cols += '<select id="suppname[]" class="form-control suppname" name="suppname[]" required autofocus>';
            @foreach($names as $names1)
            cols += '<option value="{{$names1->id}}"> {{$names1->name." - ".$names1->getRoleType->role_type}} </option>';
            @endforeach
            cols += '</select>';
            cols += '</td>';

            cols += '<td data-title="min_amt[]"><input type="number" class="form-control form-control-sm minnbr" autocomplete="off" name="min_amt[]" style="height:37px" required/></td>';
            cols += '<td data-title="max_amt[]"><input type="number" class="form-control form-control-sm maxnbr" autocomplete="off" name="max_amt[]" style="height:37px" required/></td>';

            cols += '<input type="hidden" id="appid[]" name="appid[]" value="" />';


            cols += '<td>';
            cols += '<select id="altname[]" class="form-control altname" name="altname[]" required autofocus>';
            @foreach($names as $names2)
            cols += '<option value="{{$names2->id}}"> {{$names2->name." - ".$names2->getRoleType->role_type}} </option>';
            @endforeach
            cols += '</select>';
            cols += '</td>';

            cols += '<td data-title="Action"><input type="button" class="ibtnDel btn btn-danger"  value="delete"></td>';
            cols += '</tr>'
            newRow.append(cols);
            $("table.order-list").append(newRow);
            counter++;
        });


        $("table.order-list").on("click", ".ibtnDel", function(event) {
            $(this).closest("tr").remove();
            counter -= 1
        });

        $("#new").submit(function(e) {
            if (counter == 0) {
                Swal.fire({
                    icon: 'error',
                    text: 'Please Create A New Row Before Submiting',
                })
                e.preventDefault();
            } else {
                document.getElementById('btnclose').style.display = 'none';
                document.getElementById('btnconf').style.display = 'none';
                document.getElementById('btnloading').style.display = '';
            }
        });

        $("#edit").submit(function() {
            document.getElementById('e_btnclose').style.display = 'none';
            document.getElementById('e_btnconf').style.display = 'none';
            document.getElementById('e_btnloading').style.display = '';
        });

        $("#delete").submit(function() {
            document.getElementById('d_btnclose').style.display = 'none';
            document.getElementById('d_btnconf').style.display = 'none';
            document.getElementById('d_btnloading').style.display = '';
        });

    });

    $(document).on('click', '.pagination a', function(e) {
        e.preventDefault();
        var page = $(this).attr('href').split('?page=')[1];
        getData(page);
        // alert(page);

    });

    function getData(page) {
        $.ajax({
            url: '/poapprover?page=' + page,
            type: "get",
            datatype: "html"
        }).done(function(data) {
            console.log('Page = ' + page);

            $(".tag-container").empty().html(data);

        }).fail(function(jqXHR, ajaxOptions, thrownError) {
            Swal.fire({
                icon: 'error',
                text: 'No response from server',
            })
        });
    }
</script>
@endsection
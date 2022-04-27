@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">Domain Maintenance</li>
</ol>
@endsection

@section('content')
<div class="table-responsive col-lg-12 col-md-12">
    <form action="{{route('domainmstr.store')}}" method="post" id="submit">
        {{ method_field('post') }}
        {{ csrf_field() }}

        <div class="modal-header">
        </div>

        <div class="modal-body">

            <div class="form-group row mr-5 ml-5">
                <div class="col-md-12">
                    <table id="createTable" class="table order-list" style="table-layout: fixed;">
                        <thead>
                            <tr>
                                <td style="text-align: center; width:20%">Domain Code</td>
                                <td style="text-align: center; width:30%">Domain Desc</td>
                                <td>SO Prefix</td>
                                <td>SO RN</td>
                                <td>Kerusakan Prefix</td>
                                <td>Kerusakan RN</td>
                                <td style="text-align: center;">Action</td>
                            </tr>
                        </thead>
                        <tbody id='detailapp'>
                            @forelse ( $domain as $domains )
                            <tr>
                                <td>
                                    <input type="text" class="form-control" name="code[]" value="{{$domains->domain_code}}" required />
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="desc[]" value="{{$domains->domain_desc}}" required />
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="sjprefix[]" value="{{$domains->domain_sj_prefix}}" maxlength="2" minlength="2" required />
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="sjrn[]" value="{{$domains->domain_sj_rn}}" readonly maxlength="6" minlength="6" required />
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="rcvprefix[]" value="{{$domains->domain_rcv_prefix}}" maxlength="2" minlength="2" required />
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="rcvrn[]" value="{{$domains->domain_rcv_rn}}" readonly maxlength="6" minlength="6" required />
                                </td>
                                <td style="vertical-align:middle;text-align:center;">
                                    <input type="checkbox" class="qaddel" value="">
                                    <input type="hidden" name="iddomain[]" value="{{$domains->id}}">
                                    <input type="hidden" class="op" name="op[]" value="M"/>
                                </td>
                            </tr>
                            @empty

                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="7">
                                    <input type="button" class="btn btn-lg btn-block btn-focus" id="addrow" value="Add Domain" style="background-color:#1234A5; color:white; font-size:16px" />
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <div class="modal-footer">
            <button type="submit" class="btn btn-success bt-action" id="btnconf">Save</button>
            <button type="button" class="btn bt-action" id="btnloading" style="display:none">
                <i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
            </button>
        </div>
    </form>
</div>

@endsection

@section('scripts')

<script type="text/javascript">
    var counter = 1;
    $(document).ready(function() {


    });

    $('#submit').on("submit", function(e) {
        document.getElementById('btnconf').style.display = 'none';
        document.getElementById('btnloading').style.display = '';
    });

    $("#addrow").on("click", function() {



        var rowCount = $('#createTable tr').length;

        var currow = rowCount - 2;

        // alert(currow);

        var lastline = parseInt($('#createTable tr:eq(' + currow + ') td:eq(0) input[type="number"]').val()) + 1;

        if (lastline !== lastline) {
            // check apa NaN
            lastline = 1;
        }

        // alert(lastline);

        var newRow = $("<tr>");
        var cols = "";

        cols += '<td>';
        cols += '<input type="text" class="form-control" name="code[]" required />';
        cols += '</td>';

        cols += '<td>';
        cols += '<input type="text" class="form-control" name="desc[]" required />';
        cols += '</td>';

        cols += '<td>';
        cols += '<input type="text" class="form-control" name="sjprefix[]" value="" maxlength="2" minlength="2" required />';
        cols += '</td>';

        cols += '<td>';
        cols += '<input type="text" class="form-control" name="sjrn[]" value="{{$domains->domain_so_rn}}" readonly maxlength="6" minlength="6" required />';
        cols += '</td>';

        cols += '<td>';
        cols += '<input type="text" class="form-control" name="rcvprefix[]" value="" maxlength="2" minlength="2" required />';
        cols += '</td>';

        cols += '<td>';
        cols += '<input type="text" class="form-control" name="rcvrn[]" value="{{$domains->domain_kr_rn}}" readonly maxlength="6" minlength="6" required />';
        cols += '</td>';

        cols += '<td data-title="Action"><input type="button" class="ibtnDel btn btn-danger btn-focus"  value="Delete"></td>';
        cols += '<input type="hidden" class="op" name="op[]" value="A"/>';
        cols += '<input type="hidden" class="op" name="iddomain[]" value=""/>';
        cols += '</tr>'
        counter++;

        newRow.append(cols);
        $("#detailapp").append(newRow);

        // selectRefresh();
    });

    $("table.order-list").on("click", ".ibtnDel", function(event) {
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

    $(document).on('click','.qaddel',function(){
        var checkbox = $(this), // Selected or current checkbox
        value = checkbox.val(); // Value of checkbox

        if (checkbox.is(':checked')) {
            $(this).closest("tr").find('.op').val('R');
        } else {
            $(this).closest("tr").find('.op').val('M');
        }

    });
</script>
@endsection
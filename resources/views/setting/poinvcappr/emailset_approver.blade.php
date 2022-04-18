@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
    <li class="breadcrumb-item active">Invoice PO Email</li>
</ol>
@endsection

@section('content')
<div class="table-responsive col-lg-12 col-md-12">
    <form action="{{route('poinvcemail.store')}}" method="post" id="submit">
        {{ method_field('post') }}
        {{ csrf_field() }}

        <div class="modal-header">
        </div>

        <div class="modal-body">

            <div class="form-group row">
                <label for="nameappr" class="col-md-4 col-form-label text-md-right">{{ __('Nama Approver') }}</label>
                <div class="col-md-5">
                    <input id="nameappr" type="text" class="form-control" name="nameappr" autocomplete="off" value="{{$data->name_invc ?? ''}}" autofocus required>
                </div>
            </div>
            <div class="form-group row">
                <label for="emailappr" class="col-md-4 col-form-label text-md-right">{{ __('Email Approver') }}</label>
                <div class="col-md-5">
                    <input id="emailappr" type="email" class="form-control" autocomplete="off" name="emailappr" value="{{$data->email_invc ?? ''}}" required>
                    <span id="errorcur" style="color:red"></span>
                </div>
            </div>
            <div class="form-group row mr-5 ml-5">
                <div class="col-md-8 offset-2">
                    <table id="createTable" class="table order-list" style="table-layout: fixed;">
                        <thead>
                            <tr>
                                <td colspan="2" style="text-align: center;">Email Receiver</td>
                            </tr>
                        </thead>
                        <tbody id='detailapp'>
                            @forelse ( $list as $listrcv )
                            <tr>
                                <td>
                                    <input type="email" class="form-control" name="emailrcv[]" value="{{$listrcv}}" required />
                                </td>
                                <td style="vertical-align:middle;text-align:center;">
                                    <input type="checkbox" class="qaddel" value="">
                                    <input type="hidden" class="op" name="op[]" value="M"/>
                                </td>
                            </tr>
                            @empty

                            @endforelse
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="2">
                                    <input type="button" class="btn btn-lg btn-block btn-focus" id="addrow" value="Add Email Receiver" style="background-color:#1234A5; color:white; font-size:16px" />
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
        cols += '<input type="email" class="form-control emailrcv" name="emailrcv[]" required />';
        cols += '</td>';

        cols += '<td data-title="Action"><input type="button" class="ibtnDel btn btn-danger btn-focus"  value="Delete"></td>';
        cols += '<input type="hidden" class="op" name="op[]" value="A"/>';
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
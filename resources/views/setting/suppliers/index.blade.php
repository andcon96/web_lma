@extends('layout.layout')

@section('menu_name', 'Alert Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ url('/') }}">Master</a></li>
    <li class="breadcrumb-item active">Supplier Maintenance</li>
</ol>
@endsection

@section('content')

<input type="hidden" id="hd_suppcode">

<!-- Page Heading -->
<div class="d-flex mb-3">
    <form method="post" id="loadsupp" action='{{ route('loadsupplier') }}'>
        @method('POST')
        {{ csrf_field() }}
        <button type="submit" class="btn bt-action" style="width:200px;">Load Supplier</button>
    </form>
    <label for="suppcode" class="col-md-2 col-lg-2 col-form-label text-md-right">{{ __('Supplier Code') }}</label>
    <div class="col-md-4 col-lg-2">
        <input id="suppcode" type="text" class="form-control" name="suppcode" autocomplete="off" value="" autofocus>
    </div>
    <div class="offset-md-2 offset-lg-0">
        <input type="button" class="btn bt-ref" id="btnsearch" value="Search" style="margin-left:15px;" />
    </div>
    <div class="offset-md-0 offset-lg-0">
        <button class="btn bt-ref" id='btnrefresh' style="margin-left: 10px"><i class="fa fa-sync"></i></button>
    </div>
</div>

<div class="table-responsive tag-container mt-3" style="overflow-x: auto; display: block;white-space: nowrap;">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Supplier</th>
                <th>Supplier Desc</th>
                <th>Active</th>
                <th>Purchasing</th>
                <th width="10%">Edit</th>
            </tr>
        </thead>
        <tbody>
            @include('setting.suppliers.table')
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

            <form action="{{ route('suppmaint.update', 'test') }}" id="editsupp" method="post">

                {{ method_field('put') }}
                {{ csrf_field() }}

                <input type="hidden" name="edit_id" id="edit_id">

                <div class="modal-body">
                    <div class="form-group row">
                        <label for="supname" class="col-md-3 col-form-label text-md-right">{{ __('Supplier') }}</label>
                        <div class="col-md-7">
                            <input id="supname" type="text" class="form-control" name="supname" value="" disabled>
                            @if ($errors->has('supname'))
                            <span class="help-block">
                                <strong>{{ $errors->first('supname') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="active" class="col-md-3 col-form-label text-md-right">{{ __('Active') }}</label>
                        <div class="col-md-7">
                            <select id="active" name="active" class="form-control">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                            @if ($errors->has('active'))
                            <span class="help-block">
                                <strong>{{ $errors->first('active') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="poapprove" class="col-md-3 col-form-label text-md-right">{{ __('PO Approve')
                            }}</label>
                        <div class="col-md-7">
                            <select id="poapprove" name="poapprove" class="form-control">
                                <option value="1">Yes</option>
                                <option value="0">No</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="emailpur" class="col-md-3 col-form-label text-md-right">{{ __('Purchasing')
                            }}</label>
                        <div class="col-md-7">
                            <input id="emailpur" type="text" class="form-control" placeholder="Email,Email,Email"
                                autocomplete="off" name="emailpur" value="" autofocus>
                            @if ($errors->has('emailpur'))
                            <span class="help-block">
                                <strong>{{ $errors->first('emailpur') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="phone" class="col-md-3 col-form-label text-md-right">{{ __('Phone Number')
                            }}</label>
                        <div class="col-md-7">
                            <input id="phone" type="text" class="form-control" placeholder="+628....."
                                autocomplete="off" name="phone" value="" autofocus>
                            @if ($errors->has('phone'))
                            <span class="help-block">
                                <strong>{{ $errors->first('phone') }}</strong>
                            </span>
                            @endif
                        </div>
                    </div>

                    <div class="form-group">
                        <h5>
                            <center><strong>Alert Days</strong></center>
                        </h5>
                        <hr>
                    </div>

                    <div class="form-group row">
                        <div class="offset-md-1 col-md-3">
                            <input id="alertdays1" type="text" class="form-control" placeholder="Days" name="alertdays1"
                                value="{{ old('alert1') }}" autocomplete="off" autofocus>
                        </div>
                        <div class="col-md-7">
                            <input id="alertemail1" type="text" class="form-control" placeholder="Email,Email,Email"
                                name="alertemail1" value="{{ old('alert1') }}" autocomplete="off" autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="offset-md-1 col-md-3">
                            <input id="alertdays2" type="text" class="form-control" placeholder="Days" name="alertdays2"
                                value="{{ old('alert2') }}" autocomplete="off" autofocus>
                        </div>
                        <div class="col-md-7">
                            <input id="alertemail2" type="text" class="form-control" placeholder="Email,Email,Email"
                                name="alertemail2" value="{{ old('alert2') }}" autocomplete="off" autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="offset-md-1 col-md-3">
                            <input id="alertdays3" type="text" class="form-control" placeholder="Days" name="alertdays3"
                                value="{{ old('alert3') }}" autocomplete="off" autofocus>
                        </div>
                        <div class="col-md-7">
                            <input id="alertemail3" type="text" class="form-control" placeholder="Email,Email,Email"
                                name="alertemail3" value="{{ old('alert3') }}" autocomplete="off" autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="offset-md-1 col-md-3">
                            <input id="alertdays4" type="text" class="form-control" placeholder="Days" name="alertdays4"
                                value="{{ old('alert4') }}" autocomplete="off" autofocus>
                        </div>
                        <div class="col-md-7">
                            <input id="alertemail4" type="text" class="form-control" placeholder="Email,Email,Email"
                                name="alertemail4" value="{{ old('alert4') }}" autocomplete="off" autofocus>
                        </div>
                    </div>

                    <div class="form-group row">
                        <div class="offset-md-1 col-md-3">
                            <input id="alertdays5" type="text" class="form-control" placeholder="Days" name="alertdays5"
                                value="{{ old('alert5') }}" autocomplete="off" autofocus>
                        </div>
                        <div class="col-md-7">
                            <input id="alertemail5" type="text" class="form-control" placeholder="Email,Email,Email"
                                name="alertemail5" value="{{ old('alert5') }}" autocomplete="off" autofocus>
                        </div>
                    </div>

                    <div class="form-group">
                        <h5>
                            <center><strong>Idle Days</strong></center>
                        </h5>
                        <hr>
                    </div>

                    <div class="form-group row">
                        <div class="offset-md-1 col-md-3">
                            <input id="idledays" type="text" class="form-control" placeholder="Days" name="idledays"
                                value="{{ old('idledays') }}" autocomplete="off" autofocus>
                        </div>
                        <div class="col-md-7">
                            <input id="idleemail" type="text" class="form-control" placeholder="Email,Email,Email"
                                name="idleemail" value="{{ old('idleemail') }}" autocomplete="off" autofocus>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-info bt-action" id="e_btnclose"
                        data-dismiss="modal">Cancel</button>
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
    $(document).ready(function() {
            $('#loadsupp').on("submit", function() {
                $('#loader').removeClass('hidden')
            });

            $('#editsupp').on("submit", function() {
                document.getElementById('e_btnclose').style.display = 'none';
                document.getElementById('e_btnconf').style.display = 'none';
                document.getElementById('e_btnloading').style.display = '';
            });

        });



        $(document).on('click', '.editUser', function() { // Click to only happen on announce links

            //alert('tst');
            var uid = $(this).data('id');
            var supp_code = $(this).data('supp_code');
            var supp_name = $(this).data('supp_name');

            document.getElementById("edit_id").value = uid;
            document.getElementById("supname").value = supp_code.concat(' - ', supp_name);

            jQuery.ajax({
                type: "get",
                url: "{{ URL::to('searchsupplierwhenedit') }}",
                data: {
                    search: uid,
                },
                success: function(data) {

                    if (data[0]['supp_isActive'] == '1') {
                        document.getElementById("active").selectedIndex = "0";
                    } else {
                        document.getElementById("active").selectedIndex = "1";
                    }

                    if (data[0]['supp_po_appr'] == '1') {
                        document.getElementById("poapprove").selectedIndex = "0";
                    } else {
                        document.getElementById("poapprove").selectedIndex = "1";
                    }

                    document.getElementById("emailpur").value = data[0]['supp_email_pur'];
                    document.getElementById("alertdays1").value = data[0]['supp_day_one'];
                    document.getElementById("alertdays2").value = data[0]['supp_day_two'];
                    document.getElementById("alertdays3").value = data[0]['supp_day_three'];
                    document.getElementById("alertdays4").value = data[0]['supp_day_four'];
                    document.getElementById("alertdays5").value = data[0]['supp_day_five'];
                    document.getElementById("alertemail1").value = data[0]['supp_email_d_one'];
                    document.getElementById("alertemail2").value = data[0]['supp_email_d_two'];
                    document.getElementById("alertemail3").value = data[0]['supp_email_d_three'];
                    document.getElementById("alertemail4").value = data[0]['supp_email_d_four'];
                    document.getElementById("alertemail5").value = data[0]['supp_email_d_five'];
                    document.getElementById("idledays").value = data[0]['supp_idle_days'];
                    document.getElementById("idleemail").value = data[0]['supp_idle_emails'];
                    document.getElementById('phone').value = data[0]['supp_phone'];
                }
            });
        });

        function fetch_data(page, suppcode) {
            $.ajax({
                url: '/searchsupplier?page=' + page + '&suppcode=' + suppcode,
                beforeSend: function() {
                    $('#loader').removeClass('hidden');
                },
                success: function(data) {
                    console.log(data);
                    $('tbody').html('');
                    $('tbody').html(data);
                },
                complete: function() {
                    $('#loader').addClass('hidden');
                }
            })
        }

        $(document).on('click', '.pagination a', function(event) {
            event.preventDefault();
            var page = $(this).attr('href').split('page=')[1];

            var suppcode = $('#hd_suppcode').val();
            fetch_data(page, suppcode);
        });

        $('#btnsearch').on('click', function() {

            var suppcode = $('#suppcode').val();
            var page = 1;

            document.getElementById('hd_suppcode').value = suppcode;

            fetch_data(page, suppcode);

        });

        $('#btnrefresh').on('click', function() {

            var suppcode = '';
            var page = 1;
            document.getElementById('hd_suppcode').value = suppcode;

            fetch_data(page, suppcode);
        });
</script>
@endsection
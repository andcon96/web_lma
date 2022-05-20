@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Browse PO Reciept</li>
</ol>
@endsection

@section('content')

<form action="{{route('browseSJ')}}" method="GET">
    <div class="form-group row offset-lg-1">
        <div class="col-lg-2 col-md-4">
            <label for="ponbr" class="col-form-label text-md-right" style="margin-left:25px">{{ __('PO No.') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <input id="ponbr" type="text" class="form-control" name="ponbr" value="">
        </div>

    </div>
    <div class="form-group row offset-lg-1">
        <div class="col-lg-2 col-md-4">
            <label for="supp" class="col-form-label text-md-right" style="margin-left:25px">{{ __('Supplier') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-8 col-sm-12 col-xs-12">
            <select name="supp" id="supp" class="form-control">
                <option value="">Select Data</option>
            </select>
        </div>
        <div class="offset-md-3 offset-lg-0 offset-xl-0 offset-sm-0 offset-xs-0" id='btn'>
            <button class="btn bt-ref" id="btnsearch" value="search">Search</button>
            <button class="btn bt-action ml-2" id='btnrefresh' style="width: 40px !important"><i class="fa fa-sync"></i></button>
        </div>
    </div>
</form>

<div id="tabledata">
    @include('transaksi.porcpbrowse.table-index')
</div>

@endsection

@section('scripts')
<script>
    $("#supp").select2({
        width: '100%'
    });
    
    function resetSearch(){
        $('#ponbr').val('');
        $('#supp').val('');
    }

    $( function () {
        if (sessionStorage.getItem('show_message')) {
            Swal.fire('Surat Jalan Cancelled', '', 'success');
            sessionStorage.removeItem('show_message');
        }
    });

    $(document).ready(function() {
        var cur_url = window.location.href;

        let paramString = cur_url.split('?')[1];
        let queryString = new URLSearchParams(paramString);

        let customer = queryString.get('cust');
        let status = queryString.get('status');

        $('#cust').val(customer).trigger('change');
        $('#status').val(status).trigger('change');
    });
    
    $(document).on('click', '#btndel', function(e){
        e.preventDefault();
        let curl = $(this).data('url');

        Swal.fire({
            icon: 'warning',
            title: 'Cancel Surat Jalan',
            html: `<input type="text" id="reason" class="swal2-input" placeholder="Reason">`,
            confirmButtonText: 'Submit',
            confirmButtonColor: "#DD6B55",
            focusConfirm: false,
            preConfirm: () => {
                const reason = Swal.getPopup().querySelector('#reason').value
                if (!reason) {
                Swal.showValidationMessage(`Reason Cannot be Empty`)
                }
                return { reason: reason }
            }
            }).then((result) => {
                let reasons = `${result.value.reason}`
                $.ajax({
                    type: "POST",
                    url: curl,
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "reason": reasons
                    },
                    beforeSend: function() {
						$('#loader').removeClass('hidden');
					},
                    success: function (data) {
                        // $('#tabledata').html('').append(data);
                        // sessionStorage.reloadAfterPageLoad = true;
                        sessionStorage.setItem('show_message',true);
                        window.location.reload();
                    },complete: function() {
                        $('#loader').addClass('hidden');
                    },         
                });
                
            })
        
    });
    
    $(document).on('click', '#btnrefresh', function(){
        resetSearch();
    });
</script>
@endsection
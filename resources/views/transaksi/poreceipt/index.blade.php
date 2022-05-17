@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Purchase Order Receipt</li>
</ol>
@endsection


@section('content')

<form action="{{route('searchPO')}}" method="GET">
    <div class="form-group row">
        <label for="sjnbr" class="col-form-label text-md-right" style="margin-left:25px">{{ __('PO Contract') }}</label>
        <div class="col-xl-2 col-lg-2 col-md-8 col-sm-12 col-xs-12">
            <input id="sjnbr" type="text" class="form-control" name="sjnbr" required>
        </div>

        <div class="offset-md-3 offset-lg-0 offset-xl-0 offset-sm-0 offset-xs-0" id='btn'>
            <input type="submit" class="btn bt-ref" id="btnsearch" value="Search" style="margin-left:15px;" />
        </div>
    </div>
</form>

@endsection

@section('scripts')

<script>

    $(document).on('hide.bs.modal','#detailModal',function(){
        if(confirm("Are you sure, you want to close?")) return true;
        else return false;
    });

    $('#update').submit(function(event) {
        document.getElementById('btnclose').style.display = 'none';
        document.getElementById('btnconf').style.display = 'none';
        document.getElementById('btnloading').style.display = ''; 
    });

    $(document).on('change','#m_qtyrec',function(){
        var qtyship = document.getElementById("m_qtyship").value;
        var qtyrec = document.getElementById("m_qtyrec").value
        
        
        if(parseInt(qtyrec) > parseInt(qtyship)){
            setTimeout(function(){
                    alert("Qty Received is greater than Qty Ship");
                    document.getElementById("m_qtyrec").focus();
                    return false;
            },1);
        }
    });
</script>

@endsection
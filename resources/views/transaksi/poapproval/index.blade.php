@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Purchase Order Invoice Approval</li>
</ol>
@endsection


@section('content')

<form action="{{route('searchpoinvc')}}" method="GET">
    <div class="form-group row">
        <label for="ponbr" class="col-form-label text-md-right" style="margin-left:25px">{{ __('PO Number') }}</label>
        <div class="col-xl-2 col-lg-2 col-md-8 col-sm-12 col-xs-12">
            <input id="ponbr" type="text" class="form-control" name="ponbr">
        </div>
        <!-- <label for="receiptdate" class="col-form-label text-md-right" style="margin-left:25px">{{ __('Receipt Date') }}</label>
        <div class="col-xl-2 col-lg-2 col-md-8 col-sm-12 col-xs-12">
            <input id="receiptdate" type="text" class="form-control" name="receiptdate"
                value="{{ Carbon\Carbon::parse(now())->format('d-m-Y')  }}" readonly>
        </div> -->

        <div class="offset-md-3 offset-lg-0 offset-xl-0 offset-sm-0 offset-xs-0" id='btn'>
            <input type="submit" class="btn bt-ref" id="btnsearch" value="Search" style="margin-left:15px;" />
        </div>
    </div>
</form>

@endsection

@section('scripts')

<script>
    $("#sjnbr").select2({
        width : '100%'
    });
    $("#sjnbr").select2('open');

    $( "#effdate" ).datepicker({
        dateFormat : 'dd/mm/yy'
    });

    $( "#shipdate" ).datepicker({
        dateFormat : 'dd/mm/yy'
    });

    $(document).on('hide.bs.modal','#detailModal',function(){
        if(confirm("Are you sure, you want to close?")) return true;
        else return false;
    });

    $('#update').submit(function(event) {
        document.getElementById('btnclose').style.display = 'none';
        document.getElementById('btnconf').style.display = 'none';
        document.getElementById('btnloading').style.display = ''; 
    });


    $(document).on('click','.editUser',function(){ // Click to only happen on announce links
        var suratjalan = $(this).data('sj');
        var ponbr = $(this).data('nbr');
        var line = $(this).data('line');
        var part = $(this).data('part');
        var desc = $(this).data('desc');
        //alert('123');
        var suratjalan = $(this).data('sj');
        var ponbr = $(this).data('nbr');
        var line = $(this).data('line');
        var part = $(this).data('part');
        var desc = $(this).data('desc');
        var qtyord = $(this).data('qtyord');
        var qtyship = $(this).data('qtyship');
        var qtyrcvd = $(this).data('qtyrcvd');

        //var qtyopen = $(this).data('qtyopen');

        document.getElementById("m_sj").value = suratjalan;
        document.getElementById("m_ponbr").value = ponbr;
        document.getElementById("m_line").value = line;
        document.getElementById("m_itemcode").value = part;
        document.getElementById("m_itemdesc").value = desc;
        document.getElementById("m_qtyord").value = qtyord;
        document.getElementById("m_qtyrec").value = qtyrcvd;
         
        jQuery.ajax({
            type : "get",
            url : "{{URL::to("detailreceipt") }}",
            data:{
                suratjalan : suratjalan,
                ponbr : ponbr,
                line : line
            },
            success:function(data){
            //$('tbody').html(data);
                console.log(data);
                document.getElementById("m_um").value = data[0]['xpod_um'];
                document.getElementById("m_loc").value = data[0]['xpod_loc'];
                document.getElementById("m_lot").value = data[0]['xpod_lot'];
            }
        });


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
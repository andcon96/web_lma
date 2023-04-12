@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Detail Outstanding Invoice - {{$tahunbulan}}</li>
</ol>
@endsection


@section('content')


<div class="table-responsive offset-lg-1 col-lg-10 col-md-12 mt-4 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Invoice Number</th>
                <th>Total Invoice</th>
                <th>Sisa Total Invoice</th>
                <th>Posting Date</th>
                <th>Invoice Date</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($getinvoice as $index => $datas)
                <tr>
                    <td>{{$datas['t_invnbr']}}</td>
                    <td>{{number_format($datas['t_totalinv'],2,',','.')}}</td>
                    <td>{{number_format($datas['t_sisainv'],2,',','.')}}</td>
                    <td>{{$datas['t_postingdate']}}</td>
                    <td>{{$datas['t_invoicedate']}}</td>
                </tr>
            @empty
            <td colspan='7' class='text-danger'><b>No Data Available</b></td>
            @endforelse
        </tbody>
    </table>
</div>

<div class="offset-lg-1 col-lg-10 col-md-12 mt-4">
        <a href="{{ route('dashboard.index') }}" class="btn bt-ref" style="width:200px;"> Back</a>
</div>

@endsection
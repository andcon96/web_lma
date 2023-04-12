@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Detail Outstanding SJ - {{$part}}</li>
</ol>
@endsection


@section('content')


<div class="table-responsive offset-lg-1 col-lg-10 col-md-12 mt-4 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Surat Jalan</th>
                <th>SO Number</th>
                <th>Tanggal SJ</th>
                <th>Customer</th>
                <th>Qty Order</th>
                <th>Qty Input</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($listdetail as $index => $datas)
                <tr>
                    <td>{{$datas->getMaster->sj_nbr}}</td>
                    <td>{{$datas->getMaster->sj_so_nbr}}</td>
                    <td>{{$datas->created_at->format('d-m-Y')}}</td>
                    <td>{{$datas->getMaster->sj_so_cust}}</td>
                    <td>{{number_format($datas->sj_qty_ord,0,',','.')}}</td>
                    <td>{{number_format($datas->sj_qty_input,0,',','.')}}</td>
                    <td>{{number_format($datas->sj_price_ls,2,',','.')}}</td>
                    <td>{{number_format(($datas->sj_qty_input * $datas->sj_price_ls),2,',','.')}}</td>
                </tr>
            @empty
            <td colspan='7' class='text-danger'><b>No Data Available</b></td>
            @endforelse
        </tbody>
    </table>
    {{$listdetail->WithQueryString()->links()}}
</div>

<div class="offset-lg-1 col-lg-10 col-md-12 mt-4">
        <a href="{{ route('dashboard.index') }}" class="btn bt-ref" style="width:200px;"> Back</a>
</div>

@endsection
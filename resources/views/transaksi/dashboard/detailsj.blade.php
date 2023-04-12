@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Detail Outstanding SJ - {{$bulan}} - {{$tahun}}</li>
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
                <th>No Polis</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($listsj as $index => $datas)
                <tr>
                    <td>{{$datas->sj_nbr}}</td>
                    <td>{{$datas->sj_so_nbr}}</td>
                    <td>{{$datas->created_at->format('d-m-Y')}}</td>
                    <td>{{$datas->sj_so_cust}}</td>
                    <td>{{$datas->sj_nopol}}</td>
                </tr>
            @empty
            <td colspan='7' class='text-danger'><b>No Data Available</b></td>
            @endforelse
        </tbody>
    </table>
    {{$listsj->WithQueryString()->links()}}
</div>

<div class="offset-lg-1 col-lg-10 col-md-12 mt-4">
        <a href="{{ route('dashboard.index') }}" class="btn bt-ref" style="width:200px;"> Back</a>
</div>

@endsection
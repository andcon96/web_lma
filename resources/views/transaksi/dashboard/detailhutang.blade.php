@extends('layout.layout')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Transaksi</a></li>
        <li class="breadcrumb-item active">Detail Outstanding Supplier Invoice - {{ $tahunbulan }}</li>
    </ol>
@endsection


@section('content')
    <div class="table-responsive offset-lg-1 col-lg-10 col-md-12 mt-4 tag-container"
        style="overflow-x: auto; display: block;white-space: nowrap;">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Invoice Number</th>
                    <th>Customer</th>
                    <th>Posting Date</th>
                    <th>Invoice Date</th>
                    <th>Total Invoice</th>
                    <th>Sisa Total Invoice</th>
                </tr>
            </thead>
            <tbody>
                @php($custcode = '')
                @php($totalinv = 0)
                @php($totalsisainv = 0)
                @forelse ($gethutang as $index => $datas)
                    @if ($index != 0 && $custcode != $datas['t_custcode'])
                        <tr>
                            <td colspan="4"><b>Sub Total</b></td>
                            <td><b>{{ number_format($totalinv, 2, ',', '.') }}</b></td>
                            <td><b>{{ number_format($totalsisainv, 2, ',', '.') }}</b></td>
                        </tr>

                        @php($totalinv = 0)
                        @php($totalsisainv = 0)
                    @endif
                    <tr>
                        <td>{{ $datas['t_invnbr'] }}</td>
                        <td>{{ $datas['t_custcode'] }} - {{ $datas['t_custname'] }}</td>
                        <td>{{ $datas['t_postingdate'] }}</td>
                        <td>{{ $datas['t_invoicedate'] }}</td>
                        <td>{{ number_format($datas['t_totalinv'], 2, ',', '.') }}</td>
                        <td>{{ number_format($datas['t_sisainv'], 2, ',', '.') }}</td>
                    </tr>
                    @php($custcode = $datas['t_custcode'])
                    @php($totalinv += $datas['t_totalinv'])
                    @php($totalsisainv += $datas['t_sisainv'])

                    @if ($loop->last)
                        <tr>
                            <td colspan="4"><b>Sub Total</b></td>
                            <td><b>{{ number_format($totalinv, 2, ',', '.') }}</b></td>
                            <td><b>{{ number_format($totalsisainv, 2, ',', '.') }}</b></td>
                        </tr>
                    @endif
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

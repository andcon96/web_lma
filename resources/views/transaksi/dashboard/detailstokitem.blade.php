@extends('layout.layout')

@section('breadcrumbs')
    <ol class="breadcrumb float-sm-right">
        <li class="breadcrumb-item"><a href="{{ url('/') }}">Transaksi</a></li>
        <li class="breadcrumb-item active">Detail Stok Item - {{ $lokasi }}</li>
    </ol>
@endsection


@section('content')
    <div class="table-responsive offset-lg-1 col-lg-10 col-md-12 mt-4 tag-container"
        style="overflow-x: auto; display: block;white-space: nowrap;">
        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
            <thead>
                <tr>
                    <th>Item Part</th>
                    <th>UM</th>
                    <th>Location</th>
                    <th>Lot</th>
                    @if ($lokasi == 'Ongoing Web')
                        <th>Qty Web</th>
                    @else
                        <th>Qty OH</th>
                    @endif
                </tr>
            </thead>
            <tbody>
                @forelse ($data as $index => $datas)
                    @if (($lokasi != 'Ongoing Web' && $datas->t_qtyoh != 0) || ($lokasi == 'Ongoing Web' && $datas->t_qtyinput_web != 0))
                        <tr>
                            <td>{{ $datas->t_part }} -- {{ $datas->t_desc1 }}</td>
                            <td>{{ $datas->t_um }}</td>
                            <td>{{ $datas->t_location }}</td>
                            <td>{{ $datas->t_lot }}</td>
                            @if ($lokasi == 'Ongoing Web')
                                <td>{{ number_format((float) $datas->t_qtyinput_web, 2, '.', ',') }}</td>
                            @else
                                <td>{{ number_format((float) $datas->t_qtyoh, 2, '.', ',') }}</td>
                            @endif
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

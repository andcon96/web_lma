@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Detail Outstanding SJ - {{$part}}</li>
</ol>
@endsection


@section('content')

<form action="{{ url()->current() }}" method="GET">
    <div class="form-group row">
        <div class="col-lg-2 col-md-4">
            <label for="sjnbr" class="col-form-label text-md-right">{{ __('SJ Nbr.') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
            <select name="sjnbr" id="sjnbr" class="form-control selectdata">
                <option value="">Select Data</option>
                @foreach ($listsj as $listsjs)
                    <option value="{{ $listsjs->getMaster->sj_nbr }}">{{ $listsjs->getMaster->sj_nbr }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-lg-2 col-md-4">
            <label for="sonbr" class="col-form-label text-md-right">{{ __('SO Nbr.') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
            <select name="sonbr" id="sonbr" class="form-control selectdata">
                <option value="">Select Data</option>
                @foreach ($listsj as $listsjs)
                    <option value="{{ $listsjs->getMaster->sj_so_nbr }}">{{ $listsjs->getMaster->sj_so_nbr }}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-2 col-md-4">
            <label for="startdate" class="col-form-label text-md-right">{{ __('Start Date') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
            <input id="startdate" type="text" class="form-control datepick" name="startdate" autocomplete="off"
                value="{{ request()->input('startdate') ? request()->input('startdate') : '' }}">
        </div>
        <div class="col-lg-2 col-md-4">
            <label for="enddate" class="col-form-label text-md-right">{{ __('End Date') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
            <input id="enddate" type="text" class="form-control datepick" name="enddate" autocomplete="off"
                value="{{ request()->input('enddate') ? request()->input('enddate') : '' }}">
        </div>
    </div>
    <div class="form-group row">
        <div class="col-lg-2 col-md-4">
            <label for="cust" class="col-form-label text-md-right">{{ __('Customer') }}</label>
        </div>
        <div class="col-xl-3 col-lg-3 col-md-4 col-sm-12 col-xs-12">
            <select name="cust" id="cust" class="form-control selectdata">
                <option value="">Select Data</option>
                @foreach ($lucust as $lucusts)
                    <option value="{{ $lucusts->sj_so_cust }}">{{ $lucusts->sj_so_cust }} -  {{$lucusts->getDetailCust->cust_name}}</option>
                @endforeach
            </select>
        </div>
        <div class="col-xl-2 col-lg-2 col-md-12 col-sm-12 col-xs-12 mt-xl-0 mt-lg-0 mt-3">
            <button class="btn bt-ref" id="btnsearch" value="search">Search</button>
            <button class="btn bt-action ml-2" id='btnrefresh' style="width: 40px !important"><i
                    class="fa fa-sync"></i></button>
        </div>
    </div>
</form>

<div class="table-responsive col-lg-12 col-md-12 mt-4 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
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
                    <td>{{$datas->getMaster->sj_so_cust}} - {{$datas->getMaster->getDetailCust->cust_name}}</td>
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

<div class=" col-lg-10 col-md-12 mt-4">
        <a href="{{ route('dashboard.index') }}" class="btn bt-ref" style="width:200px;"> Back</a>
</div>

@endsection



@section('scripts')
    <script>
        $('.selectdata').select2({
            width: '100%'
        });
        $(".datepick").datepicker({
            dateFormat: 'yy-mm-dd',
        });

        $(document).ready(function() {
            var cur_url = window.location.href;

            let paramString = cur_url.split('?')[1];
            let queryString = new URLSearchParams(paramString);

            let sjnbr = queryString.get('sjnbr');
            let sonbr = queryString.get('sonbr');
            let cust = queryString.get('cust');
            let startdate = queryString.get('startdate');
            let enddate = queryString.get('enddate');

            $('#startdate').val(startdate);
            $('#enddate').val(enddate);
            $('#sjnbr').val(sjnbr).trigger('change');
            $('#sonbr').val(sonbr).trigger('change');
            $('#cust').val(cust).trigger('change');
        });

        function resetSearch() {
            $('#sjnbr').val('');
            $('#sonbr').val('');
            $('#cust').val('');
            $('#startdate').val('');
            $('#enddate').val('');
        }

        $(document).on('click', '#btnrefresh', function() {
            resetSearch();
        });
    </script>
@endsection

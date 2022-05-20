<div class="table-responsive col-lg-12 col-md-12 mt-4 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>PO No.</th>
                <th style="width: 35%;">Supplier</th>
                <th>Item</th>
                <th>Total Qty Receipt</th>
                <th>Qty FG</th>
                <th>Qty Reject</th>
                <th>Receipt Date</th>
                <th>Receipt By</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $datas)
                <tr>
                    <td>{{$datas->sj_nbr}}</td>
                    <td>{{$datas->sj_so_nbr}}</td>
                    <td>{{$datas->created_at->format('d-m-Y')}}</td>
                    <td>{{$datas->sj_so_cust}} -- {{$datas->getDetailCust->cust_name}}</td>
                    {{-- <td>{{$datas->sj_so_ship}} -- {{$datas->getDetailShip->cust_name}}</td>
                    <td>{{$datas->sj_so_bill}} -- {{$datas->getDetailBill->cust_name}}</td> --}}
                    <td>{{$datas->sj_status}}</td>
                    <td>
                        @if($datas->sj_status == 'New')
                        <a href="{{route('editSJBrowse',$datas->id) }}"><i class="fas fa-edit"></i></a>
                        <a href="{{route('deleteSJBrowse',$datas->id) }}" id="btndel" 
                            data-url="{{route('deleteSJBrowse',$datas->id) }}"><i class="fas fa-trash"></i></a>
                        @else
                        <a href="{{route('viewSJBrowse',$datas->id) }}"><i class="fas fa-eye"></i></a>
                            @if($datas->sj_status == 'Cancelled')
                                <a href="{{route('changeSJBrowse',$datas->id) }}"><i class="fas fa-redo"></i></a>
                            @endif
                        @endif
                    </td>
                </tr>
            @empty
            <td colspan='7' class='text-danger'><b>No Data Available</b></td>
            @endforelse
        </tbody>
    </table>
    {{$data->WithQueryString()->links()}}
</div>
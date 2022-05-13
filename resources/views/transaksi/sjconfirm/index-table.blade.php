<div class="table-responsive col-lg-12 col-md-12 mt-4 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Surat Jalan</th>
                <th>SO Number</th>
                <th style="width: 20%;">Customer</th>
                <th style="width: 20%;">Ship To</th>
                <th style="width: 20%;">Bill To</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $datas)
                <tr>
                    <td>{{$datas->sj_nbr}}</td>
                    <td>{{$datas->sj_so_nbr}}</td>
                    <td>{{$datas->sj_so_cust}} -- {{$datas->getDetailCust->cust_name ?? ''}}</td>
                    <td>{{$datas->sj_so_ship}} -- {{$datas->getDetailShip->cust_name ?? ''}}</td>
                    <td>{{$datas->sj_so_bill}} -- {{$datas->getDetailBill->cust_name ?? ''}}</td>
                    <td>{{$datas->sj_status}}</td>
                    <td>
                        @if($datas->sj_status == 'New')
                            <a href="{{route('sjconfirm.edit',$datas->id) }}"><i class="fas fa-edit"></i></a>
                        @else
                            <a href="{{route('sjconfirm.show',$datas->id) }}"><i class="fas fa-eye"></i></a>
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
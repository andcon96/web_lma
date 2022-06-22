<div class="table-responsive col-lg-12 col-md-12 mt-4 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 8%;">Surat Jalan</th>
                <th style="width: 12%;">SO Number</th>
                <th style="width: 12%;">Tanggal SJ</th>
                <th style="width: 30%;">Customer</th>
                <th style="width: 12%;">No. Polisi</th>
                {{-- <th style="width: 20%;">Ship To</th>
                <th style="width: 20%;">Bill To</th> --}}
                <th style="width: 7%;">Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $datas)
                <tr>
                    <td>{{$datas->sj_nbr}}</td>
                    <td>{{$datas->sj_so_nbr}}</td>
                    <td>{{$datas->created_at->format('d-m-Y')}}</td>
                    <td>{{$datas->sj_so_cust}} -- {{$datas->getDetailCust->cust_name}}</td>
                    <td>{{$datas->sj_nopol}}</td>
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
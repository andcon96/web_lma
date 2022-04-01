<div class="table-responsive offset-lg-1 col-lg-10 col-md-12 mt-4 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Surat Jalan</th>
                <th>SO Number</th>
                <th>Customer</th>
                <th>Ship To</th>
                <th>Bill To</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $datas)
                <tr>
                    <td>{{$datas->sj_nbr}}</td>
                    <td>{{$datas->sj_so_nbr}}</td>
                    <td>{{$datas->sj_so_cust}}</td>
                    <td>{{$datas->sj_so_ship}}</td>
                    <td>{{$datas->sj_so_bill}}</td>
                    <td>{{$datas->sj_status}}</td>
                    <td>
                        @if($datas->sj_status == 'New')
                        <a href="{{route('editSJBrowse',$datas->id) }}"><i class="fas fa-edit"></i></a>
                        <a href="{{route('deleteSJBrowse',$datas->id) }}" id="btndel" data-url="{{route('deleteSJBrowse',$datas->id) }}"><i class="fas fa-trash"></i></a>
                        @else
                        <a href="{{route('viewSJBrowse',$datas->id) }}"><i class="fas fa-eye"></i></a>
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
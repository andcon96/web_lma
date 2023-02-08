<div class="table-responsive offset-lg-1 col-lg-10 col-md-12 mt-4 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 10%;">Domain</th>
                <th style="width: 15%;">Item Part</th>
                <th style="width: 35%;">Item Desc</th>
                <th style="width: 10%;">UM</th>
                <th style="width: 10%;">Lokasi</th>
                <th style="width: 10%;">Qty OH</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $index => $datas)
                <tr>
                    <td>{{$datas->t_dom}}</td>
                    <td>{{$datas->t_part}}</td>
                    <td>{{$datas->t_desc1}} {{$datas->t_desc2}}</td>
                    <td>{{$datas->t_um}}</td>
                    <td>{{$datas->t_location}}</td>
                    <td>{{$datas->t_qtyoh}}</td>
                </tr>
            @empty
            <td colspan='7' class='text-danger'><b>No Data Available</b></td>
            @endforelse
        </tbody>
    </table>
</div>
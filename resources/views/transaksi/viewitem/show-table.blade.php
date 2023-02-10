<div class="table-responsive mt-4 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 8%;">Domain</th>
                <th style="width: 15%;">Item Part</th>
                <th style="width: 30%;">Item Desc</th>
                <th style="width: 6%;">UM</th>
                <th style="width: 8%;">Lokasi</th>
                <th style="width: 8%;">Lot</th>
                <th style="width: 8%;">Qty OH</th>
                <th style="width: 10%;">Qty Web</th>
                <th style="width: 9%;">Qty Sisa</th>
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
                    <td>{{$datas->t_lot}}</td>
                    <td>{{number_format($datas->t_qtyoh,2)}}</td>
                    <td>{{number_format($datas->t_qtyinput_web,2)}}</td>
                    <td>{{number_format($datas->t_qtysisa,2)}}</td>
                </tr>
            @empty
            <td colspan='7' class='text-danger'><b>No Data Available</b></td>
            @endforelse
        </tbody>
    </table>
</div>
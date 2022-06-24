<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>SJ Number</th>
            <th>SO Number</th>
            <th>PO Number</th>
            <th>Customer</th>
            <th>Ship To</th>
            <th>No. Polisi</th>
            <th>Remark</th>
            <th>Status</th>
            <th>Tanggal SJ</th>
            <th>Confirm Date</th>
            <th>Line</th>
            <th>Part</th>
            <th>Loc</th>
            <th>Truk/Kapal</th>
            <th>Qty Order</th>
            <th>Qty Kirim</th>
            <th>Qty Actual</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($sj as $index => $datas)
        <tr>
            <td>{{$datas->id}}</td>
            <td>{{$datas->sj_nbr}}</td>
            <td>{{$datas->sj_so_nbr}}</td>
            <td>{{$datas->sj_so_po}}</td>
            <td>{{$datas->getDetailCust->cust_name}}</td>
            <td>{{$datas->getDetailShip->cust_name}}</td>
            <td>{{$datas->sj_nopol}}</td>
            <td>{{$datas->sj_remark}}</td>
            <td>{{$datas->sj_status}}</td>
            <td>{{$datas->created_at}}</td>
            <td>{{$datas->sj_eff_date}}</td>
            
            <td>{{$datas->getDetail->sj_part}} -- {{$datas->getDetail->sj_part_desc}}</td>
            <td>{{$datas->getDetail->sj_loc}}</td>
            <td>{{$datas->getDetail->sj_lot}}</td>
            <td>{{$datas->getDetail->sj_qty_ord}}</td>
            <td>{{$datas->getDetail->sj_qty_input}}</td>
            <td>{{$datas->getDetail->sj_qty_rcvd}}</td>
        </tr>
        @empty
        <tr>
            <td class="text-danger" colspan='12'>
                <center><b>No Data Available</b></center>
            </td>
        </tr>
        @endforelse
    </tbody>
</table>

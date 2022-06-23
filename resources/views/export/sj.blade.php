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
        @forelse ($sj as $show)
        <tr>
            <td>{{$show->id}}</td>
            <td>{{$show->sj_nbr}}</td>
            <td>{{$show->sj_so_nbr}}</td>
            <td>{{$show->sj_so_po}}</td>
            <td>{{$show->getDetailCust->cust_name}}</td>
            <td>{{$show->getDetailShip->cust_name}}</td>
            <td>{{$show->sj_nopol}}</td>
            <td>{{$show->sj_remark}}</td>
            <td>{{$show->sj_status}}</td>
            <td>{{$show->created_at}}</td>
            <td>{{$show->sj_eff_date}}</td>
            <td>{{$show->getDetail->sj_line}}</td>
            <td>{{$show->getDetail->sj_part}} -- {{$show->getDetail->sj_part_desc}}</td>
            <td>{{$show->getDetail->sj_loc}}</td>
            <td>{{$show->getDetail->sj_lot}}</td>
            <td>{{$show->getDetail->sj_qty_ord}}</td>
            <td>{{$show->getDetail->sj_qty_input}}</td>
            <td>{{$show->getDetail->sj_qty_rcvd}}</td>
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

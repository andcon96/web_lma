<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Domain</th>
            <th>PO Kontrak</th>
            <th>PO Number</th>
            <th>Line</th>
            <th>Item</th>
            <th>Supplier</th>
            <th>Receipt Date</th>
            <th>Qty Order</th>
            <th>Qty Receive</th>
            <th>Qty Open</th>
            <th>Location</th>
            <th>Gudang/Kapal</th>
            <th>Qty Supplier</th>
            <th>Qty Terima</th>
            <th>Qty Reject</th>
            <th>Qty Lebih</th>
            <th>Dibuat Oleh</th>
            <th>Dibuat Tanggal</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($po as $show)
        <tr>
            <td>{{$show->id}}</td>
            <td>{{$show->ph_domain}}</td>
            <td>{{$show->ph_pokontrak}}</td>
            <td>{{$show->ph_ponbr}}</td>
            <td>{{$show->ph_line}}</td>
            <td>{{$show->ph_part}} -- {{$show->ph_partname}}</td>
            <td>{{$show->ph_suppname}}</td>
            <td>{{$show->ph_receiptdate}}</td>
            <td>{{$show->ph_qty_order}}</td>
            <td>{{$show->ph_qty_rcvd}}</td>
            <td>{{$show->ph_qty_order - $show->ph_qty_rcvd}}</td>
            <td>{{$show->ph_loc}}</td>
            <td>{{$show->ph_lot}}</td>
            <td>{{$show->ph_qty_terima}}</td>
            <td>{{$show->ph_qty_fg}}</td>
            <td>{{$show->ph_qty_rjct}}</td>
            <td>{{$show->ph_qty_lebih}}</td>
            <td>{{$show->getUser->name}}</td>
            <td>{{$show->created_at}}</td>
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
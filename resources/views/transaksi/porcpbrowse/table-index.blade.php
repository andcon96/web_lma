<div class="table-responsive col-lg-12 col-md-12 mt-4 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
    <table class="table table-bordered" id="dataTable" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 10%">PO Contract</th>
                <th style="width: 10%;">PO No.</th>
                <th style="width: 20%;">Supplier</th>
                <th>No. Polisi</th>
                <th style="width: 20%;">Item</th>
                <th style="width: 10%;">Qty Receipt</th>
                <th style="width: 10%;">Qty FG</th>
                <th style="width: 10%;">Qty Reject</th>
                <th style="width: 10%;">Qty Lebih</th>
                <th style="width: 10%;">Receipt Date</th>
                <th style="width: 10%;">Eff. Date</th>
                <th style="width: 10%;">Receipt By</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($datas as $show)
                <tr>
                    <td>{{$show->ph_pokontrak}}</td>
                    <td>{{$show->ph_ponbr}}</td>
                    <td>{{$show->ph_supp}} -- {{$show->ph_suppname}}</td>
                    <td>{{$show->ph_nopol}}</td>
                    <td>{{$show->ph_part}} -- {{$show->ph_partname}}</td>
                    <td>{{$show->ph_qty_terima}}</td>
                    <td>{{$show->ph_qty_fg}}</td>
                    <td>{{$show->ph_qty_rjct}}</td>
                    <td>{{$show->ph_qty_lebih}}</td>
                    @if ($show->ph_receiptdate != null)
                        <td>{{$show->ph_receiptdate}}</td>
                    @else
                        <td>-</td>
                    @endif
                    @if ($show->ph_effdate != null)
                        <td>{{$show->ph_effdate}}</td>
                    @else
                        <td>-</td>
                    @endif
                    <td>{{$show->getUser->name}}
                </tr>                
            @empty
            <tr>
                <td class="text-danger" colspan='12'>
                    <center><b>No Data Available</b></center>
                </td>
            </tr>
            @endforelse
            
        </tbody>
        <tfoot>
            <tr style="border:0 !important">
                <td colspan="12">
                    {{ $datas->withQueryString()->links() }}
                </td>
            </tr>
        </tfoot>
    </table>
</div>
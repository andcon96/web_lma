<div class="table-responsive col-lg-12 col-md-12 mt-4 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 10%;">PO No.</th>
                <th style="width: 20%;">Supplier</th>
                <th style="width: 20%;">Item</th>
                <th style="width: 10%;">Qty Receipt</th>
                <th style="width: 10%;">Qty FG</th>
                <th style="width: 10%;">Qty Reject</th>
                <th style="width: 10%;">Receipt Date</th>
                <th style="width: 10%;">Receipt By</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($datas as $show)
                <tr>
                    <td>{{$show->ph_ponbr}}</td>
                    <td>{{$show->ph_supp}}</td>
                    <td>{{$show->ph_part}}</td>
                    <td>{{$show->ph_qty_terima}}</td>
                    <td>{{$show->ph_qty_fg}}</td>
                    <td>{{number_format($show->ph_qty_terima-$show->ph_qty_fg,2)}}</td>
                    <td>{{$show->ph_receiptdate}}</td>
                    <td>{{$show->created_by}}
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
<div class="table-responsive col-lg-12 col-md-12 mt-4 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
    <table class="table table-bordered" id="dataTable" cellspacing="0">
        <thead>
            <tr>
                <th style="width: 10%">Domain</th>
                <th style="width: 10%;">PO No.</th>
                <th style="width: 12%;">PO Contract</th>
                <th style="width: 10%;">Nomor Polisi</th>
                <th style="width: 10%;">Receipt Date</th>
                <th style="width: 20%;">Item</th>
                <th style="width: 10%;">Qty Unplanned</th>
                <th style="width: 10%;">Action</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($rcpt as $show)
                <tr>
                    <td>{{$show->domain}}</td>
                    <td>{{$show->ponbr}}</td>
                    <td>{{$show->pokontrak}}</td>
                    <td>{{$show->nopol}}</td>
                    <td>{{$show->receiptdate}}</td>
                    <td>{{$show->part}} -- {{$show->partdesc}}</td>
                    <td>{{$show->qty_unplanned}}</td>
                    <td><a href="{{ route('rcptunplanned.show',$show->id) }}"><i class="fas fa-edit"></i></a></td>
                    </td>
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
                    {{ $rcpt->withQueryString()->links() }}
                </td>
            </tr>
        </tfoot>
    </table>
</div>
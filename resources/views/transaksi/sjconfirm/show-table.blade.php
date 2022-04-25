<div class="table-responsive offset-lg-1 col-lg-10 col-md-12 mt-4 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Line</th>
                <th width="25%">Part</th>
                <th>Qty Order</th>
                <th>Qty Input</th>
                <th width="25%">Location</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data->getDetail as $index => $datas)
                <tr>
                    <td>{{$datas->sj_line}}</td>
                    <td>{{$datas->sj_part}}</td>
                    <td>{{$datas->sj_qty_ord}}</td>
                    <td>
                        <input type="hidden" value="{{$datas->sj_line}}" name="line[]">
                        <input type="hidden" class="form-control" name="qtyinp[]" value="{{$datas->sj_qty_input}}" 
                        max="{{$datas->sj_qty_input}}" readonly>
                        {{$datas->sj_qty_input}}
                    </td>
                    <td>
                        {{$datas->sj_loc}}
                    </td>
                </tr>
            @empty
            <td colspan='7' class='text-danger'><b>No Data Available</b></td>
            @endforelse
        </tbody>
    </table>
</div>
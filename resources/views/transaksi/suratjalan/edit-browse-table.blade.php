<div class="table-responsive offset-lg-1 col-lg-10 col-md-12 mt-4 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Line</th>
                <th width="45%">Part</th>
                <th>Qty Order QAD</th>
                <th>Qty Open</th>
                <th>Qty Input</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data->getDetail as $index => $datas)
                <tr>
                    <td>{{$datas->sj_line}}</td>
                    <td>{{$datas->sj_part}} - {{$datas->sj_part_desc}}</td>
                    <td>{{$datas->sj_qty_ord}}</td>
                    @php
                        $totqtyinput = $listsjopen->where('sj_line',$datas->sj_line)->where('sj_part',$datas->sj_part)->sum('sj_qty_input');
                    @endphp
                    <td>{{number_format($datas->sj_qty_ord - $datas->sj_qty_ship - $totqtyinput ,2)}}</td>
                    <td>
                        <input type="number" class="form-control" name="qtyinp[]" value="{{$datas->sj_qty_input}}" 
                        max="{{$datas->sj_qty_input}}">
                    </td>
                    <td>
                        <input type="hidden" name="iddetail[]" value="{{$datas->id}}">
                        <input type="hidden" name="operation[]" class="operation" value="M">
                        <input type="checkbox" class="qaddel" value="Y"> 
                    </td>
                </tr>
            @empty
            <td colspan='7' class='text-danger'><b>No Data Available</b></td>
            @endforelse
        </tbody>
    </table>
</div>
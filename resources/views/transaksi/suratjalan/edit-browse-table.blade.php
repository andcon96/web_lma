<div class="table-responsive col-lg-12 col-md-12 mt-4 tag-container" style="overflow-x: auto; overflow-y:hidden; display: inline-table;white-space: nowrap;">
    <table class="table table-bordered" style="margin-bottom: 100px;" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Line</th>
                <th width="25%">Item</th>
                <th>Qty Order QAD</th>
                <th>Qty Open</th>
                <th>Qty SJ</th>
                <th width="25%">Location </th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data->getDetail as $index => $datas)
                <tr>
                    <td>{{$datas->sj_line}}</td>
                    <td>{{$datas->sj_part}} - {{$datas->sj_part_desc}}</td>
                    <td>{{number_format($datas->sj_qty_ord,2)}}</td>
                    @php
                        $totqtyongoing = $listsjopen->where('sj_line',$datas->sj_line)->where('sj_part',$datas->sj_part)->sum('sj_qty_input');
                        $totqtyshipped = $listsjship->where('sj_line',$datas->sj_line)->where('sj_part',$datas->sj_part)->sum('sj_qty_input');
                    @endphp
                    <td>{{number_format($datas->sj_qty_ord - $totqtyshipped - $totqtyongoing ,2)}}</td>
                    <td>
                        <input type="number" class="form-control" style="width: 100px !important;" name="qtyinp[]" min="0" step="0.01" value="{{$datas->sj_qty_input}}" >
                    </td>
                    <td>
                        <select name="partloc[]" class="form-control selectpicker" data-width="200px" data-style="btn-custom" data-size='4' data-live-search="true">
                            @foreach ($loc as $locs)
                                <option value="{{$locs->loc}}" {{$locs->loc == $datas->sj_loc ? 'Selected' : ''}} >{{$locs->loc}} -- {{$locs->loc_desc}}</option>
                            @endforeach
                        </select>
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
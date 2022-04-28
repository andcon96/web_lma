<div class="table-responsive offset-lg-1 col-lg-10 col-md-12 mt-4 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
    <table class="table table-bordered" style="margin-bottom:100px;" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Line</th>
                <th width="25%">Part</th>
                <th>Qty Order</th>
                <th>Qty Open</th>
                <th>Qty SJ</th>
                <th width="15%">Qty Input</th>
                <th width="25%">Location</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data->getDetail as $index => $datas)
                <tr>
                    <td>{{$datas->sj_line}}</td>
                    <td>{{$datas->sj_part}}</td>
                    <td>{{number_format($datas->sj_qty_ord,2)}}</td>
                    @php
                        $totqtyongoing = $listsjopen->where('sj_line',$datas->sj_line)->where('sj_part',$datas->sj_part)->sum('sj_qty_input');
                        $totqtyshipped = $listsjship->where('sj_line',$datas->sj_line)->where('sj_part',$datas->sj_part)->sum('sj_qty_rcvd');
                    @endphp
                    <td>{{number_format($datas->sj_qty_ord - $totqtyshipped - $totqtyongoing ,2)}}</td>
                    <td>{{number_format($datas->sj_qty_input,2)}}</td>
                    <td>
                        <input type="hidden" value="{{$datas->id}}" name="iddetail[]">
                        <input type="hidden" value="{{$datas->sj_line}}" name="line[]">
                        <input type="hidden" value="{{$datas->sj_loc}}" name="loc[]">
                        <input type="hidden" value="{{$datas->sj_qty_ord}}" name="qtyord[]">
                        <input type="number" class="form-control" name="qtyinp[]" value="{{$datas->sj_qty_input}}">
                    </td>
                    <td>
                        <select name="partloc[]" class="form-control selectpicker" data-style="btn-custom" data-size='4' data-live-search="true">
                            @foreach ($loc as $locs)
                                <option value="{{$locs->loc}}" {{$locs->loc == $datas->sj_loc ? 'Selected' : ''}} >{{$locs->loc}} -- {{$locs->loc_desc}}</option>
                            @endforeach
                        </select>
                    </td>
                </tr>
            @empty
            <td colspan='7' class='text-danger'><b>No Data Available</b></td>
            @endforelse
        </tbody>
    </table>
</div>
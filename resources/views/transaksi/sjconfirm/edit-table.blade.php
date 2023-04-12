<div class="table-responsive col-lg-12 col-md-12 mt-4 tag-container" style="overflow-x: auto; overflow-y:hidden; display: inline-table; white-space: nowrap;">
    <table class="table table-bordered" id="dataTable" cellspacing="0">
        <thead>
            <tr>
                <th>Line</th>
                <th width="25%">Item</th>
                <th>Qty Order</th>
                <th>Qty Open</th>
                <th>Qty SJ</th>
                <th width="15%">Qty Input</th>
                <th width="25%">Location</th>
                <th width="10%">Lot</th>
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
                        $totqtyshipped = $listsjship->where('sj_line',$datas->sj_line)->where('sj_part',$datas->sj_part)->sum('sj_qty_input');
                        $qtyshipqad = $soqad;
                    @endphp
                    <td>{{number_format($datas->sj_qty_ord - $qtyshipqad + $totqtyongoing ,2)}}</td>
                    <td>{{number_format($datas->sj_qty_input,2)}}</td>
                    <td>
                        <input type="hidden" value="{{$datas->id}}" name="iddetail[]">
                        <input type="hidden" value="{{$datas->sj_line}}" name="line[]">
                        <input type="hidden" value="{{$datas->sj_loc}}" name="loc[]">
                        <input type="hidden" value="{{$datas->sj_part}}" name="part[]">
                        <input type="hidden" value="{{$datas->sj_qty_input}}" name="qtysj[]">
                        <input type="hidden" value="{{$datas->sj_price_ls}}" name="price[]">
                        <input type="number" class="form-control" style="width: 150px !important;" name="qtyinp[]" step="0.01" value="{{ old('qtyinp.'.$index) ? old('qtyinp.'.$index) : $datas->sj_qty_input}}">
                    </td>
                    <td>
                        <select name="partloc[]" class="form-control selectpicker" data-width="200px" data-style="btn-custom" data-size='4' data-dropup-auto="false" data-live-search="true">
                            @foreach ($loc as $locs)
                                @if(old('partloc'))
                                <option value="{{$locs->loc}}" {{ old('partloc.'.$index) == $locs->loc ? 'Selected' : '' }} >
                                    {{$locs->loc}} -- {{$locs->loc_desc}}
                                </option>
                                @else
                                <option value="{{$locs->loc}}" {{$locs->loc == $datas->sj_loc ? 'Selected' : ''}}>
                                    {{$locs->loc}} -- {{$locs->loc_desc}}
                                </option>
                                @endif
                            @endforeach
                        </select>
                    </td>
                    <td>
                        <input type="text" class="form-control" style="width: 150px !important;" name="lot[]" value="{{ old('lot.'.$index) ? old('lot.'.$index) : $datas->sj_lot }}">
                    </td>
                </tr>
            @empty
            <td colspan='7' class='text-danger'><b>No Data Available</b></td>
            @endforelse
        </tbody>
    </table>
</div>
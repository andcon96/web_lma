<div class="table-responsive offset-lg-1 col-lg-10 col-md-12 mt-4 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
        <thead>
            <tr>
                <th>Line</th>
                <th width="50%">Part</th>
                <th>Qty Order</th>
                <th>Qty Ongoing</th>
                <th>Qty Ship</th>
                <th>Qty Input</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($so as $index => $show)
            <tr>
                <td>
                    {{$show->sod_line}}
                    <input type="hidden" name="sodline[]" value="{{$show->sod_line}}" {{$show->sod_qty_ord <= $show->sod_qty_ship + $show->sod_qty_ongoing ? 'disabled':''}} />
                </td>
                <td>{{$show->sod_part}} - {{$show->sod_part_desc}}
                    <input type="hidden" name="sodpart[]" value="{{$show->sod_part}}" {{$show->sod_qty_ord <= $show->sod_qty_ship + $show->sod_qty_ongoing ? 'disabled':''}} />
                    <input type="hidden" name="soddesc[]" value="{{$show->sod_part_desc}}" {{$show->sod_qty_ord <= $show->sod_qty_ship + $show->sod_qty_ongoing ? 'disabled':''}}>
                    <input type="hidden" name="sodloc[]" value="{{$show->sod_loc}}" {{$show->sod_qty_ord <= $show->sod_qty_ship + $show->sod_qty_ongoing ? 'disabled':''}}>
                </td>
                <td>
                    {{$show->sod_qty_ord}}
                    <input type="hidden" name="sodqtyord[]" value="{{$show->sod_qty_ord}}" {{$show->sod_qty_ord <= $show->sod_qty_ship + $show->sod_qty_ongoing ? 'disabled':''}} />
                </td>
                <td>
                    {{$show->sod_qty_ongoing}}
                    <input type="hidden" name="sodqtyongoing[]" value="{{$show->sod_qty_ongoing}}" {{$show->sod_qty_ord <= $show->sod_qty_ship + $show->sod_qty_ongoing ? 'disabled':''}} />
                </td>
                <td>
                    {{$show->sod_qty_ship}}
                    <input type="hidden" name="sodqtyship[]" value="{{$show->sod_qty_ship}}" {{$show->sod_qty_ord <= $show->sod_qty_ship + $show->sod_qty_ongoing ? 'disabled':''}} />
                    <input type="hidden" name="sodpricels[]" value="{{$show->sod_price_ls}}" {{$show->sod_qty_ord <= $show->sod_qty_ship + $show->sod_qty_ongoing ? 'disabled':''}} />
                </td>
                <td><input type="number" name="qtyinput[]" class="form-control" min="0" max="{{$show->sod_qty_ord - $show->sod_qty_ship - $show->sod_qty_ongoing}}" step="0.01" value="0" required {{$show->sod_qty_ord <= $show->sod_qty_ship + $show->sod_qty_ongoing ? 'disabled':''}} /></td>
            </tr>
            @empty
            <td colspan='7' class='text-danger'><b>No Data Available</b></td>
            @endforelse
        </tbody>
    </table>
</div>
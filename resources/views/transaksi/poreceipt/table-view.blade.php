<div class="table-responsive col-lg-12 col-md-12 mb-4 tag-container" style="overflow-x: auto; overflow-y: hidden; display: block;white-space: nowrap;">
  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
      <tr>
          <th style="width: 5%;">Line</th>
          <th style="width: 30%;">Part</th>
          <th style="width: 10%;">Qty Order</th>
          <th style="width: 10%;">Qty Received</th>
          <th style="width: 25%;">Location</th>
          <th style="width: 10%;">Qty FG</th>
          <th style="width: 10%;">Qty Reject</th>
      </tr>
   </thead>
    <tbody>         
        @forelse ($po as $index => $show)
        <tr>
            <td>
              {{$show->pod_line}}
              <input type="hidden" name="poline[]" value="{{$show->pod_line}}" {{$show->pod_qty_ord <= $show->pod_qty_rcvd ? 'disabled':''}}/>
            </td>
            <td>{{$show->pod_part}} -- {{$show->pod_partdesc}}
            <input type="hidden" name="popart[]" value="{{$show->pod_part}}" {{$show->pod_qty_ord <= $show->pod_qty_rcvd ? 'disabled':''}}/>
            </td>
            <td>
              {{number_format($show->pod_qty_ord,2)}}
              <input type="hidden" name="poqtyord[]" value="{{$show->pod_qty_ord}}" {{$show->pod_qty_ord <= $show->pod_qty_rcvd ? 'disabled':''}}/>
            </td>
            <td>
              {{number_format($show->pod_qty_rcvd,2)}}
              <input type="hidden" name="poqtyrcvd[]" value="{{$show->pod_qty_rcvd}}" {{$show->pod_qty_ord <= $show->pod_qty_rcvd ? 'disabled':''}}/>
            </td>
            <td>
              <select name="partloc[]" class="form-control selectpicker" data-style="btn-custom" data-size='4' data-live-search="true" data-width="100%" {{$show->pod_qty_ord <= $show->pod_qty_rcvd ? 'disabled':''}}>
                  @foreach ($loc as $locs)
                      <option value="{{$locs->loc}}" {{$locs->loc == (($sessionpo!=null) ? $sessionpo[$index]->pod_loc : $show->pod_loc) ? 'Selected' : ''}} >{{$locs->loc}} -- {{$locs->loc_desc}}</option>
                  @endforeach
              </select>
            </td>
            <td><input type="number" class="form-control" name="qtyfg[]" min="0" step="0.01" value="{{($sessionpo!=null) ? $sessionpo[$index]->pod_qty_fg : 0}}" required  {{$show->pod_qty_ord <= $show->pod_qty_rcvd ? 'disabled':''}}/></td>
            <td><input type="number" class="form-control" name="qtyreject[]" min="0" step="0.01" value="{{($sessionpo!=null) ? $sessionpo[$index]->pod_qty_rjct : 0}}" required  {{$show->pod_qty_ord <= $show->pod_qty_rcvd ? 'disabled':''}}/></td>
        </tr>
        @empty
            <td colspan='7' class='text-danger'><b>No Data Available</b></td>
        @endforelse   
    </tbody>
  </table>
</div>
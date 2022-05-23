<div class="table-responsive col-lg-12 col-md-12 mb-4 tag-container" style="overflow-x: auto; overflow-y: hidden; display: inline-table;white-space: nowrap;">
  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
      <tr>
          <th style="width: 5%;">Line</th>
          <th style="width: 28%;">Item</th>
          <th style="width: 9%;">Qty Order</th>
          <th style="width: 9%;">Qty Received</th>
          <th style="width: 9%;">Qty Open</th>
          <th style="width: 10%;">Location</th>
          <th style="width: 10%;">Lot/Ser</th>
          <th style="width: 10%;">Qty Diterima</th>
          <th style="width: 10%;">Qty FG</th>
      </tr>
   </thead>
    <tbody>         
        @forelse ($receiptdetail as $index => $show)
        <tr>
            <td>
              {{$show->pod_line}}
              <input type="hidden" name="poline[]" value="{{$show->pod_line}}" {{$show->pod_qty_ord <= $show->pod_qty_rcvd ? 'disabled':''}}/>
            </td>
            <td>{{$show->pod_part}} -- {{$show->pod_partdesc}}
            <input type="hidden" name="popart[]" value="{{$show->pod_part}}" {{$show->pod_qty_ord <= $show->pod_qty_rcvd ? 'disabled':''}}/>
            <input type="hidden" name="popartdesc[]" value="{{$show->pod_partdesc}}" {{$show->pod_qty_ord <= $show->pod_qty_rcvd ? 'disabled':''}}/>
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
              {{number_format($show->pod_qty_ord - $show->pod_qty_rcvd,2)}}
            </td>
            <td>
              <input type="text" class="form-control" name="partloc[]" value="{{$show->pod_loc}}" {{$show->pod_qty_ord <= $show->pod_qty_rcvd ? 'disabled':'readonly'}}>
            </td>
            <td>
              <input type="text" class="form-control" name="partlot[]" value="{{($sessionpo!=null) ? $sessionpo[$index]->pod_lot : ''}}" {{$show->pod_qty_ord <= $show->pod_qty_rcvd ? 'disabled':''}}>
            </td>
            <td><input type="number" class="form-control" name="qtyterima[]" min="0" step="0.01" value="{{($sessionpo!=null) ? $sessionpo[$index]->pod_qty_terima : 0}}" required  {{$show->pod_qty_ord <= $show->pod_qty_rcvd ? 'disabled':''}}/></td>
            <td><input type="number" class="form-control" name="qtyfg[]" min="0" step="0.01" value="{{($sessionpo!=null) ? $sessionpo[$index]->pod_qty_fg : 0}}" required  {{$show->pod_qty_ord <= $show->pod_qty_rcvd ? 'disabled':''}}/></td>
        </tr>
        @empty
            <td colspan='7' class='text-danger'><b>No Data Available</b></td>
        @endforelse   
    </tbody>
  </table>
</div>
<div class="table-responsive col-lg-12 col-md-12 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
      <tr>
          <th style="width: 10%;">PO Nbr.</th>
          <th style="width: 10%;">Customer</th>
          <th style="width: 5%;">Line</th>
          <th style="width: 25%;">Part</th>
          <th style="width: 20%;">Qty Order</th>
          <th style="width: 20%;">Qty Received</th>
          <th style="width: 10%;">Qty Input</th>
      </tr>
   </thead>
    <tbody>         
        @forelse ($po as $index => $show)
        <tr>
            <td>
              {{$show->po_nbr}}
              <input type="hidden" name="ponbr[]" value="{{$show->po_nbr}}" {{$show->pod_qty_ord <= $show->pod_qty_rcvd ? 'disabled':''}} />  
            </td>
            <td>
              {{$show->po_cust}}
            </td>
            <td>
              {{$show->pod_line}}
              <input type="hidden" name="poline[]" value="{{$show->pod_line}}" {{$show->pod_qty_ord <= $show->pod_qty_rcvd ? 'disabled':''}}/>
            </td>
            <td>{{$show->pod_part}}
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
            <td><input type="number" name="qtyinput[]" min="0" step="0.01" value="0" required  {{$show->pod_qty_ord <= $show->pod_qty_rcvd ? 'disabled':''}}/></td>
        </tr>
        @empty
            <td colspan='7' class='text-danger'><b>No Data Available</b></td>
        @endforelse   
    </tbody>
  </table>
</div>
<div class="table-responsive col-lg-12 col-md-12 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
      <tr>
          <th>PO Nbr.</th>
          <th>Customer</th>
          <th>Line</th>
          <th>Part</th>
          <th>Qty Order</th>
          <th>Qty Received</th>
          <th>Qty Input</th>
      </tr>
   </thead>
    <tbody>         
        @forelse ($po as $index => $show)
        <tr>
            <td>
              {{$show->po_nbr}}
              
            </td>
            <td>{{$show->po_cust}}</td>
            <td>{{$show->pod_line}}</td>
            <td>{{$show->pod_part}}</td>
            <td>{{$show->pod_qty_ord}}</td>
            <td>{{$show->pod_qty_rcvd}}</td>
        </tr>
        @empty
            <td colspan='7' class='text-danger'><b>No Data Available</b></td>
        @endforelse   
    </tbody>
  </table>
</div>
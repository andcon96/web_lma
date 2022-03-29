<div class="table-responsive col-lg-12 col-md-12 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
      <tr>
          <th>PO Nbr.</th>
          <th>Invoice Nbr.</th>
          <th>Amount</th>
          <th>Status</th>
          <th>Email Status</th>
          <th style="text-align: center;">Send Email</th>
      </tr>
   </thead>
    <tbody>         
        @forelse ($poinvoice as $index => $show)
        <tr>
            <td>
              {{$show->po_nbr}}
              <input type="hidden" name="ponbr[]" value="{{$show->po_nbr}}"/>  
            </td>
            <td>
              {{$show->invoice_nbr}}
              <input type="hidden" name="invoice_nbr[]" value="{{$show->invoice_nbr}}"/>
            </td>
            <td>
              {{$show->invoice_amt}}
              <input type="hidden" name="invoice_amt[]" value="{{$show->invoice_amt}}"/>
            </td>
            <td>
              @if($show->invoice_status == 'true')
                Open
              @else
                Close
              @endif
            </td>
            <td>
              {{$show->email_status}}
            </td>
            <td style="text-align: center;"><input type="checkbox" name="sendmail[]" {{$show->invoice_status == 'true' ? '':'disabled'}}/></td>
        </tr>
        @empty
            <td colspan='7' class='text-danger'><b>No Data Available</b></td>
        @endforelse   
    </tbody>
  </table>
</div>
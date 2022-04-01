<div class="table-responsive col-lg-12 col-md-12 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
      <tr>
          <th style="width: 15%;">PO Nbr.</th>
          <th style="width: 25%;">Invoice Nbr.</th>
          <th style="width: 15%;">Amount</th>
          <th style="width: 10%;">Status</th>
          <th style="width: 15%;">Email Status</th>
          <th style="width: 10%; text-align: center;">Send Email</th>
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
              {{number_format($show->invoice_amt,2)}}
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
              <input type="hidden" value="R" name="hide_check[]" class="hide_check"/>
            </td>
            <td style="text-align: center;"><input class="sendmail" type="checkbox" name="sendmail[]" {{$show->invoice_status == 'true' ? '':'disabled'}}/></td>
        </tr>
        @empty
            <td colspan='7' class='text-danger'><b>No Data Available</b></td>
        @endforelse   
    </tbody>
  </table>
</div>
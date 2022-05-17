<div class="table-responsive col-lg-12 col-md-12 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th style="width: 7%;">PO No.</th>
        <th style="width: 15%;">Contract</th>
        <th style="width: 20%;">Supplier</th>
        <th style="width: 10%;">Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($polist as $index => $show)
      <tr>
        <td>
          {{$show->po_nbr}}
          <input type="hidden" name="ponbr[]" value="{{$show->po_nbr}}" />
        </td>
        <td>
          {{$show->po_contract}}
          <input type="hidden" name="supp[]" value="{{$show->supp}}" />
        </td>
        <td>
          {{$show->po_cust}} -- {{$show->po_custname}}
          <input type="hidden" name="invoice_nbr[]" value="{{$show->invoice_nbr}}" />
        </td>
        <td>
            <a href=""><i class="fas fa-eye"></i></a>
        </td>
        <td>
          @if($show->invoice_status == 'true')
          Open
          @else
          Close
          @endif
        </td>
        @php
        $approvalstatus = $statusappr->where('invcnbr')->first();
        @endphp
        <td>
          @if (is_null($approvalstatus))
            -
          @else
            @if ($approvalstatus->status == 'approved')
            Approved
            @elseif ($approvalstatus->status == 'rejected')
            Rejected
            @endif
          @endif

        </td>
        <td>
          {{$show->email_status}}
          <input type="hidden" value="R" name="hide_check[]" class="hide_check" />
        </td>
        <td style="text-align: center;"><input class="sendmail" type="checkbox" name="sendmail[]" {{$show->invoice_status == 'true' ? '':'disabled'}} /></td>
      </tr>
      @empty
      <td colspan='7' class='text-danger'><b>No Data Available</b></td>
      @endforelse
    </tbody>
  </table>
</div>
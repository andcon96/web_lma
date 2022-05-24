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
      @forelse ($po as $index => $show)
      <tr>
        <td>
          {{$show->po_nbr}}
          <input type="hidden" name="ponbr[]" value="{{$show->po_nbr}}" />
        </td>
        <td>
          {{$show->po_contract}}
        </td>
        <td>
          {{$show->po_cust}} -- {{$show->po_custname}}
        </td>
        <td>
            <a href="{{route('poreceipt.edit',$show->po_nbr) }}"><i class="fas fa-edit"></i></a>
        </td>
      </tr>
      <tr>
        <td colspan="4">
          <table class="table table-sm table-bordered" width="100%" style="font-size: 12px;">
            <tbody>
            @forelse ( $podetail as $showdetail )
              @if ( $showdetail->po_nbr == $show->po_nbr )
                <tr>
                  <td>{{ $showdetail->pod_line }}</td>
                  <td>{{ $showdetail->pod_part }} -- {{ $showdetail->pod_partdesc }}</td>
                </tr>
              @endif
            @empty
              <tr>
                <td>no item</td>
              </tr>
            @endforelse
            </tbody>
          </table>
        </td>
      </tr>
      @empty
      <td colspan='7' class='text-danger'><b>No Data Available</b></td>
      @endforelse
    </tbody>
  </table>
</div>
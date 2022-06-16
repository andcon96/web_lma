<div class="table-responsive col-lg-12 col-md-12 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th style="width: 15%;">SO No.</th>
        <th style="width: 15%;">PO No.</th>
        <th style="width: 15%;">Customer</th>
        <th style="width: 15%;">Due Date</th>
        <th style="width: 10%;">Action</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($so as $index => $show)
      <tr>
        <td>
          {{$show->so_nbr}}
          <input type="hidden" name="sonbr[]" value="{{$show->so_nbr}}" />
        </td>
        <td>
          {{$show->so_po}}
        </td>
        <td>
          {{$show->so_cust_name}}
        </td>
        <td>
          {{$show->so_duedate}}
        </td>
        <td>
          <a href="{{route('suratjalan.edit',$show->so_nbr) }}"><i class="fas fa-edit"></i></a>
        </td>
      </tr>
      <tr>
        <td width="10%" colspan="5">
          <table class="table table-sm table-bordered mb-0" style="font-size: 12px; table-layout: fixed;" cellspacing="0">
            <thead>
              <th>Line</th>
              <th>Item</th>
              <th>Qty Order</th>
            </thead>
            <tbody>
              @forelse ( $sodetail as $showdetail )
              @if ( $showdetail->so_nbr == $show->so_nbr )
              <tr>
                <td style="width: 5%;">{{ $showdetail->sod_line }}</td>
                <td style="width: 20%;">{{ $showdetail->sod_part }} -- {{ $showdetail->sod_part_desc }}</td>
                <td style="width: 10%;">{{ $showdetail->sod_qty_ord }}</td>
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
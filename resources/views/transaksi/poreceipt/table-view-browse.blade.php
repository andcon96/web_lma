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
            <a href="{{route('poreceipt.edit',$show->po_nbr) }}"><i class="fa fa-pencil"></i></a>
        </td>
      </tr>
      @empty
      <td colspan='7' class='text-danger'><b>No Data Available</b></td>
      @endforelse
    </tbody>
  </table>
</div>
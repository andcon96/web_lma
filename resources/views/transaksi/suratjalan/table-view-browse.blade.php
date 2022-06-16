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
      @empty
      <td colspan='7' class='text-danger'><b>No Data Available</b></td>
      @endforelse
    </tbody>
  </table>
</div>
<div class="table-responsive col-lg-12 col-md-12 tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th style="width: 7%;">Domain</th>
        <th style="width: 15%;">PO No.</th>
        <th style="width: 20%;">Invoice No.</th>
        <th style="width: 10%;">Sent Date</th>
      </tr>
    </thead>
    <tbody>
      @forelse ($datasentlist as $showlist)
      <tr>
        <td>
            {{$showlist->dom}}
        </td>
        <td>
            {{$showlist->eh_ponbr}}
        </td>
        <td>
            {{$show->eh_invcnbr}}
        </td>
        <td>
            {{$show->created_at}}
        </td>
      </tr>
      @empty
      <td colspan='7' class='text-danger'><b>No Data Available</b></td>
      @endforelse
    </tbody>
  </table>
</div>
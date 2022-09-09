<div class="table-responsive col-lg-12 col-md-12 mb-4 tag-container" style="overflow-x: auto; overflow-y: hidden; white-space: nowrap;">
  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
      <tr>
        <th style="width: 5%;">Line</th>
        <th style="width: 28%;">Item</th>
        <th style="width: 10%;">Location</th>
        <th style="width: 10%;">Gudang / Kapal</th>
        <th style="width: 9%;">Qty Unplanned</th>
      </tr>
    </thead>
    <tbody>
      <tr>
          <input type="hidden" name="idmaster" value="{{$detaildata->id}}">
        <td>
          {{$detaildata->line}}
          <input type="hidden" name="line" value="{{$detaildata->line}}"/>
        </td>
        <td>{{$detaildata->part}} -- {{$detaildata->partdesc}}
          <input type="hidden" name="part" value="{{$detaildata->part}}"  />
          <input type="hidden" name="partdesc" value="{{$detaildata->partdesc}}" />
        </td>
        <td>
          {{$detaildata->loc}}
          <input type="hidden" name="loc" value="{{$detaildata->loc}}" />
          <input type="hidden" name="site" value="{{$detaildata->site}}" />
        </td>
        <td>
            {{$detaildata->lot}}
          <input type="hidden" class="form-control" name="lot" value="{{ $detaildata->lot }}">
        </td>
        <td>
            {{$detaildata->qty_unplanned}}
            <input type="hidden" class="form-control" name="qtyunplanned" value="{{ $detaildata->qty_unplanned }}" />
        </td>
      </tr>
    </tbody>
  </table>
</div>
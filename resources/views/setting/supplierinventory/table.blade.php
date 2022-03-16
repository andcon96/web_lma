<div class="table-responsive tag-container" style="overflow-x: auto; display: block;white-space: nowrap;">
  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
      <tr>
         <th>Item Number</th>
         <th>Item Desc</th>
         <th>Supplier</th>
         <th width="7%">Delete</th>
      </tr>
   </thead>
    <tbody>         
        @forelse ($supplierInventories as $index => $show)
        <tr>
            <td >{{ $show->getItem->iim_item_part}}</td>
            <td >{{ $show->getItem->iim_item_desc }}</td>
            <td >{{ $show->getSupplier->supp_name }}</td>
            <td data-title="Delete" class="action">        
                <a href="" class="deletesupp" data-id="{{$show->id}}" data-line="{{$index + 1}}" data-toggle='modal' data-target="#deleteModal"><i class="fas fa-trash-alt"></i></a>
            </td>

        </tr>
        @empty
            <td colspan='4' class='text-danger'><center><b>No Data Available</b></center></td>
        @endforelse   
    </tbody>
  </table>
{!! $supplierInventories->render() !!}
</div>
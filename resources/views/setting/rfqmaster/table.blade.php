@forelse($items as $show)
<tr>
    <td>{{ $show->iim_item_part }}</td> 
    <td>{{ $show->iim_item_desc }}</td>
    <td>{{ $show->iim_item_um }}</td>
    <td>{{ $show->iim_item_safety_stk }}</td>
    <td>{{ $show->iim_item_type}}</td>
    <td>{{ $show->iim_item_prod_line}}</td>
    <td>{{ $show->iim_item_group}}</td>
    <td>{{ $show->iim_item_design}}</td>                                          
</tr>
@empty
<tr>
  <td class="text-danger" colspan='12'>
    <center><b>No Data Available</b></center>
  </td>
</tr>
@endforelse
<tr style="border:0 !important">
  <td colspan="12">
    {{ $items->links() }}
  </td>
</tr>             
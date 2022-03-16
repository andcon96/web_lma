@forelse($items as $show)
<tr>
    <td>{{ $show->iim_item_part }}</td>
    <td>{{ $show->iim_item_desc }}</td>
    <td>{{ $show->iim_item_um }}</td>
    <td>{{ $show->iim_item_safety_stk }}</td>
    <td>{{ $show->iim_item_type}}</td>
    <td>{{ $show->iim_item_prod_line}}</td>
    <td>{{ $show->iim_item_day1}} day</td>
    <td>{{ $show->iim_item_day2}} day</td>
    <td>{{ $show->iim_item_day3}} day</td>
    <td>{{ $show->iim_item_safety}} %</td>
    <!--   <td>
            <form action="/itmmstredt" method="get">
                  {{ csrf_field() }}  
                    <input disable type="hidden" name="part" value= {{ $show->iim_item_part }} >
                  <button class='editdata' type="submit" value="EDIT" ><i class="fas fa-edit"></i>
                </form>  
            </td>-->
</tr>
@empty
<tr>
    <td colspan='12' class="text-danger">
        <center><b>No Data Available</b></center>
    </td>
</tr>
@endforelse
<tr style="border:0 !important">
  <td colspan="12">
    {{ $items->links() }}
  </td>
</tr>             
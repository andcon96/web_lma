@forelse ($stock as $show)
  <tr>
    <!-- <td>{{ $show->item_site }}</td> -->
    <td>{{ $show->item_loc }}</td>
    <td>{{ $show->item_nbr }}</td>
    <td>{{ $show->item_desc }}</td>
    <td>{{ $show->item_um }}</td>
    <td>{{ number_format($show->item_qtyoh,2) }}</td>
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
    {{ $stock->withQueryString()->links() }}
  </td>
</tr>        
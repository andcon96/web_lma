@forelse ($cust as $show)
  <tr>
    <td>{{ $show->cust_dom }}</td>
    <td>{{ $show->cust_code }}</td>
    <td>{{ $show->cust_name }}</td>
    <td>{{ $show->cust_addr }}</td>
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
    {{ $cust->withQueryString()->links() }}
  </td>
</tr>        
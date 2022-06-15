@forelse ($supp as $show)
  <tr>
    <td>{{ $show->supp_dom }}</td>
    <td>{{ $show->supp_code }}</td>
    <td>{{ $show->supp_name }}</td>
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
    {{ $supp->withQueryString()->links() }}
  </td>
</tr>        
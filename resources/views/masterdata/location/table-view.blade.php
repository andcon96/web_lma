@forelse ($loc as $show)
  <tr>
    <td>{{ $show->loc_domain }}</td>
    <td>{{ $show->loc }}</td>
    <td>{{ $show->loc_desc }}</td>
    <td>{{ $show->loc_site }}</td>

    @if($show->loc_type == "")
      <td>-</td>
    @else
      <td>{{ $show->loc_type }}</td>
    @endif 
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
    {{ $loc->withQueryString()->links() }}
  </td>
</tr>        
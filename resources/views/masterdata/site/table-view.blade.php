@forelse ($site as $show)
  <tr>
    <td>{{ $show->site_domain }}</td>
    <td>{{ $show->site_entity }}</td>
    <td>{{ $show->site_site }}</td>

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
    {{ $site->withQueryString()->links() }}
  </td>
</tr>        
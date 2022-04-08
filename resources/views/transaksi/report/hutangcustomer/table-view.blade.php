@forelse ($hutangcust as $show)
  <tr>
    <!-- <td>{{ $show->item_site }}</td> -->
    <td>{{ $show->hutang_invcnbr }}</td>
    <td>{{ date('d-m-Y',strtotime($show->hutang_invcdate)) }}</td>
    <td>{{ $show->hutang_cust }}</td>
    <td>{{ number_format($show->hutang_amt,2) }}</td>
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
    {{ $hutangcust->withQueryString()->links() }}
  </td>
</tr>        
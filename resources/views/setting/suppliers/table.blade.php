@forelse ($suppliers as $show)
<tr>
  <td>{{ $show->supp_code }}</td>
  <td>{{ $show->supp_name }}</td>
  <td>
    @if ($show->supp_isActive == '1')
    Active
    @else
    Non-Active
    @endif
  </td>
  <td>
    @if ($show->supp_po_appr == '1')
    Yes
    @else
    No
    @endif
  </td>
  <td>
    <a href="" class="editUser" data-toggle="modal" data-target="#editModal" data-id="{{ $show->id }}"
      data-supp_code="{{ $show->supp_code }}" data-supp_name="{{ $show->supp_name }}"><i class="fas fa-edit"></i></a>
  </td>
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
    {{ $suppliers->links() }}
  </td>
</tr>

  <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
    <thead>
      <tr>
     <th width="15%">Supplier Code</th>
     <th width="45%">Supplier Name</th>  
     <th width="15%">Need Aproval</th>  
     <th width="15%">Type</th>
     <th width="10%">Edit</th>
  </tr>
   </thead>
    <tbody>         
        @foreach ($users as $show)
          <tr>
            <td>{{ $show->supp_code }}</td>
            <td>{{ $show->supp_name }}</td>
            <td>{{ $show->supp_po_appr == 1 ? 'Yes' : 'No' }}</td>
            <td>
              {{$show->getPOApprover->count() == 0 ? 'General' : 'Specific'}}
            </td>
            <td>
              <a href="" class="editUser" data-toggle="modal" data-target="#editModal" 
              data-idsupp="{{$show->id}}" data-suppname="{{$show->supp_name}}"
              data-suppcode="{{$show->supp_code}}" data-interval="{{$show->supp_intv}}">
              <i class='fas fa-edit'></i></a>
            </td>
          </tr>
        @endforeach                      
    </tbody>
  </table>
  {!! $users->render() !!}
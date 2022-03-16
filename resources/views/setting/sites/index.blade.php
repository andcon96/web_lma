@extends('layout.layout')

@section('menu_name','Site Maintenance')
@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
	<li class="breadcrumb-item"><a href="{{url('/')}}">Master</a></li>
	<li class="breadcrumb-item active">Site Maintenance</li>
</ol>
@endsection

@section('content')

<!-- Page Heading -->
<div class="col-lg-12">
	<button class="btn btn-info bt-action deleteUser" data-toggle="modal" data-target="#myModal">
		Create Site</button>
</div>

<div class="table-responsive col-lg-12 col-md-12 mt-3">
	<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
		<thead>
			<tr>
				<th>Domain</th>
				<th>Site</th>
				<th>Description</th>
				<th>Active</th>
				<th width="8%">Edit</th>
				<th width="8%">Delete</th>
			</tr>
		</thead>
		<tbody>
			@foreach ($sites as $show)
			<tr>
				<td>{{ $show->hasDomain->domain_code }}</td>
				<td>{{ $show->site_code }}</td>
				<td>{{ $show->site_desc }}</td>
				<td>{{ $show->site_isActive == 1 ? 'Yes' : 'No'}}</td>
				<td>
					<a href="" class="editModal" data-site="{{$show->site_code}}"
						data-site_id="{{$show->id}}"
						data-domain="{{$show->hasDomain->domain_code}}"
						data-desc="{{$show->site_desc}}" data-toggle='modal' data-act="{{$show->site_isActive}}"
						data-target="#editModal"><i class="fas fa-edit"></i></button>
				</td>
				<td>
					<a href="" class="deleteRole" data-site="{{$show->site_code}}"
						data-site_id="{{$show->id}}"
						data-domain="{{$show->hasDomain->domain_code}}"
						data-toggle='modal' data-target="#deleteModal"><i class="fas fa-trash-alt"></i></button>
				</td>
			</tr>
			@endforeach
		</tbody>
	</table>
</div>


<!--Create Modal-->
<div id="myModal" class="modal fade" role="dialog">
	<div class="modal-dialog">
		<!-- konten modal-->
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title text-center" id="exampleModalLabel">Create Site</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="panel-body">
				<!-- heading modal -->
				<form class="form-horizontal" role="form" method="POST" action="{{route('sitemaint.store')}}">
					{{ csrf_field() }}
					<div class="modal-body">
						<div class="form-group row">
							<label for="domain" class="col-md-3 col-form-label text-md-right">{{ __('Domain') }}</label>
							<div class="col-md-7">
								<select id="dom" class="form-control role" name="dom" required autofocus>
									<option value=''> Choose Domain </option>
									@foreach($domains as $domain)
									<option value='{{$domain->id}}'> {{$domain->domain_code}}</option>
									@endforeach
								</select>
								<!--<input id="domain" type="text" class="form-control" name="domain" value="">-->
							</div>
						</div>
						<div class="form-group row">
							<label for="site" class="col-md-3 col-form-label text-md-right">{{ __('Site') }}</label>
							<div class="col-md-7">
								<input id="site" type="text" class="form-control" name="site" value=""
									autocomplete="off">
							</div>
						</div>
						<div class="form-group row">
							<label for="desc" class="col-md-3 col-form-label text-md-right">{{ __('Description')
								}}</label>
							<div class="col-md-7">
								<input id="desc" type="textarea" class="form-control" name="desc" value=""
									autocomplete="off">
							</div>
						</div>
						<div class="form-group row">
							<label for="act" class="col-md-3 col-form-label text-md-right">{{ __('Active') }}</label>
							<div class="col-md-2">
								<input id="act" type="checkbox" class="form-control" name="act" value="true">
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-info bt-action" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-success bt-action">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!--Edit Modal-->
<div class="modal fade" id="editModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true">
	<div class="modal-dialog">
		<!-- konten modal-->
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title text-center" id="exampleModalLabel">Edit Data</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="panel-body">
				<!-- heading modal -->
				<form class="form-horizontal" role="form" method="POST" action="{{route('sitemaint.update', 'update')}}">
					@method('put')
					{{ csrf_field() }}
					<div class="modal-body">
						<input type="hidden" name="site_id" id="site_id">
						<div class="form-group row">
							<label for="e_domain" class="col-md-3 col-form-label text-md-right">{{ __('Domain')
								}}</label>
							<div class="col-md-7">
								<input id="e_domain" type="text" class="form-control" name="e_domain" readonly="true">
							</div>
						</div>

						<div class="form-group row">
							<label for="e_site" class="col-md-3 col-form-label text-md-right">{{ __('Site') }}</label>
							<div class="col-md-7">
								<input id="e_site" type="text" class="form-control" name="e_site" readonly="true">
							</div>
						</div>
						<div class="form-group row">
							<label for="e_desc" class="col-md-3 col-form-label text-md-right">{{ __('Description')
								}}</label>
							<div class="col-md-7">
								<input id="e_desc" type="textarea" class="form-control" name="e_desc">
							</div>
						</div>
						<div class="form-group row">
							<label for="e_act" class="col-md-3 col-form-label text-md-right">{{ __('Active') }}</label>
							<div class="col-md-2">
								<input id="e_act" type="checkbox" class="form-control" name="e_act" value="true">
							</div>
						</div>
					</div>

					<div class="modal-footer">
						<button type="button" class="btn btn-info bt-action" data-dismiss="modal">Cancel</button>
						<button type="submit" class="btn btn-success bt-action">Save</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
<!-- MODAL DELETE -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
	aria-hidden="true" data-backdrop="static">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title text-center" id="exampleModalLabel">Delete Data</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>

			<form action="{{route('sitemaint.destroy', 'delete')}}" method="post">
				@method('delete')
				{{ csrf_field() }}

				<div class="modal-body">
					<input type="hidden" name="d_site_id" id="d_site_id" value="">
					<input type="hidden" name="temp_site" id="temp_site" value="">

					<div class="container">
						<div class="row">
							Are you sure you want to delete site? <strong><a name="temp_dom" id="temp_dom"></a></strong>
						</div>
					</div>

				</div>

				<div class="modal-footer">
					<button type="button" class="btn btn-info bt-action" id="d_btnclose"
						data-dismiss="modal">Cancel</button>
					<button type="submit" class="btn btn-success bt-action" id="d_btnconf">Save</button>
					<button type="button" class="btn bt-action" id="d_btnloading" style="display:none">
						<i class="fa fa-circle-o-notch fa-spin"></i> &nbsp;Loading
					</button>
				</div>

			</form>
		</div>
	</div>
</div>


@endsection

@section('scripts')

<script type="text/javascript">
	$(document).on('click','.deleteRole',function(){ // Click to only happen on announce links
     
     //alert('tst');
	 var site_id = $(this).data('site_id');
     var site = $(this).data('site');
     document.getElementById("d_site_id").value = site_id;
     document.getElementById("temp_site").value = site;

     });

	$(document).on('click','.editModal',function(){ // Click to only happen on announce links
     
     //alert('tst');
     var domain_code = $(this).data('domain');
	 var site_id = $(this).data('site_id');
     var site = $(this).data('site');
     var desc = $(this).data('desc');
     var act = $(this).data('act');

     document.getElementById("e_domain").value = domain_code;
	 document.getElementById("site_id").value = site_id;
     document.getElementById("e_site").value = site;
     document.getElementById("e_desc").value = desc;
	 if(act == 1){ 
     document.getElementById("e_act").checked = true;
	 }
	 else
	 {document.getElementById("e_act").checked = false;
	 }
     });
</script>
@endsection
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Web LMA</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="{{url('assets/lte/fontawesome-free/css/all.min.css')}}">
  <!-- IonIcons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{url('assets/lte/adminlte.min.css')}}">

  <!--Old CSS-->

  <link rel="stylesheet" href="{{url('vendors/bootstrap/dist/css/bootstrap.min.css')}}">

  <link rel="stylesheet" href="{{url('assets/css/bootstrap-select.min.css')}}">
  <link rel="stylesheet" href="{{url('assets/css/select2.min.css')}}">
  <!-- <link rel="stylesheet" href="{{url('assets/css/style.css')}}"> -->
  <link rel="stylesheet" href="{{url('assets/css/tablestyle.css')}}">
  <link rel="stylesheet" href="{{url('assets/css/checkbox.css')}}">
  <link rel="stylesheet" href="{{url('assets/css/jquery-ui.css')}}">

</head>
<!--
`body` tag options:

  Apply one or more of the following classes to to the body tag
  to get the desired effect

  * sidebar-collapse
  * sidebar-mini
-->

<body class="hold-transition sidebar-mini sidebar-collapse">
  <div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
      <!-- Left navbar links -->
      <ul class="navbar-nav">
        <li class="nav-item">
          <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
      </ul>

      <!-- Right navbar links -->
      <ul class="navbar-nav ml-auto">

        <!-- Select Domain -->
        <li>
          <select name="headdomain" id="headdomain" class="form-control">
            @php
              $domain = DB::table('domains')->get();
              foreach($domain as $domains){
                if($domains->domain_code == Session::get('domain')){
                  echo '<option value="'.$domains->domain_code.'" Selected >'.$domains->domain_code.'</option>';
                }else{
                  echo '<option value="'.$domains->domain_code.'" >'.$domains->domain_code.'</option>';
                }
              }
            @endphp
          </select>
        </li>

        <li>
          <a class="nav-link" role="button" data-toggle="dropdown">
            <i class="fas fa-user mr-2"></i>
            Hello, {{Session::get('name')}}
          </a>
          <div class="dropdown-menu dropdown=menu-lg dropdown-menu-right">
            <a href="#" class="dropdown-item" data-toggle="modal" data-target="#logoutModal">
              <i class="fas fa-power-off mr-2"></i> Logout
            </a>
          </div>
        </li>
      </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
      <!-- Brand Logo -->
      <a href="{{url('/')}}" class="brand-link">
        <img src="{{url('images/imi.png')}}" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">IMI Modules</span>
      </a>

      <!-- Sidebar -->
      <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
            
            <li class="nav-item">
              <a href="{{url('/home')}}" class="nav-link">
                <i class="nav-icon fas fa-home"></i>
                <p>
                  Home
                </p>
              </a>
            </li>
           

            @can('access_transactions')
            <li class="nav-header">TRANSAKSI</li>

              @can('access_po_head')
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-money-check"></i>
                  <p>
                    Purchase Order
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @can('po_receipt')
                  <li class="nav-item">
                    <a href="{{route('poreceipt.index')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Purchase Order Receipt</p>
                    </a>
                  </li>
                  @endcan
                  @can('po_approval')
                  <li class="nav-item">
                    <a href="{{route('poapproval.index')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Purchase Order Invoice Approval</p>
                    </a>
                  </li>
                  @endcan
                </ul>
              </li>
              @endcan
              

              @can('access_sj_head')
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-shopping-bag"></i>
                  <p>
                    Surat Jalan
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @can('sj_create')
                  <li class="nav-item">
                    <a href="{{route('suratjalan.index')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Create Surat Jalan</p>
                    </a>
                  </li>
                  @endcan

                  @can('sj_browse')
                  <li class="nav-item">
                    <a href="{{route('browseSJ')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Browse Surat Jalan</p>
                    </a>
                  </li>
                  @endcan

                  @can('sj_confirm')
                  <li class="nav-item">
                    <a href="{{route('sjconfirm.index')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Confirm Surat Jalan</p>
                    </a>
                  </li>
                  @endcan
                </ul>
              </li>
              @endcan


              @can('access_report_head')
              <li class="nav-item">
                <a href="#" class="nav-link">
                  <i class="nav-icon fas fa-book"></i>
                  <p>
                    Report
                    <i class="fas fa-angle-left right"></i>
                  </p>
                </a>
                <ul class="nav nav-treeview">
                  @can('stock_item')
                  <li class="nav-item">
                    <a href="{{route('stockitm.index')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Stock Item</p>
                    </a>
                  </li>
                  @endcan

                  @can('hutang_cust')
                  <li class="nav-item">
                    <a href="{{route('hutangcust.index')}}" class="nav-link">
                      <i class="far fa-circle nav-icon"></i>
                      <p>Hutang Customer</p>
                    </a>
                  </li>
                  @endcan
                </ul>
              </li>
              @endcan
            
            @endcan

            @can('access_masters')
            <li class="nav-header">MASTER</li>
            <li class="nav-item">
              <a href="#" class="nav-link">
                <i class="nav-icon fas fa-wrench"></i>
                <p>
                  Setting
                  <i class="fas fa-angle-left right"></i>
                </p>
              </a>
              <ul class="nav nav-treeview">
                <li class="nav-item">
                  <a href="{{route('usermaint.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>User Maintenance</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('rolemaint.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Role Maintenance</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('accessrolemenu.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Role Menu Maintenance</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{url('qxwsa')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>WSA Qxtend Maintenance</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{url('prefixmaint')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Prefix Maintenance</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('poinvcemail.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>PO Invoice Email Control</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('domainmstr.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Domain Master</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('custmstr.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Customer Master</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('locmstr.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Location Master</p>
                  </a>
                </li>
                <li class="nav-item">
                  <a href="{{route('sitemstr.index')}}" class="nav-link">
                    <i class="far fa-circle nav-icon"></i>
                    <p>Site Master</p>
                  </a>
                </li>

              </ul>
            </li>
            @endcan


          </ul>
        </nav>
        <!-- /.sidebar-menu -->
      </div>
      <!-- /.sidebar -->
    </aside>

    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
      <!-- Content Header (Page header) -->
      <div class="content-header">
        <div class="container-fluid">
          <div class="row mb-2">
            <div class="col-sm-6">
            </div>
            <div class="col-sm-6">
              @yield('breadcrumbs')
            </div>
          </div>
        </div>
      </div>
      <!-- /.content-header -->

      <!-- Main content -->
      <div class="content">
        <div class="container-fluid">
          @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade show" id="getError" role="alert">
            {{ session()->get('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          @endif

          @if(session()->has('updated'))
          <div class="alert alert-success  alert-dismissible fade show" role="alert">
            {{ session()->get('updated') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          @endif

          @yield('content')

          <div id="loader" class="lds-dual-ring hidden overlay"></div>
        </div>
        <!-- /.container-fluid -->
      </div>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <!-- Control Sidebar -->
    <aside class="control-sidebar control-sidebar-dark">
      <!-- Control sidebar content goes here -->
    </aside>
    <!-- /.control-sidebar -->

  </div>
  <!-- ./wrapper -->


  <!-- Logout Modal-->
  <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
          <button class="close" type="button" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
          </button>
        </div>
        <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
        <div class="modal-footer">
          <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
          <a class="btn btn-primary" href="{{ route('logout') }}" onclick="event.preventDefault();
                                          document.getElementById('logout-form').submit();">
            {{ __('Logout') }} </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
            @csrf
          </form>
        </div>
      </div>
    </div>
  </div>

  <!-- REQUIRED SCRIPTS -->

  <!-- jQuery -->
  <script src="{{url('assets/css/jquery-3.2.1.min.js')}}"></script>
  <script src="{{url('assets/css/jquery-ui.js')}}"></script>
  <!--Date Picker-->
  <script src="{{url('vendors/popper.js/dist/umd/popper.min.js')}}"></script>
  <!-- Bootstrap -->
  <script src="{{url('vendors/bootstrap/dist/js/bootstrap.min.js')}}"></script>

  <script src="{{url('assets/js/bootstrap-select.min.js')}}"></script>
  <!-- AdminLTE -->
  <script src="{{url('assets/lte/adminlte.js')}}"></script>

  <script src="{{url('assets/css/select2.min.js')}}"></script>
  <script src="//cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  <!--Sweet Alert-->
  @include('sweetalert::alert')
  <!-- Chart JS -->
  <script src="{{ url('vendors/chart.js/dist/Chart.min.js') }}"></script>
  <!-- Barcode Scanner -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>

  @yield('scripts')



  @if(session('errors'))
  <script type="text/javascript">
    var newerror = [];

    <?php
    foreach ($errors->all() as $err) {
      echo "newerror.push('" . $err . "');";
    }
    ?>
    var countnewerror = newerror.length;
    var newtext = '';
    for (var i = 0; i < countnewerror; i++) {

      newtext += '<li>' + newerror[i] + '</li>';
    }
    Swal.fire({
      icon: 'error',
      html: newtext,
      showCloseButton: true,
      showCancelButton: true,
      focusConfirm: false,
    })
  </script>
  @endif

  <script type="text/javascript">
    $(document).ready(function() {
      if (window.innerWidth <= 576) {
        document.querySelector('body').classList.remove('open');
      } else {
        document.querySelector('body').classList.add('open');
      }


      window.addEventListener("resize", myFunction);

      function myFunction() {
        if (window.innerWidth <= 576) {
          document.querySelector('body').classList.remove('open');
        } else {
          document.querySelector('body').classList.add('open');
        }
      }
    });

    /** add active class and stay opened when selected */
    var url = window.location.href;

    if(url.includes("?")){
      url = url.split('?')[0];
    }else if (url.split("/").length > 3) {
      url = url.split("/");
      url = url[0] + "/" + url[1] + "/" + url[2] + "/" + url[3];
      console.log(url);  
    }
    // 

    // for sidebar menu entirely but not cover treeview
    $('ul.nav-sidebar a').filter(function() {
      return this.href == url;
    }).addClass('active');

    // for treeview
    $('ul.nav-treeview a').filter(function() {
      return this.href == url;
    }).parentsUntil(".nav-sidebar > .nav-treeview").prev('a').addClass('active');


    // Notification
    function sendMarkRequest(id = null) {
      return $.ajax("{{ route('notifread') }}", {
        method: 'POST',
        data: {
          "_token": "{{csrf_token()}}",
          "id": id
        }
      });
    }

    function sendMarkAllRequest(id = null) {
      return $.ajax("{{ route('notifreadall') }}", {
        method: 'POST',
        data: {
          "_token": "{{csrf_token()}}",
          "id": id
        }
      });
    }

    $(function() {
      $('.mark-as-read').click(function() {
        let request = sendMarkRequest($(this).data('id'));
        request.done(() => {
          $(this).parents('div.alert').remove();
        });
      });

      $('.mark-as-read-all').click(function() {
        let request = sendMarkAllRequest($(this).data('id'));
        request.done(() => {
          $(this).parents('div.alert').remove();
          window.location.reload();
        });
      });
    });

    $('#headdomain').on('change',function($e){
      let domain = $(this).val();
      
      $.ajax("{{ route('changeDomain') }}", {
        method: 'Get',
        data: {
          "domain": domain
        },beforeSend: function(data){
						$('#loader').removeClass('hidden');
        },
        success: function(data) {
          location.reload();
        }
      });
    });
  </script>

</body>

</html>
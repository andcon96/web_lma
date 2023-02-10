@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Detail Lokasi Item {{$id}}</li>
</ol>
@endsection


@section('content')

<div class="row">
    <div class="col-md-4">
        <div class="input-group mb-3">
            <input type="text" class="form-control" placeholder="Cari data..." aria-label="Cari data..." onkeyup="searchFunction()"  id="searchInput">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        @include('transaksi.viewitem.show-table')
    </div>
</div>


@endsection

@section('scripts')
<script>
    function searchFunction() {
        var input, table, tr, td, i, txtValue;
        input = document.getElementById("searchInput");
        table = document.getElementById("dataTable");
        tr = table.getElementsByTagName("tr");
        for (i = 0; i < tr.length; i++) {
            if (i == 0) {
                tr[i].style.display = "";
                continue;
            }
            td = tr[i].getElementsByTagName("td");
            var flag = false;
            for (j = 0; j < td.length; j++) {
                txtValue = td[j].textContent || td[j].innerText;
                if (txtValue.toUpperCase().indexOf(input.value.toUpperCase()) > -1) {
                    flag = true;
                    break;
                }
            }
            if (flag) {
                tr[i].style.display = "";
            } else {
                tr[i].style.display = "none";
            }
        }
    }
</script>
@endsection
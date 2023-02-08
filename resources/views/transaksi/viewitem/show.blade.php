@extends('layout.layout')

@section('breadcrumbs')
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{url('/')}}">Transaksi</a></li>
    <li class="breadcrumb-item active">Detail Lokasi Item {{$id}}</li>
</ol>
@endsection


@section('content')

@include('transaksi.viewitem.show-table')

@endsection


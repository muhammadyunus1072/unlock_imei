@extends('app.layouts.panel')

@section('title', 'Laporan - Transaksi')

@section('header')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <!--begin::Title-->
        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Laporan - Transaksi</h1>
        <!--end::Title-->
        <!--begin::Breadcrumb-->
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">Laporan - Transaksi</li>
            {{-- <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li> --}}
        </ul>
        <!--end::Breadcrumb-->

    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <livewire:report.transaction-report.filter lazy>
        </div>
        <div class="card-body">
            <livewire:report.transaction-report.datatable-header lazy>
            <livewire:report.transaction-report.datatable lazy>
        </div>
    </div>
@stop

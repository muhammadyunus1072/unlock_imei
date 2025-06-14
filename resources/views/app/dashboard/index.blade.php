@extends('app.layouts.panel')

@section('title', 'Dashboard')

@section('header')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <!--begin::Title-->
        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Dashboard</h1>
        <!--end::Title-->
        <!--begin::Breadcrumb-->
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">Dashboard</li>
            {{-- <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li> --}}
        </ul>
        <!--end::Breadcrumb-->
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {{-- <h1 class="panel-configure__title">Ringkasan Transaksi</h1>
            <livewire:dashboard.summary>

            <h1 class="panel-configure__title mt-5">Ringkasan Keuangan Harian</h1>
            <livewire:dashboard.daily-summary>

            <h1 class="panel-configure__title mt-5">Ringkasan Keuangan Mingguan</h1>
            <livewire:dashboard.weekly-summary>

            <h1 class="panel-configure__title mt-5">Ringkasan Keuangan Bulanan</h1>
            <livewire:dashboard.monthly-summary>

            <h1 class="panel-configure__title mt-5">Ringkasan Keuangan Tahunan</h1>
            <livewire:dashboard.yearly-summary> --}}
        </div>
    </div>
@stop

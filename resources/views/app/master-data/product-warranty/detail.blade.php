@extends('app.layouts.panel')

@section('title', 'Produk - Garansi')

@section('header')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <!--begin::Title-->
        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Produk - Garansi</h1>
        <!--end::Title-->
        <!--begin::Breadcrumb-->
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">Garansi</li>
            {{-- <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li> --}}
        </ul>
        <!--end::Breadcrumb-->

        <div class='row'>
            <div class="col-md-auto mt-2">
                <a class="btn btn-info" href="{{ route('product_warranty.index') }}">
                    <i class="ki-duotone ki-arrow-left fs-1">
                        <span class="path1"></span>
                        <span class="path2"></span>
                    </i>
                    Kembali
                </a>
            </div>
        </div>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-body">
            {{-- <livewire:master-data.product-warranty.detail :objId="$objId" /> --}}
            <livewire:master-data.product-warranty.detail :objId="$objId">
        </div>
    </div>
@stop

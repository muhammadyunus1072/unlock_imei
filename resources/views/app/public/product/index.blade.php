@extends('app.layouts.public')

@section('title', 'Home')

@section('header')
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <!--begin::Title-->
        <h1 class="page-heading d-flex text-dark fw-bold fs-3 flex-column justify-content-center my-0">Home</h1>
        <!--end::Title-->
        <!--begin::Breadcrumb-->
        <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
            <li class="breadcrumb-item text-muted">Home</li>
            {{-- <li class="breadcrumb-item">
                <span class="bullet bg-gray-400 w-5px h-2px"></span>
            </li> --}}
        </ul>
        <!--end::Breadcrumb-->
    </div>
@stop

@section('content')
    <!--begin::Heading-->
    <div class="text-center mb-4">
        <div class="fs-5 text-muted fw-bold">Capture your every moments with</div>
        <h3 class="fs-2hx text-dark">{{ config('template.title') }}</h3>
    </div>
    <!--end::Heading-->
    {{-- <livewire:public.product.filter>      --}}
    <livewire:public.product.data>     
@stop

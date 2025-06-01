<!DOCTYPE html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', env('APP_NAME'))</title>

    <!--begin::Fonts(mandatory for all pages)-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
    <!--end::Fonts-->

    <!--begin::Vendor Stylesheets(used for this page only)-->
    <link href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet"
        type="text/css" />
    <!--end::Vendor Stylesheets-->

    <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->

    @livewireStyles

    @stack('css')
</head>
<!--end::Head-->

<!--begin::Body-->

<body id="kt_app_body" data-kt-app-header-fixed="true"
    data-kt-app-sidebar-enabled="false"
    data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
    data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
    <script>
        var defaultThemeMode = "light";
        var themeMode;
        if (document.documentElement) {
            if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
                themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
            } else {
                if (localStorage.getItem("data-bs-theme") !== null) {
                    themeMode = localStorage.getItem("data-bs-theme");
                } else {
                    themeMode = defaultThemeMode;
                }
            }
            if (themeMode === "system") {
                themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
            }
            document.documentElement.setAttribute("data-bs-theme", themeMode);
        }
    </script>
    

    <!--begin::App-->
    <div class="d-flex flex-column flex-root" id="kt_app_root">
        <div class="mb-20" id="home">
           <div class="bgi-no-repeat bgi-size-contain bgi-position-x-center bgi-position-y-bottom landing-light-bg">
              <div class="landing-header" id="navbar" data-kt-sticky="true" data-kt-sticky-name="landing-header" data-kt-sticky-offset="{default: '200px', lg: '300px'}">
                 <div class="container">
                    <div class="d-flex align-items-center justify-content-between">
                       <div class="d-flex align-items-center flex-equal">
                          <button class="btn btn-icon btn-active-color-primary me-3 d-flex d-lg-none" id="kt_landing_menu_toggle">
                             <span class="svg-icon svg-icon-2hx">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                   <path d="M21 7H3C2.4 7 2 6.6 2 6V4C2 3.4 2.4 3 3 3H21C21.6 3 22 3.4 22 4V6C22 6.6 21.6 7 21 7Z" fill="currentColor"></path>
                                   <path opacity="0.3" d="M21 14H3C2.4 14 2 13.6 2 13V11C2 10.4 2.4 10 3 10H21C21.6 10 22 10.4 22 11V13C22 13.6 21.6 14 21 14ZM22 20V18C22 17.4 21.6 17 21 17H3C2.4 17 2 17.4 2 18V20C2 20.6 2.4 21 3 21H21C21.6 21 22 20.6 22 20Z" fill="currentColor"></path>
                                </svg>
                             </span>
                          </button>
                          <a href="{{ route('public.index') }}">
                          <img alt="Logo" src="{{ asset(config('template.logo_panel')) }}" class="logo-default h-35px h-lg-45px">
                          <img alt="Logo" src="{{ asset(config('template.logo_panel')) }}" class="logo-sticky h-35px h-lg-45px">
                          </a>
                       </div>
                       <div class="d-lg-block" id="kt_header_nav_wrapper">
                          <div class="d-lg-block p-5 p-lg-0" data-kt-drawer="true" data-kt-drawer-name="landing-menu" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="200px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_landing_menu_toggle" data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav_wrapper'}" style="">
                             <div class="menu menu-column flex-nowrap menu-rounded menu-lg-row menu-title-gray-500 menu-state-title-primary nav nav-flush fs-5 fw-semibold" id="kt_landing_menu">
                                <!-- <div class="menu-item">
                                   <a class="menu-link nav-link py-3 px-4 px-xxl-6" href="#">Home</a>
                                   </div> -->
                                <!-- <div class="menu-item">
                                   <a target="_blank" class="menu-link nav-link py-3 px-4 px-xxl-6" href="https://kuystudio.id/about">About Us</a>
                                   </div> -->
                                <div class="menu-item">
                                   <a class="nav-link py-3 px-4 px-xxl-6 btn btn-light-primary w-100" href="{{ route('public.index') }}">PILIH GARANSI</a>
                                </div>
                                <div class="menu-item">
                                   <a class="nav-link py-3 px-4 px-xxl-6 btn btn-light-primary w-100" href="{{ route('public.order-check') }}">CARI ORDER</a>
                                </div>
                             </div>
                          </div>
                       </div>
                    </div>
                 </div>
              </div>
              <style>
                 .select2-results__option {
                 display: flex;
                 justify-content: space-between;
                 align-items: center;
                 }
                 .select2-results__option span {
                 margin-right: 10px;
                 }
                 .recommended-badge {
                 display: flex;
                 /* Menggunakan flexbox */
                 align-items: center;
                 /* Menyelaraskan secara vertikal */
                 gap: 5px;
                 /* Memberi jarak antara teks dan ikon */
                 }
                 .recommended-badge svg {
                 display: inline-block;
                 }
              </style>
              <div class="mb-n10 mb-lg-n20 z-index-2 mt-10 pb-10">
                  <!--begin::Container-->
                  <div class="container"> 
                     @yield('content')
                  </div>
              </div>
              <style>
                 #modal-upsell .modal-dialog {
                 display: flex;
                 align-items: start;
                 justify-content: center;
                 max-width: 80vw;
                 /* Pastikan modal cukup besar */
                 width: 80vw;
                 }
                 #modal-upsell .modal-content {
                 aspect-ratio: 16 / 9;
                 /* Pastikan modal selalu dalam rasio 16:9 */
                 }
                 #modal-upsell .carousel-item img {
                 object-fit: cover;
                 /* Pastikan gambar tidak terdistorsi */
                 width: 100%;
                 height: 100%;
                 }
                 #modal-upsell .modal-content-upsell {
                 background-color: transparent;
                 /* Hapus border */
                 border: none;
                 /* Hapus box-shadow: ; */
                 box-shadow: none;
                 }
                 @media (max-width: 575px) {
                 #modal-upsell .modal-dialog {
                 max-width: 90vw;
                 /* Supaya modal tetap responsif di layar kecil */
                 width: 90vw;
                 margin: auto;
                 /* Pastikan modal selalu di tengah */
                 display: flex;
                 align-items: start;
                 justify-content: center;
                 height: 100vh;
                 /* Paksa modal untuk mengambil seluruh tinggi viewport */
                 }
                 /* #modal-upsell .modal-dialog {
                 max-width: 100vw;
                 width: 100vw;
                 } */
                 }
              </style>
              <!-- Ukuran 16:9 -->
              <div class="modal fade" id="modal-upsell" tabindex="-2" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="modal-upsell-Label" aria-hidden="true">
                 <div class="modal-dialog modal-dialog-centered d-flex justify-content-center">
                    <div class="modal-content modal-content-upsell">
                       <div class="modal-body" id="modal-body-upsell">
                          <div class="text-end mb-5">
                             <button type="button" id="btn-close-modal-upsell" class="btn btn-warning">X</button>
                          </div>
                          <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                          </div>
                       </div>
                    </div>
                 </div>
              </div>
              <!-- Modal -->
              <div class="modal fade" id="modal-branch" tabindex="-1" data-bs-keyboard="false" data-bs-backdrop="static" aria-labelledby="modal-branch-Label" aria-hidden="true" style="display: none;">
                 <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                       <div class="modal-header">
                          <h5 class="modal-title" id="modal-branch-Label">Pilih Kota</h5>
                       </div>
                       <div class="modal-body">
                          <div class="fv-row mb-5 ">
                             <label class="form-label">Jenis Paket</label>
                             <select class="form-select form-select-solid select2-hidden-accessible" name="jenis_sesi" id="jenis_sesi" data-kt-select2="true" data-placeholder="Jenis Sesi" data-allow-clear="false" data-dropdown-parent="#modal-branch" data-select2-id="select2-data-jenis_sesi" tabindex="-1" aria-hidden="true" data-kt-initialized="1">
                                <option value="Background" selected="" data-select2-id="select2-data-2-an69">Basic</option>
                                <option value="Pasfoto">Pasfoto</option>
                                <option value="Foto visa">Foto visa</option>
                                <option value="Foto ID card">Foto ID card</option>
                                <option value="Landscape">Landscape</option>
                             </select>
                             <span class="select2 select2-container select2-container--bootstrap5" dir="ltr" data-select2-id="select2-data-1-8qp2" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single form-select form-select-solid" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-jenis_sesi-container" aria-controls="select2-jenis_sesi-container"><span class="select2-selection__rendered" id="select2-jenis_sesi-container" role="textbox" aria-readonly="true" title="Basic">Basic</span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                          </div>
                          <div class="fv-row mb-5 ">
                             <label class="form-label">Pilih Kota</label>
                             <select class="form-select form-select-solid select2-hidden-accessible" name="city_id" id="city_id" data-kt-select2="true" data-placeholder="Pilih Kota" data-allow-clear="false" data-dropdown-parent="#modal-branch" data-select2-id="select2-data-city_id" tabindex="-1" aria-hidden="true" data-kt-initialized="1">
                                <option value="3171">
                                   Kota Jakarta Selatan                            
                                </option>
                                <option value="3514">
                                   Kabupaten Pasuruan                            
                                </option>
                                <option value="3571">
                                   Kota Kediri                            
                                </option>
                                <option value="3573">
                                   Kota Malang                            
                                </option>
                                <option value="3578">
                                   Kota Surabaya                            
                                </option>
                                <option value="5103">
                                   Kabupaten Badung                            
                                </option>
                             </select>
                             <span class="select2 select2-container select2-container--bootstrap5" dir="ltr" data-select2-id="select2-data-3-yy72" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single form-select form-select-solid" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-city_id-container" aria-controls="select2-city_id-container"><span class="select2-selection__rendered" id="select2-city_id-container" role="textbox" aria-readonly="true" title="Pilih Kota"><span class="select2-selection__placeholder">Pilih Kota</span></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                          </div>
                          <div class="fv-row mb-5 ">
                             <label class="form-label">Pilih Studio</label>
                             <select class="form-select form-select-solid select2-hidden-accessible" name="branch_id" id="branch_id" data-kt-select2="true" data-placeholder="Pilih Studio" data-allow-clear="false" data-dropdown-parent="#modal-branch" data-select2-id="select2-data-branch_id" tabindex="-1" aria-hidden="true" data-kt-initialized="1">
                                <option value="c99e61c6-0dd9-4cc3-81ec-b0e09eeb6949" data-unit="1" city-id="3573">
                                   Kuy Studio 1.0 - Oro Oro Dowo                            
                                </option>
                                <option value="d96b9e3d-bf97-4d9c-9f40-88486814251c" data-unit="7" city-id="3573">
                                   Kuy Studio 2.0 - Suhat                            
                                </option>
                                <option value="51afc14a-4f39-4170-ba88-a7fbaa54c83a" data-unit="8" city-id="3578">
                                   Kuy Studio 3.0 - Klampis                            
                                </option>
                                <option value="6c746f5a-6db7-4bd7-9cfd-a5b807328ece" data-unit="9" city-id="3571">
                                   Kuy Studio 4.0 - Kilisuci                            
                                </option>
                                <option value="81ef6515-872d-47df-b7b1-ab5827ba5a38" data-unit="10" city-id="3171">
                                   Kuy Studio 5.0 - Jagakarsa                            
                                </option>
                                <option value="7e769bd9-0849-4e5d-a507-9ceee0688bf9" data-unit="11" city-id="3514">
                                   Kuy Studio 6.0 - Taman Dayu                            
                                </option>
                                <option value="7e769bd9-0849-4e5d-aB47-9ceee0688bf9" data-unit="12" city-id="5103">
                                   Kuy Studio 7.0 - Badung                            
                                </option>
                             </select>
                             <span class="select2 select2-container select2-container--bootstrap5" dir="ltr" data-select2-id="select2-data-4-jgo6" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single form-select form-select-solid" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-branch_id-container" aria-controls="select2-branch_id-container"><span class="select2-selection__rendered" id="select2-branch_id-container" role="textbox" aria-readonly="true" title="Pilih Studio"><span class="select2-selection__placeholder">Pilih Studio</span></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                          </div>
                          <div class="fv-row mb-5">
                             <label class="form-label" id="label-pilih-background">Pilih Background</label>
                             <select class="form-select form-select-solid select2-hidden-accessible" name="background_color" id="background_color" data-kt-select2="true" data-placeholder="Pilihan" data-allow-clear="false" data-dropdown-parent="#modal-branch" tabindex="-1" aria-hidden="true" data-kt-initialized="1" data-select2-id="select2-data-background_color">
                                <option value="Beige" data-background="Beige" data-unit-ids="1,7,8,9,10,11,12" data-product-ids="1,4,5,10,16,18,23" data-city-ids="3171,3514,3571,3573,3578,5103" data-jenis-sesi="Background" data-recommended="1" data-new="0">
                                   BEIGE                            
                                </option>
                                <option value="Grey" data-background="Grey" data-unit-ids="1,7,8,9,10,11,12" data-product-ids="1,4,5,6,7,10,12,15,16,18,21,23" data-city-ids="3171,3514,3571,3573,3578,5103" data-jenis-sesi="Background" data-recommended="1" data-new="0">
                                   GREY                            
                                </option>
                                <option value="White" data-background="White" data-unit-ids="1,7,8,9,10,11,12" data-product-ids="1,4,5,10,15,16,18,19,21,23,25" data-city-ids="3171,3514,3571,3573,3578,5103" data-jenis-sesi="Background" data-recommended="1" data-new="0">
                                   WHITE                            
                                </option>
                                <option value="80s Year Book" data-background="80s Year Book" data-unit-ids="1,7,8,9,11" data-product-ids="1,4,10,16,21" data-city-ids="3514,3571,3573,3578" data-jenis-sesi="Background" data-recommended="0" data-new="0">
                                   80S YEAR BOOK                            
                                </option>
                                <option value="90s Year Book" data-background="90s Year Book" data-unit-ids="1,7,8,12" data-product-ids="5,7,15,23" data-city-ids="3573,3578,5103" data-jenis-sesi="Background" data-recommended="0" data-new="0">
                                   90S YEAR BOOK                            
                                </option>
                                <option value="Baby Blue" data-background="Baby Blue" data-unit-ids="1,7,8,9,10,11,12" data-product-ids="4,5,10,15,16,18,23" data-city-ids="3171,3514,3571,3573,3578,5103" data-jenis-sesi="Background" data-recommended="0" data-new="0">
                                   BABY BLUE                            
                                </option>
                                <option value="Black" data-background="Black" data-unit-ids="1,7,8,9,10,11,12" data-product-ids="1,4,5,7,10,16,18,21,23" data-city-ids="3171,3514,3571,3573,3578,5103" data-jenis-sesi="Background" data-recommended="0" data-new="0">
                                   BLACK                            
                                </option>
                                <option value="Blue" data-background="Blue" data-unit-ids="1,7,8" data-product-ids="1,7,21" data-city-ids="3573,3578" data-jenis-sesi="Background" data-recommended="0" data-new="0">
                                   BLUE                            
                                </option>
                                <option value="Brown" data-background="Brown" data-unit-ids="8,9,10,11,12" data-product-ids="6,12,17,19,25" data-city-ids="3171,3514,3571,3578,5103" data-jenis-sesi="Background" data-recommended="0" data-new="0">
                                   BROWN                            
                                </option>
                                <option value="Graduation Memories" data-background="Graduation Memories" data-unit-ids="1" data-product-ids="4" data-city-ids="3573" data-jenis-sesi="Background" data-recommended="0" data-new="0">
                                   GRADUATION MEMORIES                            
                                </option>
                                <option value="Green" data-background="Green" data-unit-ids="8" data-product-ids="21" data-city-ids="3578" data-jenis-sesi="Background" data-recommended="0" data-new="0">
                                   GREEN                            
                                </option>
                                <option value="Green Leaf" data-background="Green Leaf" data-unit-ids="7" data-product-ids="15" data-city-ids="3573" data-jenis-sesi="Background" data-recommended="0" data-new="0">
                                   GREEN LEAF                            
                                </option>
                                <option value="Grey Curtain" data-background="Grey Curtain" data-unit-ids="1" data-product-ids="4" data-city-ids="3573" data-jenis-sesi="Background" data-recommended="0" data-new="0">
                                   GREY CURTAIN                            
                                </option>
                                <option value="Pink Pastel" data-background="Pink Pastel" data-unit-ids="1,8,9,10,11,12" data-product-ids="4,5,10,16,18,23" data-city-ids="3171,3514,3571,3573,3578,5103" data-jenis-sesi="Background" data-recommended="0" data-new="0">
                                   PINK PASTEL                            
                                </option>
                                <option value="Red" data-background="Red" data-unit-ids="1,7,8" data-product-ids="1,7,21" data-city-ids="3573,3578" data-jenis-sesi="Background" data-recommended="0" data-new="0">
                                   RED                            
                                </option>
                                <option value="Spotlight Abstract" data-background="Spotlight Abstract" data-unit-ids="1,10,11,12" data-product-ids="7,16,18,23" data-city-ids="3171,3514,3573,5103" data-jenis-sesi="Background" data-recommended="0" data-new="0">
                                   SPOTLIGHT ABSTRACT                            
                                </option>
                                <option value="Spotlight Black" data-background="Spotlight Black" data-unit-ids="1,10,11,12" data-product-ids="7,16,18,23" data-city-ids="3171,3514,3573,5103" data-jenis-sesi="Background" data-recommended="0" data-new="0">
                                   SPOTLIGHT BLACK                            
                                </option>
                                <option value="Spotlight RGB" data-background="Spotlight RGB" data-unit-ids="8" data-product-ids="5" data-city-ids="3578" data-jenis-sesi="Background" data-recommended="0" data-new="0">
                                   SPOTLIGHT RGB                            
                                </option>
                                <option value="Spotlight White" data-background="Spotlight White" data-unit-ids="10,11,12" data-product-ids="16,18,23" data-city-ids="3171,3514,5103" data-jenis-sesi="Background" data-recommended="0" data-new="0">
                                   SPOTLIGHT WHITE                            
                                </option>
                             </select>
                             <span class="select2 select2-container select2-container--bootstrap5" dir="ltr" data-select2-id="select2-data-25-0eyd" style="width: 100%;"><span class="selection"><span class="select2-selection select2-selection--single form-select form-select-solid" role="combobox" aria-haspopup="true" aria-expanded="false" tabindex="0" aria-disabled="false" aria-labelledby="select2-background_color-container" aria-controls="select2-background_color-container"><span class="select2-selection__rendered" id="select2-background_color-container" role="textbox" aria-readonly="true" title="Pilihan"><span class="select2-selection__placeholder">Pilihan</span></span><span class="select2-selection__arrow" role="presentation"><b role="presentation"></b></span></span></span><span class="dropdown-wrapper" aria-hidden="true"></span></span>
                          </div>
                          <div class="text-end">
                             <button id="btn-studios" type="button" class="btn btn-primary">Lihat Studio</button>
                             <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          </div>
                       </div>
                    </div>
                 </div>
              </div>
           </div>
        </div>
        <div class="mt-auto" id="footer">
           <div class="landing-dark-bg">
              <div class="landing-dark-separator"></div>
              <div class="container">
             <div class="d-flex flex-column flex-md-row flex-stack py-7 py-lg-10">
                <div class="d-flex align-items-center order-2 order-md-1">
                   <a href="{{ route('public.index')}}">
                   <img alt="Logo" src="{{ asset('files/images/logo_long_2.png') }}" class="h-25px h-md-35px">
                   </a>
                   <span class="mx-5 fs-6 fw-semibold text-white pt-1" href="https://dev.kuystudio.id">© 2025 {{ config('template.title')}}.</span>
                </div>
                <ul class="menu menu-gray-600 menu-hover-primary fw-semibold fs-6 fs-md-5 order-1 mb-5 mb-md-0">
                   <li class="menu-item">
                  <a href="https://www.instagram.com/kuystudio" target="_blank" class="menu-link text-white">Follow us on Instagram <i class="px-2 font-size-20px fa-brands fa-instagram text-white"></i></a>
                   </li>
                </ul>
             </div>
              </div>
           </div>
        </div>
        <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
           <span class="svg-icon">
              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                 <rect opacity="0.5" x="13" y="6" width="13" height="2" rx="1" transform="rotate(90 13 6)" fill="currentColor"></rect>
                 <path d="M12.5657 8.56569L16.75 12.75C17.1642 13.1642 17.8358 13.1642 18.25 12.75C18.6642 12.3358 18.6642 11.6642 18.25 11.25L12.7071 5.70711C12.3166 5.31658 11.6834 5.31658 11.2929 5.70711L5.75 11.25C5.33579 11.6642 5.33579 12.3358 5.75 12.75C6.16421 13.1642 6.83579 13.1642 7.25 12.75L11.4343 8.56569C11.7467 8.25327 12.2533 8.25327 12.5657 8.56569Z" fill="currentColor"></path>
              </svg>
           </span>
        </div>
     </div>
    <!--end::App-->


    <!--begin::Javascript-->
    <!--begin::Global Javascript Bundle(mandatory for all pages)-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>

    @livewireScripts
    <script>
        Livewire.on("{{ Alert::EVENT_INFO }}", (event) => {
            Swal.fire({
                icon: event[0],
                title: event[1],
                html: event[2],
            }).then((result) => {
               if(event[3])
               {
                  Livewire.dispatch(event[3]);
               }
            });;
        });

        Livewire.on("{{ Alert::EVENT_CONSOLE_LOG }}", (event) => {
            console.log(event[0])
        });

        Livewire.on("{{ Alert::EVENT_CONFIRMATION }}", (event) => {
            Swal.fire({
                icon: event[0],
                title: event[1],
                html: event[2],
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: event[3],
                cancelButtonText: event[4],
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch(event[5]);
                } else {
                    Livewire.dispatch(event[6]);
                }
            });
        });

        Livewire.on('refresh-page', (data) => {
            location.reload();
        });

        Livewire.on('consoleLog', (data) => {
            console.log(data)
        });
    </script>
    @stack('js')
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>

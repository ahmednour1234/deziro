<meta charset="utf-8" />
<meta
  name="viewport"
  content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0"
/>

<meta name="csrf-token" content="{{ csrf_token() }}">


<title>Deziro</title>

<meta name="description" content="" />

<!-- Favicon -->
<link rel="icon" type="image/x-icon" href="{{ asset('admin/assets/img/favicon/favicon.ico') }}" />

<!-- Fonts -->
<link rel="preconnect" href="{{ asset('https://fonts.googleapis.com') }}" />
<link rel="preconnect" href="{{ asset('https://fonts.gstatic.com') }}" crossorigin />
{{-- <link
  href="https://fonts.googleapis.com/css2?family=Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap"
  rel="stylesheet"
/> --}}

<!-- Icons. Uncomment required icon fonts -->
<link rel="stylesheet" href="{{ asset('admin/assets/vendor/fonts/boxicons.css') }}" />

<!-- Core CSS -->
<link rel="stylesheet" href="{{ asset('admin/assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
<link rel="stylesheet" href="{{ asset('admin/assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
<link rel="stylesheet" href="{{ asset('admin/assets/css/demo.css') }}" />

<!-- Vendors CSS -->
<link rel="stylesheet" href="{{ asset('admin/assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
  <!-- Helpers -->

  <script src="{{ asset('admin/assets/vendor/js/helpers.js') }}"></script>

  <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->
  <!--? Config:  Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file.  -->
  <script src="{{ asset('admin/assets/js/config.js') }}"></script>

  {{-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css"> --}}
<link rel="stylesheet" href="{{ asset('admin/assets/vendor/libs/apex-charts/apex-charts.css') }}" />
<link rel="stylesheet" href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/paginationjs/2.1.5/pagination.css') }}">


{{-- <link rel="stylesheet" href="{{ asset('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css') }}"> --}}
<link rel="stylesheet" href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.4.0/min/dropzone.min.css') }}">

{{-- <link rel="stylesheet" href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/css/bootstrap.min.css') }}"> --}}
<link rel="stylesheet" href="{{ asset('https://cdn.datatables.net/1.13.1/css/dataTables.bootstrap5.min.css') }}">

<link rel="stylesheet" href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css') }}" integrity="sha512-MV7K8+y+gLIBoVD59lQIYicR65iaqukzvf/nwasF0nqhPay5w/9lJmVM2hMDcnK1OnMGCdVK+iQrJ7lzPJQd1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />


{{-- select2 --}}
<link rel="stylesheet" href="{{ asset('//cdnjs.cloudflare.com/ajax/libs/select2/4.0.0/css/select2.min.css') }}">

<link rel="stylesheet" href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/select2-bootstrap-5-theme/1.3.0/select2-bootstrap-5-theme.min.css') }}" integrity="sha512-z/90a5SWiu4MWVelb5+ny7sAayYUfMmdXKEAbpj27PfdkamNdyI3hcjxPxkOPbrXoKIm7r9V2mElt5f1OtVhqA==" crossorigin="anonymous" referrerpolicy="no-referrer" />


<link href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.min.css') }}" rel="stylesheet"/>

<link rel="stylesheet" href="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css') }}">
<style>
    .hide {
        display: none;
    }

    .options_table table {
        width: 100%;
    }

    .options_table tr,
    .options_table th,
    .options_table td {
        border: none;
        padding: 5px;
    }

    .options_table th {
        background: #f5f5f9;
        padding: 10px 5px;
    }

    #options_list .actions i {
        cursor: pointer;
    }

    .section-header {
        background: #f5f5f9;
        padding: 15px 10px;
        border-radius: 0.375rem;
    }

    .card section {
        margin-bottom: 25px;
    }

    .control-group .text-danger {
        font-size: small;
    }

    .swatch-picker input {
        -webkit-appearance: none;
        border-radius: 10px;
        border: none;
        height: 38px;
        width: 100%;
        padding: 0.4375rem;
        font-size: 0.9375rem;
        font-weight: 400;
        line-height: 1.53;
        cursor: pointer;
        border-radius: 0.375rem;
        -moz-appearance: none;
        -webkit-appearance: none;
        appearance: none;
    }

    .swatch-picker input::-webkit-color-swatch {
        border: none;
        border-radius: 0.375rem;
        padding: 0;
    }

    .swatch-picker input::-webkit-color-swatch-wrapper {
        border: none;
        border-radius: 0.375rem;
        padding: 0;
    }

    .modal-body {
        position: relative;
        flex: 1 1 auto;
        padding: 1rem 1.5rem;
    }

    .section-body {
        padding: 0 15px;
    }

    #modal-overlay {
        position: absolute;
        display: none;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgb(134 134 134 / 30%);
        z-index: 2;
        cursor: pointer;
        border-radius: 0.5rem;
    }

    .flex-center {
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .nav-pagination {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .form-control.has-error {
        border: 1px solid red;
    }

    .has-error::placeholder {
        color: #ff000094;
        opacity: 1;
        /* Firefox */
    }

    .has-error:-ms-input-placeholder {
        /* Internet Explorer 10-11 */
        color: #ff000094;
    }

    .has-error::-ms-input-placeholder {
        /* Microsoft Edge */
        color: #ff000094;
    }

    .card-header {
        padding: 1rem;
        margin-bottom: 0;
        background-color: transparent;
        border-bottom: 0 solid #d9dee3;
    }

    div.dataTables_wrapper div.dataTables_filter {
        padding-right: 1rem;
    }

    .dataTables_length {
        padding-left: 1rem;
    }

    div.dataTables_wrapper div.dataTables_info {
        padding-top: .85em;
        padding-left: 1rem;
        padding-bottom: 1rem;
    }

    div.dataTables_wrapper div.dataTables_paginate {
        padding-right: 1rem;
        padding-bottom: 1rem;
    }

    .page-item.first .page-link,
    .page-item.last .page-link,
    .page-item.next .page-link,
    .page-item.prev .page-link,
    .page-item.previous .page-link {
        padding: 10px;
    }
</style>

<style>
    .select2-container--classic .select2-selection--single {
        display: block;
        width: 100%;
        padding: 0.4375rem 1.875rem 0.4375rem 0.875rem;
        min-height: 38px;
        -moz-padding-start: calc(0.875rem - 3px);
        font-size: 0.9375rem;
        font-weight: 400;
        line-height: 1.53;
        color: #697a8d;
        background-color: #fff;
        /* background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='rgba%2867, 89, 113, 0.6%29' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e"); */
        background-repeat: no-repeat;
        background-position: right 0.875rem center;
        background-size: 17px 12px;
        border: 1px solid #d9dee3;
        border-radius: 0.375rem;
        transition: border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
    }

    .select2-container--classic .select2-selection--single .select2-selection__rendered {
        color: #444;
        line-height: 22px;
    }

    .select2-container--classic .select2-selection--single .select2-selection__arrow {
        background-color: white;
        border: none;
        /* border-left: 1px solid #aaa; */
        border-top-right-radius: 4px;
        border-bottom-right-radius: 4px;
        height: 36px;
        position: absolute;
        top: 1px;
        right: 11px;
        width: 20px;
        background-image: none;
    }

    .select2-container--classic.select2-container--open.select2-container--below .select2-selection--single {
        border-bottom: none;
        border-bottom-left-radius: 0;
        border-bottom-right-radius: 0;
        background-image: none;
    }

    .select2-container--classic .select2-search--dropdown .select2-search__field {
        border: 1px solid #dedede;
        outline: 0;
        border-radius: 4px;
        margin-bottom: 5px;
    }

    .select2-results__option .select2-results__option--selectable .select2-results__option--selected {
        color: #969696;
    }

    .select2-container .select2-selection--single .select2-selection__rendered {
        display: block;
        padding-left: 0px;
        padding-right: 20px;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
<style>
    .layout-navbar-fixed body:not(.modal-open) .layout-content-navbar .layout-menu,
    .layout-menu-fixed body:not(.modal-open) .layout-content-navbar .layout-menu,
    .layout-menu-fixed-offcanvas body:not(.modal-open) .layout-content-navbar .layout-menu {
        z-index: 1050;
    }

    .layout-navbar-fixed body:not(.modal-open) .layout-content-navbar .layout-navbar,
    .layout-menu-fixed body:not(.modal-open) .layout-content-navbar .layout-navbar,
    .layout-menu-fixed-offcanvas body:not(.modal-open) .layout-content-navbar .layout-navbar {
        z-index: 1050;
    }
</style>

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>cloudtenant</title>

        {{-- @include('layouts.styles') --}}
        <!-- Favicon -->
<link rel="icon" href="{{ asset('assets/img/logo.png') }}" type="image/png">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/bootstrap1.min.css') }}" />

<!-- themefy CSS -->


<!-- text editor css -->
<link rel="stylesheet" href="{{ asset('assets/vendors/text_editor/summernote-bs4.css') }}" />

<!-- morris css -->

<!-- material icon css -->

<!-- menu css  -->
{{-- <link rel="stylesheet" href="{{ asset('assets/css/metisMenu.css') }}" /> --}}

<!-- style CSS -->
<link rel="stylesheet" href="{{ asset('assets/css/style1.css') }}" />
{{-- <link rel="stylesheet" href="{{ asset('assets/css/colors/default.css') }}" id="colorSkinCSS"> --}}

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css"/>

 <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    </head>
    <body class="crm_body_bg">
            {{-- @include('layouts.navigation') --}}

            <!-- Page Heading -->
            @yield('contents')
    </body>
    {{-- @include('layouts.scripts') --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    @stack('scripts')

</html>

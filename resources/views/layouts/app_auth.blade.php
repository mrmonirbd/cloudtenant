<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>cloudtenant</title>

        @include('layouts.styles')

        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    </head>
    <body class="crm_body_bg">
            @include('partials.sidebar')
        <section class="main_content dashboard_part">

            @include('partials.topbar')
                <div class="main_content_iner ">
                    <div class="container-fluid p-0 sm_padding_15px">
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <div class="dashboard_header mb_50">
                                    <div class="row">
                                        <div class="col-lg-6">
                                            <div class="dashboard_header_title">
                                                <h3> Directory Dashboard</h3>
                                            </div>
                                        </div>
                                        <div class="col-lg-6">
                                            <div class="dashboard_breadcam text-end">
                                                <p><a href="{{ route('dashboard') }}">Dashboard</a> <i class="fas fa-caret-right"></i>   Address Book</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            @yield('contents')
                        </div>
                    </div>
                </div>
            @include('partials.footer')

        </section>
    </body>
    @include('layouts.scripts')

    @stack('scripts')

</html>

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
                @yield('contents')
            </div>
            @include('partials.footer')

        </section>
    </body>
    @include('layouts.scripts')

    @stack('scripts')

</html>

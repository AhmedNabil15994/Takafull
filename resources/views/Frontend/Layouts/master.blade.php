<!DOCTYPE html>
<html lang="en" dir="{{DIRECTION}}">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta charset="utf-8">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="{{ $data->desc }}">
        <meta name="keywords" content="{{ $data->meta }}">
        <title>{{ $data->title }} - @yield('title')</title>
        @include('Frontend.Layouts.head')
        @include('Partials.notf_messages')
    </head>
    <body>
        @include('Frontend.Partials.mobileMenu')    
        <div class="transformPage">
            @include('Frontend.Layouts.header')
            @yield('content')
            @include('Frontend.Layouts.footer')
        </div>
        @yield('modals')
        @include('Frontend.Layouts.scripts')
    </body>
</html>
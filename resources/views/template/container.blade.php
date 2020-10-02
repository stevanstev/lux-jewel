<html>
    <head>
        <title>Lux Jewelry | @yield('title')</title>
        @include('template/header')
    </head>
    <body>
        <div id="preloder">
            <div class="loader"></div>
        </div>

        @include('template/navigation')

        @yield('section')
    </body>

    @include('template/script')

    @if($show == "true")
        @include('template/footer')
    @endif
</html>
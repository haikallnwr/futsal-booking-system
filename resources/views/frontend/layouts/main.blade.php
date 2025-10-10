@include('frontend.partials.meta')

<body>
    @include('frontend.partials.navbar')
    @yield('container')
    @include('frontend.partials.footer')
    @stack('js_scripts')
        
    </body>
@include('frontend.partials.meta')
@include('auth.partials.navbar')
@yield('container')
@if (!View::hasSection('hide_footer'))
    @include('frontend.partials.footer')
@endif

@stack('js_scripts')


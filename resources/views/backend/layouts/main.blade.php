@include('backend.partials.meta')

<body class="theme-dark" style="overflow-y: auto;">
    <div id="app">
        @include('backend.partials.sidebar')
        <div id="main">
            <header class="mb-3">
                <a href="#" class="burger-btn d-block d-xl-none">
                    <i class="bi bi-justify fs-3"></i>
                </a>
            </header>
            @yield('container')
        </div>
    </div>
<script src="{{ asset('assets/extensions/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/extensions/summernote/summernote-lite.min.js') }}"></script>
<script src="{{ asset('assets/js/pages/summernote.js') }}"></script>
<script src="{{ asset('assets/js/bootstrap.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
 @yield('js') 

 @stack('scripts')
</body>
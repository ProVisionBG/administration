@include('administration::partials.head')
<body class="hold-transition login-page error-layout">

<div class="d-flex flex-column w-100 h-100">

    <div class="d-flex flex-column h-100 justify-content-center">
        @yield('content')
    </div>

    @include('administration::partials.footer')
</div>

</body>
</html>

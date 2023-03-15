<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Hello Home</title>
    <!-- Styles -->
    <link rel="stylesheet" href="{!! asset('ecommerce/css/app.css'); !!}">

    <!-- Font icons -->
    <link rel="stylesheet" href="{!! asset('ecommerce/fontello-us/css/fontello.css'); !!}">
    @stack('styles')
    <!-- Scripts -->
    <script>
        window.Laravel = {!! json_encode([
            'csrfToken' => csrf_token(),
        ]) !!};
    </script>
</head>

<body>
   @include('ecommerce::layouts.header')

    @yield('content')

    @include('ecommerce::layouts.footer')
</body>
<script src="{!! asset('ecommerce/js/app.js'); !!}"></script>
@stack('scripts')
</html>
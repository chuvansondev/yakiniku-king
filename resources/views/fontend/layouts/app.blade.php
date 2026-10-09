<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        @yield('title', localized_setting('site_name', 'Yakiniku King'))
    </title>

    <meta name="description"
          content="@yield('description', localized_setting('footer_description'))">

    @vite(['resources/scss/frontend.scss', 'resources/scss/components.scss'])

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    @stack('styles')

</head>

<body>

    @include('fontend.partials.header')

    <main>
        @yield('content')
    </main>

    @include('fontend.partials.footer')


    <script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
    </script>

    @stack('scripts')

</body>

</html>

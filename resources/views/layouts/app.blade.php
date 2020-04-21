<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>
        {{ config('app.name', 'Constructo') }}
    </title>
    <link rel="icon" type="image/ico" href="/uploads/corousel/titlebaricon.ico"/>

    <!-- Scripts -->

    <script src="{{ asset('js/app.js') }}" defer></script>


    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDbD4QUpy5uC_TJnv37DiALWuJ-OaO1dAM&libraries=places"></script>
    <script src="https://cdn.jsdelivr.net/npm/map-icons@3.0.3/dist/js/map-icons.min.js"> </script>
    <script src="https://cdn.jsdelivr.net/npm/map-icons@3.0.3/dist/js/map-icons.js"> </script>
    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">

</head>
<body style="background-color: #f5f5f5;">
    <div id="app">

            @yield('content')

    </div>
    <script src="{{ asset('js/app.js') }}" defer></script>


</body>
</html>

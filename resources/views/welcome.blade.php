<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>BC</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

    <!-- Styles -->
    <style>
        html,
        body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 200;
            height: 100vh;
            margin: 0;
        }


        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }


        .content {
            text-align: center;
        }

        .title {
            font-size: 84px;
        }

        .links>.inner-link>a {
            color: #636b6f;
            padding: 0 25px;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: .1rem;
            text-decoration: none;
            text-transform: uppercase;
        }

        .m-b-md {
            margin-bottom: 30px;
        }

        .top-right {
            background: blue !important;
            overflow: auto;
            width: 100%;
            margin: 0px;
            padding: 15px 0px;
            display: flex;
            align-items: center;
        }

        .top-right .inner-link a {
            color: white;
        }

        body {
            margin: 0px;
            padding: 0px;

        }
        img{
            margin-left: 10rem;
            height: 50px;
            width: 40px;
        }
    </style>
</head>

<body
    style="background-image: url('{{asset('frontview.jpeg')}}');background-repeat: no-repeat, repeat;background-size: cover; ">
    <div class="navbar">
        @if (Route::has('login'))
        <div class="top-right links">
        <img src="{{asset('logo.jpeg')}}" />
            <div class="inner-link">
            @auth
            <a href="{{ url('/home') }}">Home</a>
            @else
            <a href="{{ route('login') }}">Login</a>

            @if (Route::has('register'))
            <a href="{{ route('register') }}">Register</a>
            @endif
            @endauth
            </div>
        </div>
        @endif

    </div>
</body>

</html>
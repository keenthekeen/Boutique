<!doctype html>
<html>
<head>
    <title>@yield('code') @yield('title') - {{ config('app.name') }}</title>
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="theme-color" content="#616161"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-alpha.4/css/materialize.min.css" integrity="sha256-M1RAYWK/tnlEgevvMLr8tbW9WpzWS8earbGXlxgiBaI=" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet"/>
    <style>
        body {
            font-family: 'Kanit', sans-serif;
            display: flex;
            min-height: 100vh;
            flex-direction: column;
            background-color: #616161;
        }

        main {
            flex: 1 0 auto;
        }

        .page-footer a:hover {
            text-decoration: underline;
        }

        .brand-logo {
            font-size: 1.7rem !important;
            font-weight: 600;
        }

        h1 {
            font-size: 70px
        }

        h2 {
            font-size: 30px;
            margin-bottom: 15px
        }

        h3 {
            font-size: 25px
        }

        main {
            text-align: center
        }

        .footer-copyright a {
            color: lightgrey;
        }

        .footer-copyright a:hover {
            text-decoration: underline
        }
    </style>
</head>
<body>
<nav>
    <div class="nav-wrapper grey darken-2">
        <div class="container">
            <a href="/" class="brand-logo"><span class="pink-text text-accent-2">TUOPH</span> Shop</a>
            <ul id="nav-mobile" class="right hide-on-med-and-down">
                <li><a href="/">หน้าหลัก</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="white-text grey darken-2" style="height:30px"></div>

<main class="container">
    <div class="red darken-2 white-text" style="padding: 20px;margin-bottom:20px; padding-top:50px; padding-bottom:50px;">
        @section('content')
            <h1 class="center-align light" id="title">@yield('title')</h1>
            <h2 class="center-align light th">@yield('description')</h2>
        @show
    </div>
    @section('button')
        <a href="/" class="waves-effect waves-light btn blue darken-2 tooltipped center-align th" data-tooltip="กลับสู่หน้าหลัก" style="width:80%;max-width:350px;margin-top:20px">กลับไปยังหน้าหลัก</a>
    @show
</main>

<footer class="page-footer grey darken-2">
    <div class="footer-copyright">
        <div class="container">
            ร้านจำหน่ายของที่ระลึก งานนิทรรศการวิชาการ โรงเรียนเตรียมอุดมศึกษา
        </div>
    </div>
</footer>
@yield('script')
<script async src="https://www.googletagmanager.com/gtag/js?id=UA-88470919-8"></script>
<script>
    window.dataLayer = window.dataLayer || [];
    function gtag(){dataLayer.push(arguments);}
    gtag('js', new Date());

    gtag('config', 'UA-88470919-8');
</script>
</body>
</html>

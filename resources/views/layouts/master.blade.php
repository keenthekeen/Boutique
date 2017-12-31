<!doctype html>
<html>
<head>
    @if (config('app.env') == 'staging')
        <title>UNDER CONSTRUCTION - TUCMC</title>
        <meta name="robots" content="noindex, nofollow"/>
    @else
    @section('title')
        <title>TUOPH Shop</title>
    @show
    <meta name="keyword" content="TU Open House Triamudom นิทรรศการ เตรียมอุดม"/>
    @endif
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="theme-color" content="#616161"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-alpha.2/css/materialize.min.css" integrity="sha256-8gLlCqPk1HxrExaXv1sMh46mbQSYvcu11zsAANydnhU="
          crossorigin="anonymous"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet"/>
    <link href="/css/app.css" rel="stylesheet"/>
    @yield('style')
</head>
<body>

<nav>
    <div class="nav-wrapper grey darken-2">
        <div class="container">
            <a href="/" class="brand-logo">
                @if (config('app.env') == 'staging')
                    <b class="red-text">UNDER CONSTRUCTION</b>
                @else
                    <span class="pink-text text-accent-2">TUOPH</span> Shop
                @endif
            </a>
            <ul class="right hide-on-med-and-down">
                @if (Auth::check())
                    @if (Cart::count() > 0)
                        <li><a href="/cart"><i class="material-icons left">shopping_cart</i> ตะกร้า</a></li>
                    @endif
                    <li><a href="/logout">ออกจากระบบ</a></li>
                @else
                    <li><a href="/login">เข้าสู่ระบบ</a></li>
                @endif
            </ul>
            <ul class="sidenav" id="nav-mobile">
                @if (Auth::check())
                    @if (Cart::count() > 0)
                        <li><a href="/cart"><i class="material-icons">shopping_cart</i> ตะกร้า</a></li>
                    @endif
                    <li><a href="/logout">ออกจากระบบ</a></li>
                @else
                    <li><a href="/login">เข้าสู่ระบบ</a></li>
                @endif
            </ul>
            <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
        </div>
    </div>
</nav>

@yield('beforemain')

<main class="container">
    @yield('main')
</main>

<footer class="page-footer grey darken-2">
    <div class="footer-copyright">
        <div class="container">
            @if (config('app.env') == 'staging')
                Under Construction --- <b class="red-text">DO NOT PUBLISH</b>
            @else
                ร้านจำหน่ายของที่ระลึก งานนิทรรศการวิชาการ โรงเรียนเตรียมอุดมศึกษา
            @endif
            @if (Auth::check())
                | เข้าสู่ระบบโดย {{ Auth::user()->name }}
            @endif
        </div>
    </div>
</footer>

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-alpha.2/js/materialize.min.js" integrity="sha256-4u/8c/K9iSYgglS0o0++LZ82P5V5tll6wyz02zryNxc="
            crossorigin="anonymous"></script>
    <script>
        var navElement = document.querySelector('.sidenav');
        var sideNav = new M.Sidenav(navElement, {});
        @if (session()->has('notify'))
        M.toast({html: '{!! session('notify') !!}'});
        @endif
    </script>
@show
</body>
</html>

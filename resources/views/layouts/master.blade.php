<!doctype html>
<html>
<head>
    @if (config('app.env') == 'staging')
        <title>Internal Use - TUCMC</title>
        <meta name="robots" content="noindex, nofollow"/>
    @else
    @section('title')
        <title>{{ config('app.name') }}</title>
    @show
    <meta name="keyword" content="TU Open House Triamudom นิทรรศการ เตรียมอุดม"/>
    @endif
    <meta charset="UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <meta name="theme-color" content="#616161"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-alpha.4/css/materialize.min.css" integrity="sha256-M1RAYWK/tnlEgevvMLr8tbW9WpzWS8earbGXlxgiBaI="
          crossorigin="anonymous"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css?family=Kanit" rel="stylesheet"/>
    <link href="/css/app.css" rel="stylesheet"/>
    <link rel="manifest" href="/manifest.json"/>
    @yield('style')
</head>
<body>

<nav>
    <div class="nav-wrapper grey darken-2">
        <div class="container">
            <a href="/" class="brand-logo">
                @if (config('app.env') == 'staging')
                    <b class="red-text">TUCMC Internal</b>
                @else
                    <span class="pink-text text-accent-2">TUOPH</span> Shop
                @endif
            </a>

            <ul class="right hide-on-med-and-down">
                <li><a href="/">หน้าแรก</a></li>
                @if (Auth::check())
                    @if (Cart::count() > 0)
                        <li><a href="/cart"><i class="material-icons left">shopping_cart</i> ตะกร้า</a></li>
                    @endif
                    @can('admin-action')
                        <li><a class="dropdown-trigger" href="#!" data-target="dropdown-admin">Admin<i class="material-icons right">arrow_drop_down</i></a></li>
                    @endcan
                    <li><a href="/logout">ออกจากระบบ</a></li>
                @elseif (env('NORMAL_LOGIN', false))
                    <li><a href="/login">เข้าสู่ระบบ</a></li>
                @endif
            </ul>

            <ul class="sidenav" id="nav-mobile">
                <li><a href="/">ดูสินค้าทั้งหมด</a></li>
                @if (Auth::check())
                    @if (Cart::count() > 0)
                        <li><a href="/cart"><i class="material-icons">shopping_cart</i> ตะกร้า</a></li>
                    @endif
                    @can('admin-action')
                        <li>
                            <div class="divider"></div>
                        </li>
                        <li><a class="subheader">Admin</a></li>
                        <li><a href="/admin/cashier">Cashier</a></li>
                        <li><a href="/admin/delivery">Pickup</a></li>
                        <li><a href="/admin/inventory">Inventory</a></li>
                        <li><a href="/admin/find-order">Find order</a></li>
                        <li><a href="/admin/paycheck">Payment Verify</a></li>
                        <li>
                            <div class="divider"></div>
                        </li>
                    @endcan
                    <li><a href="/logout">ออกจากระบบ</a></li>
                @elseif (env('NORMAL_LOGIN', false))
                    <li><a href="/login">เข้าสู่ระบบ</a></li>
                @endif
            </ul>

            @can('admin-action')
                <ul id="dropdown-admin" class="dropdown-content">
                    <li><a href="/admin/cashier">Cashier</a></li>
                    <li><a href="/admin/delivery">Pickup</a></li>
                    <li><a href="/admin/inventory">Inventory</a></li>
                    <li><a href="/admin/find-order">Find order</a></li>
                    <li><a href="/admin/paycheck">Payment Verify</a></li>
                </ul>
            @endcan

            <a href="#" data-target="nav-mobile" class="sidenav-trigger"><i class="material-icons">menu</i></a>
        </div>
    </div>
</nav>

@yield('beforemain')

<main class="container">
    @yield('main')
    <br/>
</main>

@section('footer')
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
@show

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-alpha.4/js/materialize.min.js" integrity="sha256-Jq79Dv9shjRhvRMzr71WgDr8gbZxm0AYmeJxx5jLdCU="
            crossorigin="anonymous"></script>
    <script>
        var navElement = document.querySelector('.sidenav');
        var sideNav = new M.Sidenav(navElement, {});
        var dropIns = M.Dropdown.init(document.querySelector('.dropdown-trigger'), {});
        @if (session()->has('notify'))
        M.toast({html: '{!! session('notify') !!}'});
        @endif
    </script>
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-88470919-8"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }

        gtag('js', new Date());

        gtag('config', 'UA-88470919-8');
    </script>
@show
</body>
</html>

@extends('layouts.master')

@section('style')
    <style>
        .col {
            border-radius: 0.2em;
            font-size: 16px;
            color: black;
        }

        .row img {
            max-height: 12em;
        }

        .row h5 {
            font-size: 1.5em;
            margin-bottom: 0;
        }

        .row .author {
            font-size: 0.9em;
        }

        .row .price {
            font-weight: bold;
            color: blue;
        }
    </style>
@endsection

@section('beforemain')
    <div class="grey darken-2 white-text" style="padding-top:1rem;padding-bottom:2rem;">
        <div class="container">
            <h2 class="left-align">สินค้าที่ระลึก</h2>
        </div>
    </div>
@endsection

@section('main')
    @if (Auth::check())
        @if (Auth::user()->is_admin)
            <div class="sector red lighten-5">
                <b>Admin Menu</b>&emsp;
                <a class="waves-effect waves-light btn purple" href="/admin/cashier">Cashier</a>
            </div>
            @endif

        @if ($myProducts = Auth::user()->products OR config('app.env') != 'production')
            <div class="sector purple lighten-5">
                <b>Merchant Menu</b>&emsp;
                @if (config('app.env') == 'production')
                    @if ($myProducts)
                        สินค้าของฉัน:
                        @foreach($myProducts as  $product)
                            <a href="/product/{{ $product->id }}">{{ $product->name }}</a>
                        @endforeach
                    @endif
                @else
                    <a class="waves-effect waves-light btn" href="/merchant/register">เพิ่มสินค้า</a><br/>
                    @if ($myProducts)
                        สินค้าของฉันที่เพิ่มข้อมูลแล้ว:
                        @foreach($myProducts as  $product)
                            <a href="/merchant/edit/{{ $product->id }}">{{ $product->name }}</a>
                        @endforeach
                    @endif
                @endif
            </div>
        @endif

        @if (($pendingOrders = Auth::user()->orders()->where('status', 'unpaid')->get()) AND $pendingOrders->isNotEmpty())
            <div class="sector yellow lighten-5">
                <b>คำสั่งซื้อคงค้าง</b>&ensp;
                @foreach($pendingOrders as $order)
                    <a href="/cart/order/{{ $order->id }}">เลขที่ {{ $order->id }} ({{ $order->price }} บาท)</a>
                @endforeach
            </div>
        @endif
    @endif

    <div class="row center-align">
        @foreach(\App\Product::inRandomOrder()->get() as $order => $product)
            <a href="/product/{{ $product->id }}">
                <div class="col s12 m6 l3 hoverable">
                    <img class="responsive-img" src="{{ $product->picture }}"/>
                    <h5>{{ $product->name }}</h5>
                    <span class="author">{{ $product->author }}</span><br/>
                    @if ($product->type == 'หนังสือ')
                        หนังสือ{{ $product->book_type }} {{ $product->detail['page'] }} หน้า
                    @else
                        {{ $product->type }}
                    @endif
                    <br/>

                    @if ($product->inStock())
                        <span class="price">{{ $product->price }} บาท</span>
                    @else
                        <span class="red-text">หมด</span>
                    @endif
                </div>
            </a>
            @if ($order > 2 AND $order % 4 == 3)
    </div>
    <div class="row center-align">
        @endif
        @endforeach
    </div>
@endsection
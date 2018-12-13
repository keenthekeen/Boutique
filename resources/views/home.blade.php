@extends('layouts.master')

@section('title')
    <title>หน้าหลัก - {{ config('app.name') }}</title>
@endsection

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

        @media screen and (min-width: 993px) {
            .container {
                width: 80%;
            }
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
        @can('admin-action')
            <div class="sector red lighten-5">
                <b>Admin Menu</b>&emsp;
                <a class="waves-effect waves-light btn teal" href="/admin/cashier">Cashier</a>&emsp;
                <a class="waves-effect waves-light btn orange" href="/admin/delivery">Pickup</a>&emsp;
                <a class="waves-effect waves-light btn purple" href="/admin/inventory">Inventory</a>&emsp;
                <a class="waves-effect waves-light btn lime" href="/admin/findOrder">Find order</a>
            </div>
        @endcan

        @if (!env('SHOP_CLOSED', false))
            @if (($myProducts = Auth::user()->products)->isNotEmpty())
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

            @if (($pendingOrders = Auth::user()->orders()->where('status', '!=', 'delivered')->get()) AND $pendingOrders->isNotEmpty())
                <div class="sector yellow lighten-5">
                    <b>คำสั่งซื้อคงค้าง</b>&ensp;
                    @foreach($pendingOrders as $order)
                        <a href="/cart/order/{{ $order->id }}">เลขที่ {{ $order->id }} ({{ $order->price }} บาท)</a>
                    @endforeach
                </div>
            @endif
        @endif
    @endif

    @if (env('SHOP_CLOSED', false))
        <div class="sector blue lighten-3">
            <b>ขอขอบคุณที่ให้ความสนใจ</b><br/>
            คณะกรรมการจัดงานฯ ขอขอบพระคุณผู้เข้าร่วมงานที่ให้ความสนใจเยี่ยมชมบูธของที่ระลึกเป็นจำนวนมาก อย่างไรก็ตามทางเราไม่ได้ให้บริการจัดส่งสินค้าหลังจากงานนิทรรศการฯ ทั้งนี้
            ท่านสามารถดูสินค้าที่มีขายภายในงาน และติดต่อไปยังผู้จัดทำสินค้าโดยตรง โดยคลิกที่ปุ่ม "เว็บไซต์ผู้จัดทำ"
        </div>
    @endif

    @php
        $isMobile = str_contains(Request::userAgent(), ['Android', 'Mobile Safari']) AND !Request::has('desktop');
        $rowMember = $isMobile ? 2 : 4;
        $rowCut = 0;
        $products = App\Product::select('id', 'picture', 'name', 'author', 'type', 'detail', 'book_type', 'book_subject', 'price')->with('items')->orderBy('type');
        if (Request::has("sort") OR env('SHOP_CLOSED', false)) {
        $products = $products->orderBy("name")->get();
        } else {
        $products = $products->inRandomOrder()->get();
        }
        $bookOrder = 0;
        $nonbookOrder = 0;
        $isBookActive = rand(0, 1);
    @endphp

    {{-- <div class="row">
        <div class="col s12">
            <ul class="tabs">
                <li class="tab col s6"><a {!! $isBookActive ? 'class="active"' : '' !!} href="#book-tab">หนังสือ</a></li>
                <li class="tab col s6"><a {!! $isBookActive ? '' : 'class="active"' !!} href="#nonbook-tab">ของที่ระลึก</a></li>
            </ul>
        </div>
        <div id="book-tab" class="col s12">--}}
    <p>ข้ามไปยัง <a href="#first-books">หนังสือ</a> | <a href="#first-nonbooks">ไม่ใช่หนังสือ</a></p>

    <div class="row center-align" id="first-books">
        @foreach($products->where('type', 'หนังสือ') as $product)
            <a href="/product/{{ $product->id }}">
                <div class="col s6 m6 {{ $isMobile ? 'l6' : 'l3' }} hoverable">
                    <img style="padding-top: 1vw;" class="responsive-img" src="{{ $product->picture }}"/>
                    <h5>{{ $product->name }}</h5>
                    <span class="author">{{ $product->author }}</span><br/>
                    @if ($product->type == 'หนังสือ')
                        <span title="{{ $product->detail['page'] }} หน้า มีโจทย์ {{ $product->detail['question'] }} ข้อ">หนังสือ{{ $product->book_type }}
                            วิชา{{ implode(' ', $product->book_subject) }}</span>
                    @else
                        {{ $product->type }}
                    @endif
                    <br/>

                    @if ($product->inStock())
                        <span class="price">{{ $product->price }} บาท</span>
                    @else
                        <span class="red-text" title="{{ $product->price }} บาท">หมด</span>
                    @endif
                </div>
            </a>
            @if (++$bookOrder > 2 AND ($bookOrder % $rowMember == $rowCut))
    </div>
    <div class="row center-align">
        @endif
        @endforeach
    </div>
    {{-- </div>
    <div id="nonbook-tab" class="col s12"> --}}
    <div class="row center-align" id="first-nonbooks">
        @foreach($products->where('type', '!=', 'หนังสือ') as $product)
            <a href="/product/{{ $product->id }}">
                <div class="col s6 m6 {{ $isMobile ? 'l6' : 'l3' }} hoverable">
                    <img style="padding-top: 1vw;" class="responsive-img" src="{{ $product->picture }}"/>
                    <h5>{{ $product->name }}</h5>
                    <span class="author">{{ $product->author }}</span><br/>
                    @if ($product->type == 'หนังสือ')
                        หนังสือ{{ $product->book_type }} วิชา{{ implode(' ', $product->book_subject) }}
                    @else
                        {{ $product->type }}
                    @endif
                    <br/>

                    @if ($product->inStock())
                        <span class="price">{{ $product->price }} บาท</span>
                    @else
                        <span class="red-text" title="{{ $product->price }} บาท">หมด</span>
                    @endif
                </div>
            </a>
            @if (++$nonbookOrder > 2 AND ($nonbookOrder % $rowMember == $rowCut))
    </div>
    <div class="row center-align">
        @endif
        @endforeach
    </div>
    </div>
    {{-- </div> --}}

@endsection

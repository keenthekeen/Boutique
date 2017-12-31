@extends('layouts.master')

@section('style')
    @php
        /** @var $product \App\Product */
    @endphp
    <style>
        .sector {
            margin-top: 2rem;
        }

        .sector .row {
            margin-bottom: 0;
        }
    </style>
@endsection

@section('main')
    <div class="row">
        <div class="col s12 m4 l3">
            <img class="responsive-img" src="{{ $product->picture }}"/>
        </div>
        <div class="col s12 m8 l9">
            <span style="font-size: 2rem">{{ $product->name }}</span> <span style="font-size: 0.95rem">{{ $product->author }}</span><br/>
            @if ($product->type == 'หนังสือ')
                หนังสือ{{ $product->book_type }} {{ $product->detail['page'] }} หน้า
                @if (str_contains($product->book_type, 'โจทย์'))
                    มีโจทย์ {{ $product->detail['question'] }} ข้อ
                @endif
            @else
                {{ $product->type }}
            @endif

            @unless (empty($product->detail['url']))
                <br/><a href="{{ $product->detail['url'] }}">Website</a>
            @endunless
            @unless (empty($product->book_example))
                <br/><br/><a class="waves-effect waves-light btn fullwidth orange" href="{{ $product->book_example }}">ตัวอย่างหนังสือ</a>
            @endunless
        </div>
    </div>

    @unless (empty($product->poster))
        <img class="responsive-img" src="{{ $product->poster }}"/>
    @endunless

    {!! $product->detail['description'] !!}


    @if (!$product->inStock())
        <div class="sector red lighten-4">
            สินค้าหมด - Out of Stock
        </div>
    @elseif (!Auth::check())
        <div class="sector amber lighten-5">
            กรุณา<a href="/login">เข้าสู่ระบบ</a>เพื่อสั่งซื้อ
        </div>
    @elseif ($items = $product->items AND $items->count() > 0)
        <div class="sector blue lighten-5">
            @foreach ($items as $item)
                <div class="row">
                    <div class="col s12 m8 l9">
                        ซื้อ {{ $item->name }} ในราคา {{ $item->price }} บาท
                    </div>
                    <div class="col s12 m4 l3">
                        <a class="waves-effect waves-light btn fullwidth" href="/cart/add/{{ $item->id }}"><i class="material-icons left">add_shopping_cart</i>เพิ่มในตะกร้า</a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="sector red lighten-4">
            ยังไม่มีสินค้า
        </div>
    @endif

@endsection
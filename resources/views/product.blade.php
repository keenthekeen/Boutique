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

        #basic-info a.btn {
            margin-top: 0.8rem
        }
    </style>
@endsection

@section('main')
    <div class="row" id="basic-info">
        <div class="col s12 m4 l3">
            <img class="responsive-img" src="{{ $product->picture }}"/>
        </div>
        <div class="col s12 m8 l9">
            <span style="font-size: 2rem">{{ $product->name }}</span> <span style="font-size: 0.95rem">{{ $product->author }}</span><br/>
            <label style="margin-top:0.8rem">ข้อมูลสินค้า</label><br/>
            @if ($product->type == 'หนังสือ')
                หนังสือ{{ $product->book_type }} วิชา{{ implode(' ', $product->book_subject) }} {{ $product->detail['page'] }} หน้า
                @if (str_contains($product->book_type, 'โจทย์'))
                    มีโจทย์ {{ $product->detail['question'] }} ข้อ
                @endif
            @else
                {{ $product->type }}
            @endif
            <br/>

            @unless (empty($product->detail['url']))
                <br/><a class="waves-effect waves-light btn fullwidth teal" href="{{ $product->detail['url'] }}">เว็บไซต์ผู้จัดทำ</a>
            @endunless
            @unless (empty($product->book_example))
                <br/><a class="waves-effect waves-light btn fullwidth orange" href="{{ $product->book_example }}">ตัวอย่างหนังสือ</a>
            @endunless
        </div>
    </div>

    @unless (empty($product->poster))
        <img class="responsive-img" src="{{ $product->poster }}" style="margin-bottom: 1rem"/><br/>
    @endunless

    {!! nl2br($product->detail['description']) !!}


    @if (!$product->inStock())
        <div class="sector red lighten-4">
            สินค้าหมด - Out of Stock
        </div>
    @elseif (!Auth::check())
        {{-- <div class="sector amber lighten-5">
            กรุณา<a href="/login">เข้าสู่ระบบ</a>เพื่อสั่งซื้อ
        </div> --}}
    @elseif ($items = $product->items AND $items->count() > 0)
        <div class="sector blue lighten-5">
            @foreach ($items as $item)
                <div class="row">
                    <div class="col s12 m8 l9">
                        ซื้อ {{ $item->name }} ในราคา {{ $item->price }} บาท
                    </div>
                    <div class="col s12 m4 l3">
                        <a class="waves-effect waves-light btn fullwidth disabled" href="/cart/add/{{ $item->id }}"><i class="material-icons left">add_shopping_cart</i>เพิ่มในตะกร้า</a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="sector red lighten-4">
            ยังไม่มีสินค้า
        </div>
    @endif

    @if (Auth::check() AND Auth::user()->is_admin)
        <div class="sector purple lighten-3">
            <h4>ข้อมูลผู้ฝากขาย</h4>
            <h5>{{ $product->getAttributeAsString('owner_detail_1.name') }}</h5>
            ห้อง {{ $product->getAttributeAsString('owner_detail_1.room') }} | LINE: {{ $product->getAttributeAsString('owner_detail_1.line') }} |
            โทรศัพท์: {{ $product->getAttributeAsString('owner_detail_1.phone') }}
            <h5>{{ $product->getAttributeAsString('owner_detail_2.name') }}</h5>
            ห้อง {{ $product->getAttributeAsString('owner_detail_2.room') }} | LINE: {{ $product->getAttributeAsString('owner_detail_2.line') }} |
            โทรศัพท์: {{ $product->getAttributeAsString('owner_detail_2.phone') }}
            <br/>
            <h4>การรับเงิน</h4>
            ธนาคาร {{ $product->getAttributeAsString('payment.bank') }} เลขที่บัญชี {{ $product->getAttributeAsString('payment.number') }} ชื่อ {{ $product->getAttributeAsString('payment.name') }}
            @unless (empty($product->getAttributeAsString('payment.promptpay')))
                | พร้อมเพย์ {{ $product->getAttributeAsString('payment.promptpay') }}
            @endunless
        </div>
    @endif

@endsection
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
    <div class="row center-align">
        @foreach(\App\Product::inRandomOrder()->get() as $order => $product)
            <a href="/product/{{ $product->id }}">
                <div class="col s12 m6 l3 hoverable">
                    <img class="responsive-img" src="{{ $product->picture }}"/>
                    <h5>{{ $product->name }}</h5>
                    <span class="author">{{ $product->author }}</span><br/>
                    @if ($product->type == 'หนังสือ')
                        หนังสือ{{ $product->book_type }} {{ $product->book_page }} หน้า
                    @else
                        {{ $product->type }}
                    @endif
                    <br/>

                    @if ($product->amountLeft())
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
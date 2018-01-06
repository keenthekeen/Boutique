@extends('layouts.master')

@section('title')
    <title>Cashier - TUOPH</title>
@endsection

@section('main')

@endsection

{{--
@section('style')
    <style>
        .product-title {
            font-size: 2rem;
        }
    </style>
@endsection
@section('main')
    @php
        $products = \App\Product::orderBy('name')->get();
    @endphp
    <div class="row">
        <div class="col s12">
            <ul class="tabs">
                <li class="tab col s6"><a href="#books">หนังสือ</a></li>
                <li class="tab col s6"><a href="#nonbooks">ไม่ใช่หนังสือ</a></li>
            </ul>
        </div>
        <div id="books" class="col s12">
            <ul class="collection">
                @foreach($products->where('type', 'หนังสือ') as $product)
                    <li class="collection-item">
                        <span class="product-title">{{ $product->name }}</span>&ensp;
                        @foreach ($product->items as $item)
                            <a class="btn {{ $item->colorCode() }}">{{ $item->name }}</a>
                            @endforeach
                    </li>
                @endforeach
            </ul>
        </div>
        <div id="nonbooks" class="col s12">
            Test 2
        </div>
    </div>
@endsection

@section('script')
    @parent
    <script>
        var tabs = new M.Tabs(document.querySelector('.tabs'), {});
    </script>
@endsection --}}
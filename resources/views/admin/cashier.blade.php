@extends('layouts.master')

@section('title')
    <title>Cashier - TUOPH</title>
@endsection

@section('style')
    <style>
        .progress {
            margin-top: 30vh;
        }

        #iQuantity {
            margin: 0 2rem;
        }
    </style>
@endsection

@section('main')

    <div id="add-form" class="sector hide">
        <h4>เพิ่มสินค้า</h4>
        <p>
            <label>ประเภท</label>&emsp;
            <label>
                <input name="type" type="radio" value="books"/>
                <span>หนังสือ</span>
            </label>&ensp;
            <label>
                <input name="type" type="radio" value="non-books"/>
                <span>ไม่ใช่หนังสือ</span>
            </label>
        </p>
        <p>
            <label>สินค้า</label>
            <select class="browser-default" id="iProduct">
                <option value="" disabled selected>กรุณาเลือกประเภทสินค้า</option>
            </select>
        </p>
        <p>
            <label>แบบ</label>
            <select class="browser-default" id="iItem">
                <option value="" disabled selected>กรุณาเลือกสินค้า</option>
            </select>
        </p>
        <p>
            <label>จำนวน</label>&emsp;
            <a class="waves-effect btn-flat lighten-3 light-blue" style="padding: 0 1rem" onclick="$('.iQuantity').text('1')"><i class="material-icons">fast_rewind</i></a>
            <a class="waves-effect btn-flat lighten-3 blue" onclick="$('.iQuantity').text($('.iQuantity').text()-1)"><i class="material-icons">remove</i></a>
            <span id="iQuantity">1</span>
            <a class="waves-effect btn-flat lighten-3 cyan" onclick="$('.iQuantity').text($('.iQuantity').text()+1)"><i class="material-icons">add</i></a>
        </p>
        <a class="waves-effect btn indigo fullwidth">เพิ่ม</a>
    </div>

    <div id="loading">
        <div class="progress">
            <div class="indeterminate"></div>
        </div>
        <h5 class="center-align">กำลังโหลด</h5>
    </div>
@endsection

@section('script')
    @parent
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        var productList;
        $(document).ready(function () {
            fetch('/admin/products', {
                credentials: 'same-origin'
            }).then(function (response) {
                return response.json();
            }).then(function (data) {
                productList = data;
                $(".hide").removeClass("hide");
                $("#loading").slideUp();

                $("input:radio[name=type]:checked").change(function () {
                    var products = productList[$(this).val()];
                    console.log(products);
                    var options = '';
                    for (var i = 0; i < products.length; i++) {
                        options.concat('<option value="'+products[i].id+'">'+products[i].name+'</option>');
                    }
                    console.log(options);
                    $("#iProduct").html(options);
                }).change();
            });
        });
    </script>
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
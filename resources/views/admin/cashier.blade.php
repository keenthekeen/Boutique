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

        .not-type {display: none}
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
        <p class="not-type">
            <label>สินค้า</label>
            <select class="browser-default" id="iProduct">
                <option value="" disabled selected>กรุณาเลือกประเภทสินค้า</option>
            </select>
        </p>
        <p class="not-type">
            <label>แบบ</label>
            <select class="browser-default" id="iItem">
                <option value="" disabled selected>กรุณาเลือกสินค้า</option>
            </select>
        </p>
        <p class="not-type">
            <label>จำนวน</label>&emsp;
            <a class="waves-effect btn-flat lighten-3 light-blue" style="padding: 0 1rem" onclick="$('#iQuantity').text('1')"><i class="material-icons">fast_rewind</i></a>
            <a class="waves-effect btn-flat lighten-3 blue" onclick="$('#iQuantity').text(Math.abs($('#iQuantity').text()-1))"><i class="material-icons">remove</i></a>
            <span id="iQuantity">1</span>
            <a class="waves-effect btn-flat lighten-3 cyan" onclick="$('#iQuantity').text($('#iQuantity').text()-(-1))"><i class="material-icons">add</i></a>
        </p>
        <a class="waves-effect btn indigo fullwidth not-type" id="addButton">เพิ่ม</a>
    </div>

    <table id="items-table"></table>

    <a class="btn waves-effect teal fullwidth" style="display: none;" onclick="processCart()" id="process-btn">PROCESS</a><br/>

    <div id="summary"></div>

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
        var cart = [];
        $(document).ready(function () {
            fetch('/admin/products', {
                credentials: 'same-origin'
            }).then(function (response) {
                return response.json();
            }).then(function (data) {
                productList = data;
                $("input[type=radio][name=type]").prop("checked", false);
                $(".hide").removeClass("hide");
                $(".not-type").hide();
                $("#loading").slideUp();

                $("input[type=radio][name=type]").change(function () {
                    var products = 'books';
                    if ($(this).val()) {
                        products = productList[$(this).val()];
                        var options = '';
                        for (var i in products) {
                            options += '<option value="' + products[i].id + '" data-order="' + i + '">' + products[i].name + '</option>';
                        }
                        $("#iProduct").html(options).change();
                        $(".not-type").slideDown();
                    } else {
                        $("#iProduct").html('<option selected>เลือกประเภทสินค้า</option>').change();
                        $(".not-type").slideUp();
                    }
                });

                $("#iProduct").change(function () {
                    if (isNaN($(this).children('option:selected').data('order'))) {
                        $("#iItem").html('');
                        return;
                    }
                    var items = productList[$('input[type=radio][name=type]').val()][$(this).children('option:selected').data('order')].items;
                    var options = '';
                    if (items.length > 1) {
                        $("#iItem").attr('disabled', false);
                        for (var i in items) {
                            options += '<option value="' + items[i].id + '" data-price="' + items[i].price + '">' + items[i].name + ' (' + items[i].price + '  บาท)</option>';
                        }
                    } else if (items.length === 1) {
                        $("#iItem").attr('disabled', true);
                        options = '<option value="' + items[0].id + '" data-price="' + items[0].price + '" selected>' + items[0].name + ' (' + items[0].price + ' บาท)</option>';
                    } else {
                        $("#iItem").attr('disabled', true);
                        options = '<option value="X" selected disabled>--- ไม่มีสินค้า ---</option>';
                    }
                    $("#iItem").html(options);
                }).trigger('change');
            });
        });

        $("#addButton").click(function () {
            var item = $("#iItem").find("option:selected");
            if (isNaN(item.val()) || item.val() <= 0) {
                $(this).removeClass('indigo').addClass('red');
                setTimeout(function () {
                    $("#addButton").addClass('indigo').removeClass('red');
                }, 1500);
                return;
            }

            var isFoundInCart = false;
            cart.map(function (it) {
                if (it.id === item.val()) {
                    isFoundInCart = true;
                    it.quantity = it.quantity - (-$("#iQuantity").text());
                }
                return it;
            }.bind(item));
            if (!isFoundInCart) {
                cart.push({name: item.text(), price: item.data("price"), id: item.val(), quantity: $("#iQuantity").text()});
            }
            renderTable();
        });

        function renderTable() {
            var content = '<tr><th>ชื่อ</th><th>จำนวน</th><th>ราคา</th><th></th></tr>';
            for (var i in cart) {
                content += '<tr><td>' + cart[i].name + '</td><td>' + cart[i].quantity + '</td><td>' + (cart[i].price * cart[i].quantity) + '</td><td><a onclick="removeItem(' + cart[i].id + ')">X</a></td></tr>';
            }
            $("#items-table").html(content);
            if (cart.length > 0) {
                $("#process-btn").slideDown();
            } else {
                $("#process-btn").slideUp();
                $("#summary").html('');
            }
        }

        function removeItem(id) {
            cart = cart.filter(function (it) {
                return it.id.toString() !== id.toString();
            }.bind(id));
            renderTable();
        }

        function processCart() {
            $.ajax({
                type: "POST",
                url: '/admin/cashier',
                data: {cart: cart},
                success: function (data) {
                    $("#summary").html(data);
                },
                dataType: 'html'
            });
        }
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
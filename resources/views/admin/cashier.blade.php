@extends('layouts.master')

@section('title')
    <title>Cashier - {{ config('app.name') }}</title>
@endsection

@section('style')
    <style>
        .progress {
            margin-top: 30vh;
        }

        #iQuantity {
            margin: 0 2rem;
        }

        .not-type {
            display: none
        }
    </style>
@endsection

@section('main')

    <div id="add-form" class="sector hide">
        <h4>เพิ่มสินค้า</h4>
        <p>
            <label>ประเภท</label>&emsp;
            <label>
                <input class="type-radio" name="type" type="radio" value="books"/>
                <span>หนังสือ</span>
            </label>&ensp;
            <label>
                <input class="type-radio" name="type" type="radio" value="non-books"/>
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

    <a class="btn waves-effect teal fullwidth" style="display: none;" onclick="processCart(false)" id="process-btn">PROCESS</a><br/><br/>

    <div id="summary"></div>

    <div id="loading">
        <div class="progress">
            <div class="indeterminate"></div>
        </div>
        <h5 class="center-align">กำลังโหลด</h5>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    @parent
    <script>
        var productList;
        var cart = [];
        var selectedType;
        var checker = "DEFAULT";
        var loadTime = (new Date()).getTime();
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

                $(".type-radio").change(function () {
                    var products = 'books';
                    if ($(this).val()) {
                        products = productList[$(this).val()];
                        var options = '';
                        for (var i in products) {
                            options += '<option value="' + products[i].id + '" data-order="' + i + '">' + (parseInt(i, 10)+1) + '. ' + products[i].name + '</option>';
                        }
                        selectedType = $(this).val();
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
                    var items = productList[selectedType][$(this).children('option:selected').data('order')].items;
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
                });
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
            }
            $("#summary").html('');
        }

        function removeItem(id) {
            cart = cart.filter(function (it) {
                return it.id.toString() !== id.toString();
            }.bind(id));
            renderTable();
        }

        function processCart(proceed, method = 'cash') {
            if (proceed && !confirm('แน่ใจหรือ?')) {
                return;
            }
            $.ajax({
                type: "POST",
                url: '/admin/cashier',
                data: {cart: cart, _token: "{{ csrf_token() }}", proceed: proceed, checker: checker, method: method},
                success: function (data) {
                    if (data.status == 'checked') {
                        $("#summary").html(
                            "<h4>บันทึกคำสั่งซื้อและรับเงินแล้ว " + data.total + ' บาท</h4>รหัสคำสั่งซื้อ <a target="_blank" href="/admin/findOrder?order=' + data.order_id + '">' + data.order_id + "</a> เมื่อ " + data.order_time + '<br /><a class="btn waves-effect pink lighten-3" onclick="clearCart()"><i class="material-icons">clear</i> Clear</a>');
                    } else {
                        $("#summary").html(
                            "ลดราคาไป " + data.discount + " บาท<br/>" +
                            "<h4>ราคารวม " + data.total + " บาท</h4>" +
                            "<br/>" +
                            "<img src='https://promptpay.io/{{ env('PROMPTPAY_NUMBER', '0819010182') }}/" + data.total + ".png'/>" +
                            "<br/>" +
                            "<br/>" +
                            "<a class=\"btn waves-effect green fullwidth\" onclick=\"processCart(true, 'cash')\">PROCEED WITH CASH</a>" +
                            "<br/>" +
                            "<br/>" +
                            "<a class=\"btn waves-effect blue fullwidth\" onclick=\"processCart(true, 'promptpay')\">PROCEED WITH PROMPTPAY</a>" +
                            "<br/>" +
                            "<br/>");
                        checker = data.checker;
                    }
                },
                dataType: 'json'
            });
        }

        function clearCart() {
            // If page has been loaded from more than 15 minutes ago, refresh
            if ((new Date()).getTime() - loadTime > 900000) {
                window.location.reload();
            } else {
                cart = [];
                renderTable();
            }
        }
    </script>
@endsection

@section('footer')
    <footer class="page-footer grey darken-2">
        <div class="footer-copyright">
            <div class="container">
                Last updated {{ date('Y-m-d H:i:s') }}
            </div>
        </div>
    </footer>
@endsection

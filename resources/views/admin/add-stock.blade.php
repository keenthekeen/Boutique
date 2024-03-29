@extends('layouts.master')

@section('title')
    <title>Add Stock - TUOPH Shop</title>
@endsection

@section('beforemain')
    <div class="grey darken-2 white-text" style="padding-top:1rem;padding-bottom:2rem;">
        <div class="container">
            <h2 class="left-align">เพิ่มสินค้าในคลังเก็บสินค้า</h2>
        </div>
    </div>
@endsection

@section('main')

    @if(Session::has('succeed'))
        <div class="z-depth-1 card-panel white-text green" style="max-width:1280px; margin: auto auto auto;">
            เพิ่มสินค้าสำเร็จ!
            <br>
        </div>
    @endif

    <div class="sector">
        <h4>ข้อมูลสินค้า</h4>
        <div class="row">
            <div class="input-field col s12">
                <select name="id" id="id">
                    @foreach(['หนังสือ','กระเป๋า','สมุด','ริสแบนด์','เสื้อ','แฟ้ม','พวงกุญแจ'] as $type)
                        <optgroup label="{{ $type }}">
                            @foreach(\App\Product::where('type', $type)->get() as $product)
                                <option value="{{ $product->id }}" templateName="{{ $type . ' ' . $product->name }}" price="{{ $product->price }}">{{ $product->name }} ({{ $product->author }})</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                <label>สินค้า</label>
            </div>
            <div class="input-field col s12">
                <input id="name" type="text" class="validate" name="name">
                <label for="name">ชื่อแบบ</label>
            </div>
            <div class="input-field col s12">
                <select name="type" id="type">
                    <option value="NORMAL">สินค้า</option>
                    <option value="PROMOTION">โปรโมชั่น</option>
                </select>
                <label>ประเภท</label>
            </div>
            <div class="input-field col s12">
                <input id="amount" type="number" class="validate" name="amount">
                <label for="amount">จำนวน</label>
            </div>
            <div class="input-field col s12">
                <input id="price" type="number" class="validate" name="price">
                <label for="price">ราคา</label>
            </div>
            <button class="btn waves-effect waves-light fullwidth blue" onclick="addStock();" id="btn-submit">เพิ่ม
                <i class="material-icons left">add</i>
            </button>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.4.1/jquery.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
    @parent
    <script>
        $(document).ready(function(){
            $('select').formSelect();
        });

        $('#id').on('change', function() {
            $('#name').attr('value', $('#id option:selected').attr('templateName'));
            $('#price').attr('value', $('#id option:selected').attr('price'));
        });

        function addStock(){
            $.ajax({
                type: "POST",
                url: '/admin/addStock',
                data: jQuery.param({
                    id: $('#id').val(),
                    name: $('#name').val(),
                    amount: $('#amount').val(),
                    price: $('#price').val(),
                    type: $('#type').val(),
                    _token: '{{ csrf_token() }}'
                }),
                success: function(data) {
                    if (data.code == 200){
                        M.toast({html: 'สำเร็จ!'});
                    }
                    else{
                        M.toast({html: 'มีข้อผิดพลาดเกิดขึ้น'});
                    }
                },
                error: function(error){
                    M.toast({html: 'มีข้อผิดพลาดเกิดขึ้น'});
                },
                dataType: 'json'
            });
        }
    </script>
@endsection

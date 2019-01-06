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

    @if (count($errors) > 0)
        <ul class="collection white-text">
            <li class="collection-item red darken-1">เกิดข้อผิดพลาดในข้อมูล
                ({{ implode(', ', $errors->all()) }})
            </li>
        </ul>
    @endif

    <form method="POST" action="/admin/addStock">
        {{ csrf_field() }}
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
                    <input id="amount" type="number" class="validate" name="amount">
                    <label for="amount">จำนวน</label>
                </div>
                <div class="input-field col s12">
                    <input id="price" type="number" class="validate" name="price">
                    <label for="price">ราคา</label>
                </div>
                <button class="btn waves-effect waves-light fullwidth blue" type="submit" id="btn-submit">เพิ่ม
                    <i class="material-icons left">add</i>
                </button>
            </div>
        </div>
    </form>
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    @parent
    <script>
        $(document).ready(function(){
            $('select').formSelect();
        });

        $('#id').on('change', function() {
            $('#name').attr('value', $('#id option:selected').attr('templateName'));
            $('#price').attr('value', $('#id option:selected').attr('price'));
        });
    </script>
@endsection
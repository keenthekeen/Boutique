@extends('layouts.master')

{{-- @section('style')
    <style>
        table {
            margin-bottom: 2rem;
        }

        td img {
            max-height: 5rem;
        }
    </style>
@endsection --}}

@section('beforemain')
    <meta http-equiv="refresh" content="5"/>
    <div class="grey darken-2 white-text" style="padding-top:1rem;padding-bottom:2rem;">
        <div class="container">
            <h2 class="left-align">จุดรับสินค้า</h2>
        </div>
    </div>
@endsection

@section('main')
    @if (count($errors) > 0)
        <ul class="collection white-text">
            <li class="collection-item red darken-1">เกิดข้อผิดพลาดในข้อมูล
                ({{ implode(', ', $errors->all()) }})
            </li>
        </ul>
    @endif
    @if (count($list) > 0)
        <form method="POST">
            {{ csrf_field() }}
            @foreach ($list as $id => $items)
                <div class="sector">
                    <h4>Order {{ $id }} <span style="font-size: 0.8em">({{ $items['total'] }} บาท)</span></h4>
                    @foreach ($items['items'] as $item)
                        - {{ $item['id'] }}: <b>{{ $item['name'] }}</b> x {{ $item['quantity'] }}<br/>
                    @endforeach
                    <button type="submit" name="order" value="{{ $id }}" class="btn orange waves-effect fullwidth" onclick="return confirm('แน่ใจหรือที่จะทำเครื่องหมายการสั่งซื้อ {{ $id }} ว่าส่งแล้ว?')">Mark
                        as delivered
                    </button>
                </div>
            @endforeach
        </form>
    @else
        <div class="fullwidth center-align">
            <br/><br/>
            <i class="large material-icons grey-text">brightness_5</i><br/>
            ยังไม่มีการสั่งซื้อรอจ่าย
        </div>
    @endif
@endsection
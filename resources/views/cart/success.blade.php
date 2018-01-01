@extends('layouts.master')

@section('title')
    <title>จ่ายเงินที่แคชเชียร์ - TUOPH</title>
@endsection

@section('main')
    @php
        /** @var $order \App\Order */
    @endphp
    <div class="fullwidth center-align">
        <br/><br/>
        @if ($order->status == 'unpaid')
            <i class="large material-icons yellow-text">attach_money</i><br/>
            โปรดนำข้อมูลต่อไปนี้แจ้งที่แคชเชียร์ภายในงานฯเพื่อชำระเงิน
        @elseif ($order->status == 'paid')
            <i class="large material-icons green-text">place</i><br/>
            โปรดนำข้อมูลต่อไปนี้แจ้งที่จุด<b>รับของ</b>
        @elseif ($order->status == 'delivered')
            <i class="large material-icons green-text">done</i><br/>
            ท่านได้รับสินค้าเรียบร้อยแล้ว
        @endif
        <br/>
        <span style="font-size: 2.3rem">เลขที่คำสั่งซื้อ {{ $order->id }}</span><br/>
        <span style="font-size: 1.7rem">ราคารวม {{ number_format($order->price) }} บาท</span><br/>
        <p style="font-size:0.9rem"><a href="https://openhouse.triamudom.ac.th" target="_blank">ไปยังเว็บไซต์งานนิทรศการฯ</a> เพื่อดูแผนผังงานและข้อมูลอื่นๆ</p>
    </div>

    <p>
        <b>สินค้าที่สั่งซื้อ</b>
    <ul class="browser-default">
        @foreach($order->items as $item)
            <li>{{ $item->productItem->name }} <span class="grey-text"> x {{ $item->quantity }}</span></li>
        @endforeach
    </ul>
    </p>

@endsection
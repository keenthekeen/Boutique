@extends('layouts.master')

@section('title')
    <title>Pick up - {{ config('app.name') }}</title>
@endsection

@section('beforemain')
    @unless (Request::has('norefresh') OR $mode == 'all')
        <meta http-equiv="refresh" content="5"/>
    @endunless
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

    <div class="row">
        <div class="col s6">
            @if ($mode == 'all')
                กำลังดูทั้งหมด
            @else
                <a href="/admin/delivery/all" class="btn waves-effect teal fullwidth">ดูทั้งหมด</a>
            @endif
        </div>
        <div class="col s6">
            @if ($mode == 'latest')
                กำลังดู 1 ชั่วโมงล่าสุด
            @else
                <a href="/admin/delivery/latest" class="btn waves-effect cyan fullwidth">ดู 1 ชั่วโมงล่าสุด</a>
            @endif
        </div>
    </div>

    @if (count($list) > 0)
        <a href="#footer" class="btn blue waves-effect fullwidth">ลงไปล่างสุด</a><br/>
        <form method="POST" action="/admin/delivery">
            {{ csrf_field() }}
            @foreach ($list as $id => $items)
                <div class="sector">
                    <h4>
                        <a href="/admin/findOrder?order={{ $id }}">Order {{ $id }}</a>
                        <span style="font-size: 0.8em">({{ $items['total'] }} บาท)</span>
                        <span style="font-size: 0.6em">{{ $items['time'] }}</span>
                        <span style="font-size: 0.4em">{{ json_decode($items['payment_note'])['method'] }}</span>
                    </h4>
                    @foreach ($items['items'] as $item)
                        - {{ $item['id'] }}: <b>{{ $item['name'] }}</b> x {{ $item['quantity'] }} <span class="{{ $items['isPriceMatch'] ? 'grey-text' : 'red-text' }}">({{ $item['price'] }}
                            บาท)</span><br/>
                    @endforeach
                    <br/>
                    <button type="submit" name="order" value="{{ $id }}" class="btn orange waves-effect fullwidth"
                            onclick="return confirm('แน่ใจหรือที่จะทำเครื่องหมายการสั่งซื้อ {{ $id }} ว่าส่งแล้ว?')">Mark
                        as delivered
                    </button>
                </div>
            @endforeach
            {!! $links !!}
        </form>
    @else
        <div class="fullwidth center-align">
            <br/><br/>
            <i class="large material-icons grey-text">brightness_5</i><br/>
            ยังไม่มีรายการรอรับสินค้า
        </div>
    @endif
@endsection

@section('footer')
    <footer class="page-footer grey darken-2" id="footer">
        <div class="footer-copyright">
            <div class="container">
                Last updated {{ date('Y-m-d H:i:s') }}
            </div>
        </div>
    </footer>
@endsection
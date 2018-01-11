@extends('layouts.master')

@section('beforemain')
    <meta http-equiv="refresh" content="5"/>
    <div class="grey darken-2 white-text" style="padding-top:1rem;padding-bottom:2rem;">
        <div class="container">
            <h2 class="left-align">ค้นหาการสั่งซื้อ</h2>
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
    <form method="POST">
        {{ csrf_field() }}
        <div class="row">
            <div class="col s6">
                <input id="i6a84e" name="search" type="text" class="validate" required data-length="4"/><label for="i6a84e">Order ID</label>
            </div>
            <div class="col s6">
                <button type="submit" class="btn waves-effect purple">Find</button>
            </div>
        </div>
    </form>
    @if (Request::has('search'))
        @if ($order = \App\Order::find(Request::input('search')))
            <form method="POST" action="/admin/delivery">
                {{ csrf_field() }}
                <div class="sector">
                    <h4>Order {{ $order->id }} <span style="font-size: 0.8em">({{ $order->price }} บาท)</span></h4>
                    @foreach ($order->items as $item)
                        - {{ $item->id }}: <b>{{ $item->name }}</b> x {{ $item->quantity }} <span class="grey-text">({{ $item->price }} บาท)</span><br/>
                    @endforeach
                    <button type="submit" name="order" value="{{ $id }}" class="btn orange waves-effect fullwidth"
                            onclick="return confirm('แน่ใจหรือที่จะทำเครื่องหมายการสั่งซื้อ {{ $id }} ว่าส่งแล้ว?')">
                        Mark
                        as delivered
                    </button>
                </div>
            </form>
        @else
            <h4>Not Found</h4>
        @endif
    @endif
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
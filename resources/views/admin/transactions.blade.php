@extends('layouts.master')

@section('title')
    <title>Transactions - {{ config('app.name') }}</title>
@endsection

@section('beforemain')
    <meta http-equiv="refresh" content="60"/>
    <div class="grey darken-2 white-text" style="padding-top:1rem;padding-bottom:2rem;">
        <div class="container">
            <h2 class="left-align">30-day Transactions</h2>
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

    <table>
        <tr>
            <th>Order ID</th>
            <th>OrderItem ID</th>
            <th>ProductItem ID</th>
            <th>จำนวน</th>
            <th>ราคา</th>
        </tr>
        @foreach (\App\Order::with('items')->whereIn('status', ['paid', 'delivered'])->whereRaw('updated_at >= DATE_SUB(NOW(),INTERVAL 30 DAY)')->get() as $order)
            <tr>
                <td colspan="2"><a href="/admin/findOrder?order={{ $order->id }}">Order {{ $order->id }}</a></td>
                <td colspan="2" class="grey">{{ $order->created_at }}</td>
                <td class="right-align">{{ $order->price }}</td>
            </tr>
            @foreach($order->items as $item)
                <tr>
                    <td>{{ $order->id }}</td>
                    <td>{{ $item->id }}</td>
                    <td><a href="/product/{{ $item->product_item_id }}">{{ $item->product_item_id }}</a></td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ $item->price }}</td>
                </tr>
            @endforeach
        @endforeach
    </table>
    <br/><br/>
    <h4>ยอดขายรวม {{ number_format(\App\Order::whereIn('status', ['paid', 'delivered'])->whereRaw('updated_at >= DATE_SUB(NOW(),INTERVAL 30 DAY)')->sum('price')) }} บาท</h4>
    <br/>
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

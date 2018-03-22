@extends('layouts.master')

@section('title')
    <title>Inventory - {{ config('app.name') }}</title>
@endsection

@section('beforemain')
    <meta http-equiv="refresh" content="60"/>
    <div class="grey darken-2 white-text" style="padding-top:1rem;padding-bottom:2rem;">
        <div class="container">
            <h2 class="left-align">Inventory</h2>
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
            <th>Product ID</th>
            <th>Item ID</th>
            <th>ชื่อ</th>
            <th>ราคา</th>
            <th>ขายไป</th>
        </tr>
        @foreach (\App\ProductItem::orderBy('name')->get() as $productItem)
            <tr>
                <td><a href="/product/{{ $productItem->product_id }}">{{ $productItem->product_id }}</a></td>
                <td>{{ $productItem->id }}</td>
                <td>{{ $productItem->name }}</td>
                <td>{{ $productItem->price }}</td>
                <td class="{{ (($left = $productItem->amount - ($sold = $productItem->orderItems()->sum('quantity'))) > 0) ? 'blue' : 'red' }} lighten-3" title="รับมา {{ $productItem->amount }} / เหลือ {{ $left }}">{{ $sold }}</td>
            </tr>
        @endforeach
    </table>
    <br/><br/>
    <h4>ยอดขายรวม {{ number_format(\App\Order::where('status', '!=', 'unpaid')->sum('price')) }} บาท</h4>
    <p class="red-text">คำเตือน: ปริมาณการขายอาจมีข้อผิดพลาด</p>
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
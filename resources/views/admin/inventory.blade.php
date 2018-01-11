@extends('layouts.master')

@section('beforemain')
    <meta http-equiv="refresh" content="60"/>
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

    <table>
        <tr>
            <th>Product ID</th>
            <th>Item ID</th>
            <th>ชื่อ</th>
            <th>รับ</th>
            <th>ขาย</th>
            <th>คงเหลือ</th>
            <th>Action</th>
        </tr>
    </table>
    @foreach (\App\ProductItem::orderBy('name')->get() as $productItem)
        <tr>
            <td><a href="/product/{{ $productItem->product_id }}">{{ $productItem->product_id }}</a></td>
            <td>{{ $productItem->id }}</td>
            <td>{{ $productItem->name }}</td>
            <td>{{ $productItem->amount }}</td>
            <td>{{ $sold = $productItem->orderItems()->sum('quantity') }}</td>
            <td class="{{ (($left = $productItem->amount - $sold) > 0) ? 'blue' : 'red' }}">{{ $left }}</td>
            <td></td>
        </tr>
    @endforeach
    <br/><br/>
    <h4>ยอดขายรวม {{ number_format(\App\Order::where('status', '!=', 'unpaid')->sum('price')) }} บาท</h4>
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
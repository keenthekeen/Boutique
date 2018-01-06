@extends('layouts.master')

@section('style')
    <style>
        table {
            margin-bottom: 2rem;
        }

        td img {
            max-height: 5rem;
        }
    </style>
@endsection

@section('beforemain')
    <div class="grey darken-2 white-text" style="padding-top:1rem;padding-bottom:2rem;">
        <div class="container">
            <h2 class="left-align"><i class="material-icons medium">shopping_cart</i> ตะกร้าสินค้า</h2>
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
    @if (Cart::count() > 0)
        <table class="striped">
            <thead>
            <tr>
                <th></th>
                <th>สินค้า</th>
                <th>ราคา</th>
                <th>จำนวน</th>
                <th>ราคารวม</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            @foreach(Cart::content() as $row)
                @php
                    // Reduce query amount
                        /** @var \Gloudemans\Shoppingcart\CartItem $row */
                        /** @var \App\ProductItem $item */
                        $item = $row->model;
                        /** @var \App\Product $product */
                        $product = $item->product;
                @endphp
                <tr>
                    <td><a href="/product/{{ $row->id }}"><img src="{{ $product->picture }}"/></a></td>
                    <td>{{ $product->type }} {{ $item->name }}</td>
                    <td>{{ $item->price }}</td>
                    <td>
                        <a class="waves-effect waves-yellow btn-flat" href="/cart/update/{{ $row->rowId }}/{{ $row->qty-1 }}"><i class="material-icons">remove</i></a>
                        {{ $row->qty }}
                        <a class="waves-effect waves-yellow btn-flat" href="/cart/update/{{ $row->rowId }}/{{ $row->qty+1 }}"><i class="material-icons">add</i></a>
                    </td>
                    <td>{{ $row->subtotal() }} บาท</td>
                    <td><a class="waves-effect waves-red btn-flat" href="/cart/remove/{{ $row->rowId }}"><i class="material-icons">delete</i></a></td>
                </tr>
            @endforeach
            </tbody>
        </table>

        <form method="POST" action="/cart/checkout" onsubmit="return confirm('คุณได้ตรวจสอบข้อมูลทั้งหมดว่าถูกต้องแล้วใช่หรือไม่?');">
            {{ csrf_field() }}
            @php
                $appliedPromotions = \App\Order::processPromotions(Cart::content());
                $discountSum = $appliedPromotions->pluck('reduced')->sum();
                $total = Cart::subtotal() - abs($discountSum);
            @endphp
            @if ($appliedPromotions->isNotEmpty())
                ส่วนลด:
                <ul class="browser-default">
                    @foreach($appliedPromotions as $promotion)
                        <li>
                            @php Debugbar::debug($promotion); @endphp
                            {{ $promotion['promotion']->name }} : {{ $promotion['times'] }} ครั้ง - ลดไป {{ abs($promotion['reduced']) }} บาท
                        </li>
                    @endforeach
                </ul>
            @endif
            <input type="hidden" name="total" value="{{ $total }}"/>
            รวมทั้งสิ้น <span style="font-size:2rem">{{ $total }} บาท</span><br/><br/>
            โปรดเลือกวิธีชำระเงิน
            <p>
                <label>
                    <input name="method" type="radio" value="cashier" checked/>
                    <span>จ่ายที่แคชเชียร์ (ภายในงาน)</span>
                </label>
            </p>
            {{-- <p>
                <label>
                    <input name="method" type="radio" value="card" disabled/>
                    <span>บัตร VISA/MasterCard (ค่าธรรมเนียม 4%)</span>
                </label>
            </p> --}}
            <button type="submit" class="waves-effect waves-light btn fullwidth blue">สั่งซื้อ</button>
            <span class="red-text">โปรดตรวจสอบข้อมูลว่าครบถ้วนถูกต้องก่อนดำเนินการต่อ</span>
        </form>
    @else
        <div class="fullwidth center-align">
            <br/><br/>
            <i class="large material-icons grey-text">brightness_5</i><br/>
            ยังไม่มีสินค้าในตะกร้า <a href="/">ดูสินค้า</a>
        </div>
    @endif
@endsection
@extends('layouts.master')

@section('beforemain')
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
                <input id="i6a84e" name="order" type="text" class="validate" required data-length="4" autofocus/><label for="i6a84e">Order ID</label>
            </div>
            <div class="col s6">
                <button type="submit" class="btn waves-effect purple">Find</button>
            </div>
        </div>
    </form>
    @if (Request::has('order'))
        @if ($order = \App\Order::find(Request::input('order')))
            <form method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="order" value="{{ $order->id }}"/>
                @php
                    if (Request::has('status')) {
                        $order->status = Request::input('status');
                        $order->save();
                    }
                    $statusColor = 'black-text';
                switch ($order->status) {
                    case 'unpaid' : $statusColor = 'red-text';break;
                    case 'paid' : $statusColor = 'blue-text';break;
                    case 'delivered': $statusColor = 'green-text';
                }
                $items = $order->items;
                $isPriceMatch = $items->sum('price') == $order->price;
                @endphp
                <div class="sector">
                    <h4>Order {{ $order->id }} <span style="font-size: 0.8em">({{ $order->price }} บาท)</span></h4>
                    <p>Status: <span class="{{ $statusColor }}">{{ $order->status }}</span></p>
                    @foreach ($items as $item)
                        - <b title="OrderItem ID {{ $item->id }}, ProductItem ID {{ $item->product_item_id }}">{{ $item->productItem->name }}</b> x {{ $item->quantity }} <span class="{{ $items['isPriceMatch'] ? 'grey-text' : 'red-text' }}">({{ $item->price }} บาท)</span><br/>
                    @endforeach
                    <button type="submit" class="btn waves-effect red" name="status" value="unpaid">Mark as unpaid</button>&emsp;
                    <button type="submit" class="btn waves-effect" name="status" value="paid">Mark as paid</button>&emsp;
                    <button type="submit" class="btn waves-effect orange" name="status" value="delivered" id="deliver-btn">Mark as delivered (\)</button>
                    <p style="font-size: 0.8rem">Created at {{ $order->created_at }}, Updated at {{ $order->updated_at }}</p>
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

@section('script')
    @parent
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        $(function () {
            $(document).keyup(function (event) {
                if (event.which == 220) {
                    $("#deliver-btn").click();
                }
            });
        });
    </script>
@endsection
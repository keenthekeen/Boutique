@extends('layouts.master')

@section('title')
    <title>Find Order - TUOPH Shop</title>
@endsection

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
    <form method="POST" action="/admin/findOrder">
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
                    $items = $order->items;
                        if (Request::has('status')) {
                            $order->status = Request::input('status');
                            if (Request::input('status') == 'paid' AND (empty($order->payment_note) OR $order->payment_note['method'] != 'CASH') AND $order->status != 'delivered') {
                                $order->payment_note = [
                                                'method' => 'CASH',
                                                'customer_type' => 'preorder',
                                                'cashier' => Auth::id(),
                                                'paid_time' => date(DATE_ISO8601)
                                            ];
                            }
                            $order->save();
                        } elseif (Request::has('correct_price')) {
                        $price = $items->sum('price');
                            $corrected = $price - $order->price;
                            $order->price = $price;
                            $paynote = $order->payment_note ?? array();
                            $paynote ['price_correct'] = $corrected;
                            $paynote ['price_correct_at'] = \Carbon\Carbon::now()->toDateTimeString();
                            $order->payment_note = $paynote;
                            $order->save();
                        } elseif (Request::has('payment_verify')) {
                            $paynote = $order->payment_note ?? array();
                            $paynote['status'] = Request::input('payment_verify');
                            if (Request::input('payment_verify') == 'verified') {
                                $order->status = 'paid';
                                $paynote['paid_time'] = date(DATE_ISO8601);
                            }
                            $order->payment_note = $paynote;
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
                    <h4>
                        <a href="/admin/findOrder?order={{ $order->id }}">Order {{ $order->id }}</a>
                        <span style="font-size: 0.8em" class="{{ $isPriceMatch ? '' : 'red-text' }}" title="Item price sum: {{ $items->sum('price') }}">({{ $order->price }} บาท)</span>
                    </h4>
                    <p>Status: <span class="{{ $statusColor }}">{{ $order->status }}</span></p>
                    @foreach ($items as $item)
                        - <b title="OrderItem ID {{ $item->id }}, ProductItem ID {{ $item->product_item_id }}">{{ ($productItem = $item->productItem)->name }}</b> x {{ $item->quantity }} <span
                                class="{{ (($productItem->price * $item->quantity) == $item->price) ? 'grey-text' : 'red-text' }}">({{ $item->price }} บาท)</span><br/>
                    @endforeach<br/>
                    <button type="submit" class="btn waves-effect red" name="status" value="unpaid">Mark as unpaid</button>&emsp;
                    @if ($order->status == 'unpaid')
                        <button type="submit" class="btn waves-effect" name="status" value="paid">Paid by cash</button>&emsp;
                    @elseif ($order->status == 'paid')
                        <button type="submit" class="btn waves-effect orange" name="status" value="delivered" id="deliver-btn">Mark as delivered (\)</button>
                    @else
                        <button type="submit" class="btn waves-effect" name="status" value="paid">Mark as Paid</button>
                    @endif
                    @unless($isPriceMatch)
                        <button type="submit" class="btn waves-effect purple" name="correct_price" value="do">Correct order price</button>
                    @endunless
                    <p style="font-size: 0.8rem">Created at {{ $order->created_at }}, Updated at {{ $order->updated_at }}</p>
                    @if (is_array($order->payment_note) AND !empty($order->payment_note))
                        <div class="sector">
                            <b>Payment Details</b><br/>
                            @foreach($order->payment_note as $key => $value)
                                <u>{{ ucwords(str_replace('_', ' ',$key)) }}</u> {{ $value }}
                                @if ($key == 'status' AND $value == 'unverified')
                                &emsp;
                                <button type="submit" class="btn waves-effect cyan" name="payment_verify" value="verified">Mark as verified</button>&emsp;
                                &emsp;
                                <button type="submit" class="btn waves-effect red" name="payment_verify" value="cancel">Cancel</button>&emsp;
                                @endif
                                @if ($key == 'cashier' AND is_numeric($value))
                                    <a href="https://facebook.com/{{ $value }}" target="_blank"><img src="https://graph.facebook.com/{{ $value }}/picture?type=small" style="height: 1.5rem"/></a>
                                @endif
                                <br/>
                            @endforeach
                        </div>
                    @else
                        <p style="font-size: 0.8rem">Note: {{ $order->getOriginal('payment_note') }}</p>
                    @endif
                    @unless (empty($order->user_id))
                        <div class="sector">
                            <img src="https://graph.facebook.com/{{ $order->user_id }}/picture?type=square" style="height: 1.5rem"/>
                            Order initiated by user <a href="https://facebook.com/{{ $order->user_id }}" target="_blank">{{ $order->user->name }}</a>
                        </div>
                    @endunless
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js" integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8=" crossorigin="anonymous"></script>
    @parent
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
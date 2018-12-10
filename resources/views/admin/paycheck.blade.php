@extends('layouts.master')

@section('title')
    <title>Payment Verification - Admin - {{ config('app.name') }}</title>
@endsection

@section('beforemain')
    @if (Request::has('refresh'))
        <meta http-equiv="refresh" content="60"/>
    @endif
    <div class="grey darken-2 white-text" style="padding-top:1rem;padding-bottom:2rem;">
        <div class="container">
            <h2 class="left-align">Payment Verification</h2>
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

    @if (($orders = \App\Order::where('status', 'pending')->get())->isNotEmpty())
        <table>
            <tr>
                <th>ID</th>
                <th>ราคาจริง</th>
                <th>ช่องทาง</th>
                <th>วันที่โอน</th>
                <th>เวลา</th>
                <th>จำนวนเงิน</th>
            </tr>
            @foreach ($orders as $order)
                <tr>
                    <td><a href="/admin/findOrder?order={{ $order->id }}">{{ $order->id }}</a></td>
                    <td>{{ $order->price }}</td>
                    @if (is_array($note = $order->payment_note))
                    <td>{{ $note['method'] }}</td>
                    <td>{{ $note['date'] ?? '-' }}</td>
                    <td>{{ $note['time'] ?? '-' }}</td>
                    <td>{{ $note['amount'] ?? ('('.$order->price.')') }}</td>
                    @else
                        <td colspan="4">{{ $note }}</td>
                    @endif
                </tr>
            @endforeach
        </table>
    @else
        <div class="fullwidth center-align">
            <br/><br/>
            <i class="large material-icons grey-text">brightness_5</i><br/>
            ยังไม่มีรายการรอยืนยันการชำระเงิน
        </div>
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
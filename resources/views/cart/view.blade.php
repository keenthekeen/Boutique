@extends('layouts.master')

@section('title')
    <title>คำสั่งซื้อ {{ $order->id }} - {{ config('app.name') }}</title>
@endsection

@section('main')
    @php
        /** @var $order \App\Order */
    @endphp
    <div class="fullwidth center-align">
        <br/><br/>
        @if ($order->status == 'unpaid')
            <i class="large material-icons yellow-text">attach_money</i><br/>
            โปรดชำระเงิน
        @elseif ($order->status == 'paid')
            <i class="large material-icons green-text">place</i><br/>
            โปรดนำข้อมูลต่อไปนี้แจ้งที่จุด<b>รับของ</b>
        @elseif ($order->status == 'delivered')
            <i class="large material-icons green-text">done</i><br/>
            ท่านได้รับสินค้าเรียบร้อยแล้ว
        @endif
        <br/>
        <span style="font-size: 2.3rem">เลขที่คำสั่งซื้อ {{ $order->id }}</span><br/>
        @if ($order->status != 'unpaid')
            <span style="font-size: 1.7rem">ราคารวม {{ number_format($order->price) }} บาท</span><br/>
        @endif
        <p style="font-size:0.9rem"><a href="https://openhouse.triamudom.ac.th" target="_blank">ไปยังเว็บไซต์งานนิทรศการฯ</a> เพื่อดูแผนผังงานและข้อมูลอื่นๆ</p>
    </div>

    <div>
        <b>สินค้าที่สั่งซื้อ</b>
        <ul class="browser-default">
            @foreach($order->items as $item)
                <li>{{ $item->productItem->name }} <span class="grey-text"> x {{ $item->quantity }}</span></li>
            @endforeach
        </ul>
    </div>

    <div>
        <b style="font-size:1.3rem">ชำระเงิน</b>

        <ul class="collapsible">
            <li>
                <div class="collapsible-header"><i class="material-icons">attach_money</i> ชำระด้วยเงินสด</div>
                <div class="collapsible-body">
                    <p>โปรดชำระเงินสดที่จุดชำระเงิน จำนวน {{ $order->price }} บาท โดยแจ้งรหัสคำสั่งซื้อ {{ $order->id }}</p>
                </div>
            </li>
            <li>
                <div class="collapsible-header"><img src="/assets/truemoneywallet.png"/> True Money Wallet</div>
                <div class="collapsible-body">
                    <p class="center-align"><img src="/assets/pay-truemoney.jpg"/></p>
                    โปรดโอนเงินผ่านแอพ True Money Wallet โดยกรอกข้อมูลดังนี้
                    <table>
                        <tr>
                            <th>ประเภทบัญชีผู้รับ</th>
                            <td>True Money Wallet <span class="red-text">(ไม่ใช่ PromptPay)</span></td>
                        </tr>
                        <tr>
                            <th>เบอร์โทรศัพท์มือถือผู้รับ</th>
                            <td>083 893 4557</td>
                        </tr>
                        <tr>
                            <th>ชื่อผู้รับ</th>
                            <td>ศิวัช เตชวรนันท์</td>
                        </tr>
                        <tr>
                            <th>จำนวนเงิน</th>
                            <td>{{ $order->price }} บาท</td>
                        </tr>
                        <tr>
                            <th>ข้อความถึงผู้รับ</th>
                            <td><i>BTQ-{{ $order->id }}-{{ substr($order->created_at, -2) }}</i></td>
                        </tr>
                    </table>
                    <ul class="browser-default">
                        <li>สามารถเติมเงินในแอพได้ผ่านทาง 7-Eleven, ตู้ True Money Kiosk, โอนจากบัญชีธนาคาร และอื่นๆ <a href="http://www.truemoney.com/wallet/">ดูข้อมูลจากเว็บไซต์ True Money</a></li>
                        <li>เบอร์โทรศัพท์ดังกล่าวใช้สำหรับรับโอนเงินเท่านั้น ไม่ใช่สำหรับติดต่อสอบถาม</li>
                        <li>QR Code นี้สำหรับโอนเงินผ่านแอพ True Money Wallet เท่านั้น ห้ามใช้โอนเงินจากธนาคาร</li>
                    </ul>
                </div>
            </li>
            <li>
                <div class="collapsible-header"><img src="/assets/promptpay.jpg"/> พร้อมเพย์</div>
                <div class="collapsible-body">
                    <p class="center-align"><img src="/assets/pay-promptpay.png"/></p>
                    โปรดสแกน QR Code ด้วยแอพ Mobile Banking หรือโอนที่ตู้ ATM ด้วยข้อมูลต่อไปนี้
                    <table>
                        <tr>
                            <th>เบอร์โทรศัพท์มือถือผู้รับ</th>
                            <td>083 893 4557</td>
                        </tr>
                        <tr>
                            <th>ชื่อผู้รับ</th>
                            <td>นายศิวัช เตชวรนันท์</td>
                        </tr>
                        <tr>
                            <th>จำนวนเงิน</th>
                            <td><b>{{ $order->amountForTransfer() }}</b> บาท</td>
                        </tr>
                    </table>
                    <ul class="browser-default">
                        <li>ให้โอนเงินตามยอดที่ระบบแสดง เพื่อให้สะดวกต่อการตรวจสอบยอด โดยระบบอาจเพิ่ม/ลดจำนวนเงินเป็นเศษสตางค์ไม่เกิน 1 บาท</li>
                        <li>เมื่อโอนเงินยอดมากกว่า 5,000 บาท ธนาคารอาจเรียกเก็บค่าธรรมเนียม <a href="http://www.epayment.go.th/home/app/media/uploads/images/bootstrap-builder/01PromtPay-04.png">ดูอัตราค่าธรรมเนียม</a>
                            <a href="http://epayment.newmediastorm.com/FAQ-promptpay-20_10_2559.pdf">คำถามที่ถามบ่อยเกี่ยวกับพร้อมเพย์</a></li>
                        <li>เบอร์โทรศัพท์ดังกล่าวใช้สำหรับรับโอนเงินเท่านั้น ไม่ใช่สำหรับติดต่อสอบถาม</li>
                    </ul>
                </div>
            </li>
            <li>
                <div class="collapsible-header"><img src="/assets/truemoneykiosk.png"/> True Money Kiosk</div>
                <div class="collapsible-body">
                    โปรดชำระเงินผ่านตู้ True Money Kiosk (สามารถพบได้ที่สถานีรถไฟฟ้าใต้ดิน MRT, True Shop) โดยใส่ข้อมูลดังนี้
                    <table>
                        <tr>
                            <th>ประเภท</th>
                            <td>เติมเงิน True Money Wallet ปุ่มทางขวา <span class="red-text">(ไม่ใช่จ่ายบิลทรู)</span></td>
                        </tr>
                        <tr>
                            <th>เลขบัญชีวอลเล็ท</th>
                            <td>083 893 4557</td>
                        </tr>
                        <tr>
                            <th>ใส่เงินรวมทั้งสิ้น (ตู้รับธนบัตรและเหรียญ)</th>
                            <td>{{ ceil($order->price) }} บาท <i>หากโอนเกินไม่สามารถคืนเงิน</i></td>
                        </tr>
                    </table>
                    <ul class="browser-default">
                        <li><a href="http://www.truemoney.com/kiosk/">ดูตำแหน่งตู้จากเว็บไซต์ True Money</a></li>
                        <li>เบอร์โทรศัพท์ดังกล่าวใช้สำหรับรับโอนเงินเท่านั้น ไม่ใช่สำหรับติดต่อสอบถาม</li>
                    </ul>
                </div>
            </li>
            <li>
                <div class="collapsible-header"><img src="/assets/linepay.jpg"/> LINE Pay</div>
                <div class="collapsible-body">
                    <p class="center-align"><img src="/assets/pay-line.jpg"/></p>
                    <p>โปรดโอนเงินจาก LINE Pay โดยโอนไปที่ LINE ID keen1234 จำนวน {{ $order->price }} บาท เมื่อโอนแล้วให้ส่งข้อความไปในแชทระบุรหัสว่า "BTQ-{{ $order->id }}
                        -{{ substr($order->created_at, -2) }}"</p>

                    <p>สามารถเติมเงินในแอพได้ที่สถานีรถไฟฟ้า BTS, McDonald's หรือหักบัญชีธนาคาร | บัญชี LINE ดังกล่าวสำหรับรับโอนเงินเท่านั้น ไม่ใช่ติดต่อสอบถาม</p>
                </div>
            </li>
        </ul>
    </div>

@endsection

@section('script')
    @parent
    <script>
        var collapsible = M.Collapsible.init(document.querySelector('.collapsible'), {});
    </script>
@endsection
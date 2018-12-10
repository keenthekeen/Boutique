@extends('layouts.master')

@section('title')
    <title>คำสั่งซื้อ {{ $order->id }} - {{ config('app.name') }}</title>
@endsection

@section('style')
    <style>
        .collapsible-body img {
            max-width: 20rem;
        }

        table {
            margin-bottom: 3rem;
        }

        td, th {
            padding: 8px 5px;
        }
    </style>
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
        @elseif ($order->status == 'pending')
            <i class="large material-icons yellow-text">attach_money</i><br/>
            รอการยืนยันการชำระเงิน
        @elseif ($order->status == 'paid')
            <i class="large material-icons green-text">place</i><br/>
            โปรดนำข้อมูลต่อไปนี้แจ้งที่จุด<b>รับสินค้า</b>
        @elseif ($order->status == 'delivered')
            <i class="large material-icons green-text">done</i><br/>
            ท่านได้รับสินค้าเรียบร้อยแล้ว
        @endif
        <br/>
        <span style="font-size: 2.3rem">เลขที่คำสั่งซื้อ {{ $order->id }}</span><br/>
        @if ($order->status != 'unpaid')
            <span style="font-size: 1.7rem">ราคารวม {{ number_format($order->price) }} บาท</span><br/>
        @endif
    </div>

    <div>
        <b>สินค้าที่สั่งซื้อ</b>
        <ul class="browser-default">
            @foreach($order->items as $item)
                <li>{{ $item->productItem->name }} <span class="grey-text"> x {{ $item->quantity }}</span></li>
            @endforeach
        </ul>
    </div>

    @if (count($errors) > 0)
        <div class="sector red darken-1">เกิดข้อผิดพลาดในข้อมูล
            ({{ implode(', ', $errors->all()) }})
        </div>
    @endif

    @if ($order->status == 'unpaid')
        <div>
            <b style="font-size:1.3rem">ชำระเงิน</b>
            <ul class="collapsible popout">
                <li>
                    <div class="collapsible-header"><i class="material-icons">attach_money</i> ชำระด้วยเงินสด</div>
                    <div class="collapsible-body">
                        <p>โปรดชำระเงินสดที่จุดชำระเงิน จำนวน {{ $order->price }} บาท
                            โดยแจ้งรหัสคำสั่งซื้อ {{ $order->id }}</p>
                        <p style="font-size:0.9rem"><a href="https://openhouse.triamudom.ac.th" target="_blank">ไปยังเว็บไซต์งานนิทรศการฯ</a>
                            เพื่อดูแผนผังงานและข้อมูลอื่นๆ</p>
                    </div>
                </li>
                @if (env('ENABLE_ONLINE_PAY', false))
                    <li>
                        <div class="collapsible-header"><img src="/assets/promptpay.jpg"/> พร้อมเพย์</div>
                        <div class="collapsible-body">
                            โปรดสแกน QR Code ด้วยแอพ Mobile Banking หรือโอนที่ตู้ ATM ด้วยข้อมูลต่อไปนี้
                            <div class="row">
                                <div class="col s12 m3 l4 center-align">
                                    <img src="/assets/pay-promptpay.png" class="fullwidth"/>
                                </div>
                                <div class="col s12 m9 l8">
                                    <table>
                                        <tr>
                                            <th>เบอร์โทรศัพท์ผู้รับ</th>
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
                                    <a class="waves-effect waves-light btn modal-trigger fullwidth orange"
                                       href="#modal-promptpay">แจ้งว่าโอนเงินแล้ว</a>
                                </div>
                            </div>
                            <div id="modal-promptpay" class="modal bottom-sheet">
                                <div class="modal-content">
                                    <h4>แจ้งการโอนเงินผ่านพร้อมเพย์</h4>
                                    <form method="POST" action="/cart/pay"
                                          onsubmit="return confirm('แน่ใจว่าต้องการแจ้งชำระเงินใช่หรือไม่')">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="order_id" value="{{ $order->id }}"/>
                                        <input type="hidden" name="payment_method" value="promptpay"/>
                                        โปรดกรอกข้อมูลต่อไปนี้โดยอิงจากสลิป/ข้อมูลจากธนาคารเท่านั้น
                                        <div class="row">
                                            <div class="input-field col s6 m4">
                                                <input type="text" class="datepicker" id="tk-date" name="date"
                                                       required/>
                                                <label for="tk-date">วันที่โอนเงิน</label>
                                            </div>
                                            <div class="input-field col s6 m4">
                                                <input type="text" class="timepicker" id="tk-time" name="time"
                                                       required/>
                                                <label for="tk-time">เวลาที่โอนเงิน</label>
                                            </div>
                                            <div class="input-field col s12 m4">
                                                <button class="btn indigo waves-effect fullwidth" type="submit">ยืนยัน
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <a href="#!"
                                       class="modal-action modal-close waves-effect waves-red btn-flat">ยกเลิก</a>
                                </div>
                            </div>
                            <ul class="browser-default">
                                <li>ผู้โอนเงินไม่จำเป็นต้องลงทะเบียนพร้อมเพย์ (ดู <a
                                            href="http://epayment.newmediastorm.com/FAQ-promptpay-20_10_2559.pdf"
                                            target="_blank">คำถามที่ถามบ่อยเกี่ยวกับพร้อมเพย์</a>)
                                </li>
                                <li>ให้โอนเงินตามยอดที่ระบบแสดง เพื่อให้สะดวกต่อการตรวจสอบยอด
                                    โดยระบบอาจเพิ่ม/ลดจำนวนเงินเป็นเศษสตางค์ไม่เกิน 1 บาท
                                </li>
                                @if ($order->amountForTransfer() > 5000)
                                    <li>เมื่อโอนเงินยอดมากกว่า 5,000 บาท ธนาคารอาจเรียกเก็บค่าธรรมเนียม (<a
                                                target="_blank"
                                                href="http://www.epayment.go.th/home/app/media/uploads/images/bootstrap-builder/01PromtPay-04.png">ดูอัตราค่าธรรมเนียม</a>)
                                    </li>
                                @endif
                                <li>เบอร์โทรศัพท์ดังกล่าวใช้สำหรับรับโอนเงินเท่านั้น ไม่ใช่สำหรับติดต่อสอบถาม</li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="collapsible-header"><img src="/assets/truemoneywallet.png"/> True Money Wallet</div>
                        <div class="collapsible-body">
                            <p class="center-align"></p>
                            โปรดโอนเงินผ่านแอพ True Money Wallet โดยสแกน QR Code หรือกรอกข้อมูลดังนี้
                            <div class="row">
                                <div class="col s12 m3 l4 center-align">
                                    <img src="/assets/pay-truemoney.jpg" class="fullwidth"/>
                                </div>
                                <div class="col s12 m9 l8">
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
                                    <form method="POST" action="/cart/pay"
                                          onsubmit="return confirm('แน่ใจว่าต้องการแจ้งชำระเงินใช่หรือไม่')">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="order_id" value="{{ $order->id }}"/>
                                        <input type="hidden" name="payment_method" value="truemoney"/>
                                        <button class="btn indigo waves-effect fullwidth" type="submit">
                                            โอนเงินตามที่ระบุแล้ว
                                        </button>
                                    </form>
                                    <br/>
                                </div>
                            </div>
                            <ul class="browser-default">
                                <li>สามารถเติมเงินในแอพได้ผ่านทาง 7-Eleven, ตู้ True Money Kiosk, โอนจากบัญชีธนาคาร
                                    และอื่นๆ <a target="_blank" href="http://www.truemoney.com/wallet/">ดูข้อมูลจากเว็บไซต์
                                        True Money</a> (ต้องเติมเงินในบัญชีของตนเองก่อนแล้วจึงโอน)
                                </li>
                                <li>เบอร์โทรศัพท์ดังกล่าวใช้สำหรับรับโอนเงินเท่านั้น ไม่ใช่สำหรับติดต่อสอบถาม</li>
                                <li>QR Code นี้สำหรับโอนเงินผ่านแอพ True Money Wallet เท่านั้น ห้ามใช้โอนเงินจากธนาคาร
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="collapsible-header"><img src="/assets/truemoneykiosk.png"/> True Money Kiosk</div>
                        <div class="collapsible-body">
                            โปรดชำระเงินผ่านตู้ True Money Kiosk โดยใส่ข้อมูลดังนี้
                            <table>
                                <tr>
                                    <th>ประเภท</th>
                                    <td>เติมเงิน True Money Wallet ปุ่มทางขวา <span
                                                class="red-text">(ไม่ใช่จ่ายบิลทรู)</span></td>
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
                            <a class="waves-effect waves-light btn modal-trigger fullwidth orange"
                               href="#modal-truekiosk">แจ้งว่าโอนเงินแล้ว</a><br/><br/>
                            <div id="modal-truekiosk" class="modal bottom-sheet">
                                <div class="modal-content">
                                    <h4>แจ้งการโอนเงินผ่าน TrueMoney Kiosk</h4>
                                    <form method="POST" action="/cart/pay"
                                          onsubmit="return confirm('แน่ใจว่าต้องการแจ้งชำระเงินใช่หรือไม่')">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="order_id" value="{{ $order->id }}"/>
                                        <input type="hidden" name="payment_method" value="truekiosk"/>
                                        โปรดกรอกข้อมูลต่อไปนี้โดยอิงจากสลิปเท่านั้น
                                        <div class="row">
                                            <div class="input-field col s6 m3">
                                                <input type="text" class="datepicker" id="tk-date" name="date"
                                                       required/>
                                                <label for="tk-date">วันที่โอนเงิน</label>
                                            </div>
                                            <div class="input-field col s6 m3">
                                                <input type="text" class="timepicker" id="tk-time" name="time"
                                                       required/>
                                                <label for="tk-time">เวลาที่โอนเงิน</label>
                                            </div>
                                            <div class="input-field col s12 m3">
                                                <input type="number" id="tk-amount" name="amount" required step="1"
                                                       min="{{ round($order->price) }}" max="50000"/>
                                                <label for="tk-amount">จำนวนเงิน</label>
                                            </div>
                                            <div class="input-field col s12 m3">
                                                <button class="btn indigo waves-effect fullwidth" type="submit">ยืนยัน
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <a href="#!"
                                       class="modal-action modal-close waves-effect waves-red btn-flat">ยกเลิก</a>
                                </div>
                            </div>
                            <ul class="browser-default">
                                <li>สามารถพบตู้ได้ที่สถานีรถไฟฟ้าใต้ดิน MRT และ True Shop <a target="_blank"
                                                                                             href="http://www.truemoney.com/kiosk/">ดูตำแหน่งตู้จากเว็บไซต์
                                        True Money</a></li>
                                <li>เบอร์โทรศัพท์ดังกล่าวใช้สำหรับรับโอนเงินเท่านั้น ไม่ใช่สำหรับติดต่อสอบถาม</li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <div class="collapsible-header"><img src="/assets/linepay.jpg"/> LINE Pay</div>
                        <div class="collapsible-body">
                            <div class="row">
                                <div class="col s12 m3 l4 center-align">
                                    <img src="/assets/pay-line.jpg" class="fullwidth"/>
                                </div>
                                <div class="col s12 m9 l8">
                                    โปรดโอนเงินจาก LINE Pay โดยโอนไปที่ LINE ID keen1234 จำนวน {{ $order->price }} บาท
                                    เมื่อโอนแล้วให้ส่งข้อความไปในแชทระบุรหัสว่า
                                    <i>"BTQ-{{ $order->id }}-{{ substr($order->created_at, -2) }}"</i><br/><br/>
                                    <form method="POST" action="/cart/pay"
                                          onsubmit="return confirm('แน่ใจว่าต้องการแจ้งชำระเงินใช่หรือไม่')">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="order_id" value="{{ $order->id }}"/>
                                        <input type="hidden" name="payment_method" value="line"/>
                                        <button class="btn indigo waves-effect fullwidth" type="submit">
                                            โอนเงินตามที่ระบุแล้ว
                                        </button>
                                    </form>
                                    <br/>
                                    <ul class="browser-default">
                                        <li>ใช้บริการ LINE Pay ได้โดยเข้าไปที่แอพ LINE ที่แท็บขวาสุด กด Rabbit LINE
                                            Pay
                                        </li>
                                        <li>สามารถเติมเงินในแอพได้ที่สถานีรถไฟฟ้า BTS, McDonald's หรือหักบัญชีธนาคาร
                                            (ต้องเติมเงินในบัญชีของตนเองก่อนแล้วจึงโอน)
                                        </li>
                                        <li>บัญชี LINE ดังกล่าวสำหรับรับโอนเงินเท่านั้น ไม่ใช่ติดต่อสอบถาม</li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="collapsible-header" id="cc-header"><i class="material-icons">credit_card</i> บัตร
                            VISA / MasterCard / JCB
                        </div>
                        <div class="collapsible-body">
                            <form action="/cart/pay/card" method="post" id="cc-checkout">
                                {{ csrf_field() }}
                                <input type="hidden" name="order_id" value="{{ $order->id }}"/>
                                <div id="token_errors"></div>
                                <input type="hidden" name="omise_token"/>
                                <div class="row">
                                    <div class="input-field col s12 m6">
                                        <input type="text" data-omise="holder_name" id="cc-name" autocomplete="cc-name"
                                               required/>
                                        <label for="cc-name">ชื่อผู้ถือบัตร</label>
                                    </div>
                                    <div class="input-field col s12 m6">
                                        <input type="text" data-omise="number" id="cc-number" autocomplete="cc-number"
                                               required/>
                                        <label for="cc-number">เลขที่บัตร</label>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-field col s3">
                                        <input type="number" id="cc-month" required step="1" min="1" max="12"
                                               data-omise="expiration_month" autocomplete="cc-exp-month"/>
                                        <label for="cc-month">เดือนหมดอายุ</label>
                                    </div>
                                    <div class="input-field col s3">
                                        <input type="number" id="cc-year" required step="1" min="2018" max="2030"
                                               data-omise="expiration_year" autocomplete="cc-exp-year"/>
                                        <label for="cc-year">ปีหมดอายุ</label>
                                    </div>
                                    <div class="input-field col s6">
                                        <input type="number" id="cc-cvv" required step="1" data-omise="security_code"
                                               autocomplete="cc-csc"/>
                                        <label for="cc-cvv">CVV</label>
                                    </div>
                                </div>
                                <button class="btn orange waves-effect fullwidth" type="submit" id="create_token">
                                    ชำระด้วยบัตรเครดิต
                                </button>
                            </form>
                        </div>
                    </li>
                @endif
            </ul>
        </div>

        <p>
            เมื่อยืนยันการชำระเงินแล้ว จึงจะสามารถรับสินค้าที่จุดรับได้ (ไม่มีบริการจัดส่งไปรษณีย์)<br/>
            หากมีข้อสงสัย หรือปัญหาเกี่ยวกับการชำระเงิน กรุณา<a href="/contact">ติดต่อเรา</a>
        </p>
    @elseif ($order->status == 'paid')
        <p style="font-size:0.9rem">{{ date(DATE_RSS) }}</p>
    @endif
@endsection

@section('script')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"
            integrity="sha384-tsQFqpEReu7ZLhBV2VZlAu7zcOV+rXbYlF2cqB8txI/8aZajjp4Bqd+V6D5IgvKT"
            crossorigin="anonymous"></script>
    @parent
    <script>
        $(document).ready(function () {
            $('.collapsible').collapsible();
            $('.modal').modal();
            $('.datepicker').datepicker({yearRange: 1});
            $('.timepicker').timepicker({twelveHour: false});
        });
    </script>
    @if ($order->status == 'unpaid')
        <script src="https://cdn.omise.co/omise.js"></script>
        <script>
            function createOmiseToken(card, form) {
                // Send a request to create a token then trigger the callback function once
                // a response is received from Omise.
                //
                // Note that the response could be an error and this needs to be handled within
                // the callback.
                Omise.setPublicKey("{{ \App\OmiseCharge::getPublicKey() }}");
                Omise.createToken("card", card, function (statusCode, response) {
                    if (response.object == "error" || !response.card.security_code_check) {
                        // Display an error message.
                        var message_text = "การตรวจสอบรหัสความปลอดกัย (CVV) ผิดพลาด";
                        if (response.object == "error") {
                            message_text = response.message;
                        }
                        $("#token_errors").html(message_text);

                        // Re-enable the submit button.
                        form.find("input[type=submit]").prop("disabled", false);
                    } else {
                        // Then fill the omise_token.
                        form.find("[name=omise_token]").val(response.id);

                        // Remove card number from form before submiting to server.
                        form.find("[data-omise=number]").val("");
                        form.find("[data-omise=security_code]").val("");

                        // submit token to server.
                        form.get(0).submit();
                    }
                });
            }

            $("#cc-checkout").submit(function () {
                var form = $(this);

                // Disable the submit button to avoid repeated click.
                form.find("input[type=submit]").prop("disabled", true);

                createOmiseToken({
                    "name": form.find("[data-omise=holder_name]").val(),
                    "number": form.find("[data-omise=number]").val(),
                    "expiration_month": form.find("[data-omise=expiration_month]").val(),
                    "expiration_year": form.find("[data-omise=expiration_year]").val(),
                    "security_code": form.find("[data-omise=security_code]").val()
                }, form);

                // Prevent the form from being submitted;
                return false;

            });
            $("#cc-header").click(function () {
                if (!window.PaymentRequest) {
                    console.log("Payment Request API not supported!");
                    return;
                }

                var supportedInstruments = [{
                    supportedMethods: ['basic-card'],
                    data: {
                        supportedNetworks: ['visa', 'mastercard', 'jcb']
                    }
                }];

                // Checkout details
                var details = {
                    total: {
                        label: 'ค่าสินค้าทั้งหมด',
                        amount: {currency: 'THB', value: '{{ $order->price }}'}
                    }
                };

                var request = new PaymentRequest(supportedInstruments, details);
                request.show().then(function (result) {
                    createOmiseToken({
                        "name": result.details.cardholderName,
                        "number": result.details.cardNumber,
                        "expiration_month": result.details.expiryMonth,
                        "expiration_year": result.details.expiryYear,
                        "security_code": result.details.cardSecurityCode
                    }, $("#cc-checkout"));
                    return result.complete('success');
                });
            });
        </script>
    @endif
@endsection
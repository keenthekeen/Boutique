@extends('layouts.master')

@section('title')
    <title>Merchant Registration - TUOPH</title>
@endsection

@section('style')
    <style>
        input[type='number'] {
            -moz-appearance: textfield;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
        }

        form .row {
            margin-bottom: 0.8rem;
        }
    </style>
@endsection

@section('beforemain')
    <div class="grey darken-2 white-text" style="padding-top:1rem;padding-bottom:2rem;">
        <div class="container">
            <h2 class="left-align">เพิ่มข้อมูลสินค้า</h2>
        </div>
    </div>
@endsection

@section('main')
    <p>
        <b class="amber-text text-darken-2">โปรดอ่านข้อความและแบบฟอร์มทั้งหมดอย่างละเอียดก่อนเริ่มกรอกฟอร์ม</b><br/>
        เพิ่มข้อมูลในระบบจัดจำหน่ายสินค้า งานนิทรรศการวิชาการ<br/>
        - สินค้าจะไม่ถูกจำหน่าย หากไม่มีข้อมูลในระบบ<br/>
        - ข้อมูลของสินค้าจะถูกเผยแพร่ผ่านทางเว็บไซต์ เพื่ออำนวยความสะดวกให้ผู้เข้าชมงานสามารถเลือกดูสินค้าได้ล่วงหน้าโดยไม่ต้องรอดูที่หน้างาน ซึ่งมีคิวจำนวนมาก<br/>
        - สำหรับนักเรียนเตรียมอุดมศึกษา ฝากจำหน่ายสินค้าเท่านั้น<br/>
        - ห้ามเผยแพร่ URL หรือภาพหน้าจอเว็บไซต์โดยไม่ได้รับอนุญาต<br/>
        - ควรดูฟอร์มทั้งหมดก่อนว่าต้องกรอกอะไรบ้าง แล้วเตรียมข้อมูลไว้ให้พร้อมก่อนเริ่มกรอก เนื่องจากต้องกรอกให้เสร็จทั้งหมดจึงจะบันทึกข้อมูลได้<br/>
        - กรอกฟอร์มหนึ่งครั้ง ต่อหนึ่งสินค้า กรณีหนังสือมีเล่มที่ 1, เล่มที่ 2 แยกขายถือว่าสินค้าคนละอย่างกันเพราะเนื้อหาต่างกันทั้งหมด แต่เสื้อมีหลายไซส์, กระเป๋าหลายสีให้ถือเป็นอย่างเดียวกัน
    </p>
    <form action="/merchant/register" method="POST" enctype="multipart/form-data">
        @php
            if (empty($product)) {
                $product = new \App\Product();
            } else {
                echo '<input type="hidden" name="id" value="'.$product->id.'" />';
            }
        @endphp

        {{ csrf_field() }}
        @if (count($errors) > 0)
            <ul class="collection white-text">
                <li class="collection-item red darken-1">เกิดข้อผิดพลาดในข้อมูล
                    ({{ implode(', ', $errors->all()) }})
                </li>
            </ul>
        @endif

        <div class="sector">
            <h4>ผู้รับผิดชอบ 1</h4>
            <div class="row">
                <div class="col s6">{{ Auth::user()->name }}</div>
                <div class="col s6">{{ Auth::user()->email }}</div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6">
                    {!! $product->createInput('owner_detail_1.name', 'ชื่อ', true) !!}
                    <span class="helper-text" data-error="ข้อมูลผิดรูปแบบ">ภาษาไทย คำนำหน้าติดกับชื่อ</span>
                </div>
                <div class="input-field col s12 m6">
                    {!! $product->createInput('owner_detail_1.room', 'ห้อง', true, 3) !!}
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6">
                    {!! $product->createInput('owner_detail_1.line', 'LINE ID') !!}
                </div>
                <div class="input-field col s6">
                    {!! $product->createInput('owner_detail_1.phone', 'โทรศัพท์มือถือ', true, 10) !!}
                </div>
            </div>
        </div>

        <div class="sector">
            <h4>ผู้รับผิดชอบ 2</h4>
            <div class="row">
                <div class="input-field col s12 m6">
                    {!! $product->createInput('owner_detail_2.name', 'ชื่อ', true) !!}
                    <span class="helper-text" data-error="ข้อมูลผิดรูปแบบ">ภาษาไทย คำนำหน้าติดกับชื่อ</span>
                </div>
                <div class="input-field col s12 m6">
                    {!! $product->createInput('owner_detail_2.room', 'ห้อง', true, 3) !!}
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6">
                    {!! $product->createInput('owner_detail_2.line', 'LINE ID') !!}
                </div>
                <div class="input-field col s6">
                    {!! $product->createInput('owner_detail_2.phone', 'โทรศัพท์มือถือ', true, 10) !!}
                </div>
            </div>
        </div>

        <div class="sector">
            <h4>การรับเงิน</h4>
            <span class="blue-text">
                - กรณีบัญชีอยู่นอกกรุงเทพมหานครและปริมณฑล ผู้ฝากขายเป็นผู้รับผิดชอบค่าธรรมเนียมการโอนต่างเขตสำนักหักบัญชี<br/>
                - หากบัญชีรับโอนไม่มีพร้อมเพย์ ผู้ฝากขายอาจต้องรับผิดชอบค่าธรรมเนียมการโอนระหว่างธนาคาร (ไม่เกิน 120 บาท)
            </span>
            <div class="row">
                <div class="input-field col s12 m6">
                    {!! $product->createOption('payment.bank', 'ธนาคาร', ['ธนาคารกรุงเทพ' => 'BBL', 'ธนาคารกสิกรไทย' => 'KBank', 'ธนาคารไทยพาณิชย์' => 'SCB', 'ธนาคารกรุงไทย' => 'KTB', 'ธนาคารธนชาติ' => 'Thanachart', 'ธนาคารทหารไทย' => 'TMB'], true, false) !!}
                </div>
                <div class="input-field col s12 m6">
                    {!! $product->createInput('payment.number', 'เลขที่บัญชี', true, 20) !!}
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6">
                    {!! $product->createInput('payment.name', 'ชื่อบัญชี', true) !!}
                </div>
                <div class="input-field col s12 m6">
                    {!! $product->createInput('payment.promptpay', 'พร้อมเพย์ (ถ้ามี)', false, 13) !!}
                </div>
            </div>
        </div>


        <div class="sector">
            <h4>ข้อมูลสินค้า</h4>
            <div class="row">
                <div class="input-field col s12 m6 type-cont">
                    {!! $product->createOption('type', 'ประเภทสินค้า', ['หนังสือ', 'สมุด', 'ริสแบนด์', 'พวงกุญแจ', 'กระเป๋า', 'เสื้อ', 'แฟ้ม'], true, false) !!}
                </div>
                <div class="input-field col s12 m6">
                    {!! $product->createInput('author', 'ผู้จัดทำ', true, 20) !!}
                    <span class="helper-text" data-error="ข้อมูลผิดรูปแบบ">ในรูปแบบ 948 TU78 หรือ DMC48 TU79 เป็นต้น</span>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 m6">
                    {!! $product->createInput('name', 'ชื่อสินค้า', true) !!}
                    <span class="helper-text" data-error="ข้อมูลผิดรูปแบบ">ใส่เฉพาะชื่อสินค้า งดใส่ "หนังสือ" หรือ "by DMC48"</span>
                </div>
                <div class="input-field col s12 m6">
                    {!! $product->createInput('price', 'ราคา (บาท)', true, 1000, 'number') !!}
                    <span class="helper-text" data-error="ข้อมูลผิดรูปแบบ">เป็นจำนวนเต็ม ตั้งแต่ 1 - 1000 บาท</span>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    <textarea id="textarea1" name="detail[description]" class="materialize-textarea" data-length="3000">{{ $product->getOldInput('detail.description') }}</textarea>
                    <label for="textarea1">รายละเอียดสินค้า</label>
                    <span class="helper-text" data-error="ข้อมูลผิดรูปแบบ">ข้อความอธิบายสรรพคุณสินค้าอย่างละเอียด</span>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    {!! $product->createInput('detail.url', 'URL', false, 250) !!}
                    <span class="helper-text" data-error="ข้อมูลผิดรูปแบบ">เว็บไซต์ หรือ Facebook Page (ขึ้นต้นด้วย https:// หรือ http://)</span>
                </div>
            </div>
        </div>

        <div class="sector" id="sectorBook">
            <h4>ข้อมูลหนังสือ</h4>
            <div class="row">
                <div class="input-field col s12 m6">
                    {!! $product->createOption('book_type', 'ประเภทเนื้อหา', ['เนื้อหา', 'โจทย์', 'เนื้อหาและโจทย์'], false, false) !!}
                </div>
                <div class="input-field col s6 m3">
                    {!! $product->createInput('detail.page', 'จำนวนหน้าที่มีเนื้อหา', false, 500, 'number') !!}
                </div>
                <div class="input-field col s6 m3">
                    {!! $product->createInput('detail.question', 'จำนวนข้อโจทย์', false, 1500, 'number') !!}
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12">
                    {!! $product->createOption('book_subject.', 'วิชา', ['ภาษาไทย', 'คณิตศาสตร์', 'วิทยาศาสตร์', 'ภาษาอังกฤษ', 'สังคมศึกษา'], false, true) !!}
                </div>
            </div>
            <div class="row">
                <div class="file-field input-field col s12">
                    <div class="btn">
                        <span>ตัวอย่างเนื้อหา</span>
                        <input type="file" accept=".pdf,application/pdf" name="book_example"/>
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text">
                        <span class="helper-text" data-error="ข้อมูลผิดรูปแบบ">ส่วนหนึ่งของเนื้อหาหลักของหนังสือ เพื่อเป็นตัวอย่างประกอบการเลือกซื้อ (PDF 1-10 หน้า ขนาดไม่เกิน 10 MB; ไม่จำเป็น; <a
                                    href="/assets/book-example.pdf" target="_blank">ตัวอย่าง</a>)</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="sector">
            <h4>รายละเอียดโปรโมชั่นและแบบสินค้า</h4>
            <p>
                - กรณีเสื้อผ้าหรือกระเป๋ามีหลายสีหรือหลายขนาด ให้แจ้งขนาด/สีที่มี พร้อมราคา<br/>
                - กรณีจัดโปรโมชั่น อธิบายโปรโมชั่น ได้แก่ ซื้ออะไรกับอะไร กี่ชิ้น ลดราคาเท่าไหร่<u>ต่อชิ้น</u> (เนื่องจากระบบต้องแบ่งรายได้ตามสินค้า) เช่น ซื้อ A คู่กับ B แล้ว A ลด 10 บาท และ B ลด 15
                บาท โดยจะต้องใส่ข้อมูลให้<u>ตรงกันทุกสินค้าที่ร่วมรายการ</u>
            </p>
            <div class="row">
                <div class="input-field col s12">
                    <textarea id="textarea2" name="note" class="materialize-textarea" data-length="1500">{{ $product->getOldInput('note') }}</textarea>
                    <label for="textarea2">โปรโมชั่นและแบบสินค้า</label>
                </div>
            </div>
        </div>

        <div class="sector">
            <h4>ภาพประกอบ</h4>
            <div class="row">
                <div class="file-field input-field col s12">
                    <div class="btn">
                        <span>ภาพสินค้า</span>
                        <input type="file" accept=".jpg,image/jpeg" name="picture"/>
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text">
                        <span class="helper-text" data-error="ข้อมูลผิดรูปแบบ">ภาพตัวสินค้า พื้นหลังขาวหรือใส, ไฟล์ JPG ความละเอียด 1-10 Megapixel ขนาดไม่เกิน 1.5 MB</span>
                    </div>
                </div>
            </div>
            <div class="sector grey lighten-5">
                <h5>ตัวอย่างภาพสินค้า</h5>
                <div class="row center-align" style="margin-bottom: 0">
                    <div class="col s4">
                        <img class="responsive-img" src="/assets/picture-book.jpg">
                        <br/><i class="material-icons green-text medium">check</i>
                    </div>
                    <div class="col s4">
                        <img class="responsive-img" src="/assets/picture-hm.jpg">
                        <br/><i class="material-icons green-text medium">check</i>
                    </div>
                    <div class="col s4">
                        <img class="responsive-img" src="/assets/picture-no.jpg">
                        <br/><i class="material-icons red-text medium">close</i>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="file-field input-field col s12">
                    <div class="btn">
                        <span>โปสเตอร์</span>
                        <input type="file" accept=".jpg,image/jpeg" name="poster"/>
                    </div>
                    <div class="file-path-wrapper">
                        <input class="file-path validate" type="text">
                        <span class="helper-text" data-error="ข้อมูลผิดรูปแบบ">ภาพโปรโมตสินค้า อาจแสดงสรรพคุณ โปรโมชั่น หรือลักษณะสินค้าก็ได้ (ไม่จำเป็น) ขนาดไม่เกิน 4 MB</span>
                    </div>
                </div>
            </div>
            <div class="sector grey lighten-5">
                <h5>ตัวอย่างโปสเตอร์</h5>
                <div class="row center-align" style="margin-bottom: 0">
                    <div class="col s6">
                        <img class="responsive-img" src="/assets/poster-cupsaa.jpg">
                        <br/><i class="material-icons green-text medium">check</i>
                    </div>
                    <div class="col s6">
                        <img class="responsive-img" src="/assets/poster-crescentia.jpg">
                        <br/><i class="material-icons green-text medium">check</i>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col s12">
                <button class="btn waves-effect waves-light fullwidth blue" type="submit" id="btn-submit">บันทึก
                    <i class="material-icons left">save</i>
                </button>
            </div>
        </div>

    </form>
@endsection

@section('script')
    @parent
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script>
        $(document).ready(function () {
            $('select').each(function () {
                var instance = new M.Select(this, {});
            });

            $(".type-cont select").change(function () {
                if ($(".type-cont option:selected").val() == 'หนังสือ') {
                    $("#sectorBook").slideDown();
                } else {
                    $("#sectorBook").slideUp();
                }
            }).change();
        });
    </script>
@endsection
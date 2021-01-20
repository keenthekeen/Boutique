<?php

return [
    'accepted'             => 'ข้อมูล :attribute ต้องผ่านการยอมรับก่อน',
    'active_url'           => 'ข้อมูล :attribute ต้องเป็น URL เท่านั้น',
    'after'                => 'ข้อมูล :attribute ต้องเป็นวันที่หลังจาก :date.',
    'after_or_equal'       => 'ข้อมูล :attribute ต้องเป็นวันที่ตั้งแต่วันที่ :date หรือหลังจากนั้น.',
    'alpha'                => 'ข้อมูล :attribute ต้องเป็นตัวอักษรภาษาอังกฤษเท่านั้น',
    'alpha_dash'           => 'ข้อมูล :attribute ต้องเป็นตัวอักษรภาษาอังกฤษ ตัวเลข และ _ เท่านั้น',
    'alpha_num'            => 'ข้อมูล :attribute ต้องเป็นตัวอักษรภาษาอังกฤษ ตัวเลข เท่านั้น',
    'array'                => 'ข้อมูล :attribute ต้องเป็น array เท่านั้น',
    'before'               => 'ข้อมูล :attribute ต้องเป็นวันที่ก่อน :date.',
    'before_or_equal'      => 'ข้อมูล :attribute ต้องเป็นวันที่ก่อนหรือเท่ากับวันที่ :date.',
    'between'              => [
        'numeric' => 'ข้อมูล :attribute ต้องอยู่ในช่วงระหว่าง :min - :max.',
        'file'    => 'ข้อมูล :attribute ต้องมีขนาดระหว่าง :min - :max กิโลไบต์',
        'string'  => 'ข้อมูล :attribute ต้องมีความยาวตัวอักษรระหว่าง :min - :max ตัวอักษร',
        'array'   => 'ข้อมูล :attribute ต้องมีค่าระหว่าง :min - :max ค่า',
    ],
    'boolean'              => 'ข้อมูล :attribute ต้องเป็นจริง หรือเท็จ เท่านั้น',
    'confirmed'            => 'ข้อมูล :attribute ไม่ตรงกัน',
    'date'                 => 'ข้อมูล :attribute ต้องเป็นวันที่',
    'date_format'          => 'ข้อมูล :attribute ไม่ตรงกับข้อมูลกำหนด :format.',
    'different'            => 'ข้อมูล :attribute และ :other ต้องไม่เท่ากัน',
    'digits'               => 'ข้อมูล :attribute ต้องเป็น :digits',
    'digits_between'       => 'ข้อมูล :attribute ต้องอยู่ในช่วงระหว่าง :min ถึง :max',
    'dimensions'           => 'ข้อมูล :attribute มีขนาดไม่ถูกต้อง.',
    'distinct'             => 'ข้อมูล :attribute มีค่าที่ซ้ำกัน',
    'email'                => 'ข้อมูล :attribute ต้องเป็นอีเมล์',
    'exists'               => 'ข้อมูล ที่ถูกเลือกจาก :attribute ไม่ถูกต้อง',
    'file'                 => 'ข้อมูล :attribute ต้องเป็นไฟล์.',
    'filled'               => 'ข้อมูล :attribute จำเป็นต้องกรอก',
    'image'                => 'ข้อมูล :attribute ต้องเป็นรูปภาพ',
    'in'                   => 'ข้อมูล ที่ถูกเลือกใน :attribute ไม่ถูกต้อง',
    'in_array'             => 'ข้อมูล :attribute ไม่มีอยู่ภายในค่าของ :other',
    'integer'              => 'ข้อมูล :attribute ต้องเป็นตัวเลข',
    'ip'                   => 'ข้อมูล :attribute ต้องเป็น IP',
    'ipv4'                 => 'ข้อมูล :attribute ต้องตรงตามรูปแบบ IPv4 address.',
    'ipv6'                 => 'ข้อมูล :attribute ต้องตรงตามรูปแบบ IPv6 address.',
    'json'                 => 'ข้อมูล :attribute ต้องเป็นอักขระ JSON ที่สมบูรณ์',
    'max'                  => [
        'numeric' => 'ข้อมูล :attribute ต้องมีค่าไม่เกิน :max.',
        'file'    => 'ข้อมูล :attribute ต้องมีขนาดไม่เกิน :max กิโลไบต์',
        'string'  => 'ข้อมูล :attribute ต้องมีความยาวตัวอักษรไม่เกิน :max ตัวอักษร',
        'array'   => 'ข้อมูล :attribute ต้องมีไม่เกิน :max ค่า',
    ],
    'mimes'                => 'ข้อมูล :attribute ต้องเป็นชนิดไฟล์: :values.',
    'mimetypes'            => 'ข้อมูล :attribute ต้องเป็นชนิดไฟล์: :values.',
    'min'                  => [
        'numeric' => 'ข้อมูล :attribute ต้องมีค่าอย่างน้อย :min.',
        'file'    => 'ข้อมูล :attribute ต้องมีขนาดอย่างน้อย :min กิโลไบต์',
        'string'  => 'ข้อมูล :attribute ต้องมีความยาวตัวอักษรอย่างน้อย :min ตัวอักษร',
        'array'   => 'ข้อมูล :attribute ต้องมีอย่างน้อย :min ค่า',
    ],
    'not_in'               => 'ข้อมูล ที่เลือกจาก :attribute ไม่ถูกต้อง',
    'numeric'              => 'ข้อมูล :attribute ต้องเป็นตัวเลข',
    'present'              => 'ข้อมูล :attribute ต้องเป็นปัจจุบัน',
    'regex'                => 'ข้อมูล :attribute มีรูปแบบไม่ถูกต้อง',
    'required'             => 'ข้อมูล :attribute จำเป็นต้องกรอก',
    'required_if'          => 'ข้อมูล :attribute จำเป็นต้องกรอกเมื่อ :other เป็น :value.',
    'required_unless'      => 'ข้อมูล :attribute จำเป็นต้องกรอกเว้นแต่ :other เป็น :values.',
    'required_with'        => 'ข้อมูล :attribute จำเป็นต้องกรอกเมื่อ :values มีค่า',
    'required_with_all'    => 'ข้อมูล :attribute จำเป็นต้องกรอกเมื่อ :values มีค่าทั้งหมด',
    'required_without'     => 'ข้อมูล :attribute จำเป็นต้องกรอกเมื่อ :values ไม่มีค่า',
    'required_without_all' => 'ข้อมูล :attribute จำเป็นต้องกรอกเมื่อ :values ไม่มีค่าทั้งหมด',
    'same'                 => 'ข้อมูล :attribute และ :other ต้องถูกต้อง',
    'size'                 => [
        'numeric' => 'ข้อมูล :attribute ต้องเท่ากับ :size',
        'file'    => 'ข้อมูล :attribute ต้องเท่ากับ :size กิโลไบต์',
        'string'  => 'ข้อมูล :attribute ต้องเท่ากับ :size ตัวอักษร',
        'array'   => 'ข้อมูล :attribute ต้องเท่ากับ :size ค่า',
    ],
    'string'               => 'ข้อมูล :attribute ต้องเป็นอักขระ',
    'timezone'             => 'ข้อมูล :attribute ต้องเป็นข้อมูลเขตเวลาที่ถูกต้อง',
    'unique'               => 'ข้อมูล :attribute ไม่สามารถใช้ได้',
    'unique_with'          => 'มีบัญชีที่ใช้ :fields เหล่านี้อยู่แล้ว',
    'uploaded'             => 'ข้อมูล :attribute ไม่สามารพอัพโหลดได้.',
    'url'                  => 'ข้อมูล :attribute ไม่ถูกต้อง',
    'custom'               => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],
    'attributes'           => [
        'email'          => 'อีเมล',
        'password'       => 'รหัสผ่าน',
        'school'         => 'ชื่อโรงเรียน',
        'studentYear'    => 'ชั้นปีที่ศึกษา',
        'schoolName'     => 'ชื่อโรงเรียน',
        'accountType'    => 'ประเภทบัญชี',
        'address'        => 'ที่อยู่โรงเรียน',
        'subject'        => 'สาขาวิชา',
        'phone'          => 'เบอร์โทรศัพท์',
        'website'        => 'เว็บไซต์',
        'position'       => 'ตำแหน่ง',
        'firstName'      => 'ชื่อ',
        'lastName'       => 'นามสกุล',
        'fullName'       => 'ชื่อ-นามสกุล',
        'year'           => 'ระดับชั้น',
        'prefix'         => 'คำนำหน้าชื่อ',
        'student_id'     => 'รหัสนักเรียน',
        'citizen_id'     => 'รหัสประจำตัวประชาชน',
        'file'           => 'เอกสาร',
        'id'             => 'รหัสประจำตัวนักเรียน',
        'class'          => 'ห้องเรียนปัจจุบัน',
        'number'         => 'เลขที่ในห้องเรียนปัจจุบัน',
        'name'           => 'ชื่อสินค้า',
        'author'         => 'ผู้ขายสินค้า',
        'type'           => 'ชนิดสินค้า',
        'price'          => 'ราคา',
        'detail'         => 'รายละเอียด',
        'detail.url'     => 'ลิงค์',
        'picture'        => 'รูปภาพ',
        'poster'         => 'โปสเตอร์',
        'book_example'   => 'ตัวอย่างหนังสือ',
        'book_type'      => 'หนังสือ',
        'book_subject'   => 'วิชา',
        'owner_detail_1' => 'ข้อมูลผู้ขาย 1',
        'owner_detail_2' => 'ข้อมูลผู้ขาย 2',
        'payment'        => 'การชำระเงิน',
    ],
    'date_equals'          => 'ข้อมูล :attribute ต้องเป็นวันที่ที่เท่ากับ :date',
    'gt'                   => [
        'numeric' => 'ข้อมูล :attribute ต้องมีค่ามากกว่า :value.',
        'file'    => 'ข้อมูล :attribute ต้องมีขนาดมากกว่า :value กิโลไบต์.',
        'string'  => 'ข้อมูล :attribute ต้องมีความยาวตัวอักษรมากกว่า :value ตัวอักษร.',
        'array'   => 'ข้อมูล :attribute ต้องมีมากกว่า :value ค่า.',
    ],
    'gte'                  => [
        'numeric' => 'ข้อมูล :attribute ต้องมีค่ามากกว่าหรือเท่ากับ :value.',
        'file'    => 'ข้อมูล :attribute ต้องมีขนาดมากกว่าหรือเท่ากับ :value กิโลไบต์.',
        'string'  => 'ข้อมูล :attribute ต้องมีความยาวตัวอักษรมากกว่าหรือเท่ากับ :value ตัวอักษร.',
        'array'   => 'ข้อมูล :attribute ต้องมี :value ค่า หรือมากกว่า.',
    ],
    'lt'                   => [
        'numeric' => 'ข้อมูล :attribute ต้องมีค่าน้อยกว่า :value.',
        'file'    => 'ข้อมูล :attribute ต้องมีขนาดน้อยกว่า :value กิโลไบต์.',
        'string'  => 'ข้อมูล :attribute ต้องมีความยาวตัวอักษรน้อยกว่า :value ตัวอักษร.',
        'array'   => 'ข้อมูล :attribute ต้องมีน้อยกว่า :value ค่า.',
    ],
    'lte'                  => [
        'numeric' => 'ข้อมูล :attribute ต้องมีค่าน้อยกว่าหรือเท่ากับ :value.',
        'file'    => 'ข้อมูล :attribute ต้องมีขนาดน้อยกว่าหรือเท่ากับ :value กิโลไบต์.',
        'string'  => 'ข้อมูล :attribute ต้องมีความยาวตัวอักษรน้อยกว่าหรือเท่ากับ :value ตัวอักษร.',
        'array'   => 'ข้อมูล :attribute ต้องมีไม่เกิน :value ค่า.',
    ],
    'not_regex'            => 'ข้อมูล :attribute มีรูปแบบไม่ถูกต้อง.',
    'starts_with'          => 'ข้อมูล :attribute ต้องเริ่มด้วยค่าใดค่าหนึ่งต่อไปนี้: :values',
    'uuid'                 => 'ข้อมูล :attribute ต้องเป็นค่า UUID ที่ถูกต้อง',
    'ends_with'            => 'The :attribute must end with one of the following: :values.',
    'multiple_of'          => 'The :attribute must be a multiple of :value',
    'password'             => 'The password is incorrect.',
];

@extends('layouts.master')

@section('style')
    h1 {font-size:3rem}
    .fullwidth {width:99%}
    h1 .smtext {font-size:40%}
    body {background-color: #616161}
    nav {box-shadow:none}
@endsection

@section('main')
    <div class="z-depth-1 card-panel" style="max-width:800px;margin: 1rem auto auto;">
        <div class="row">
            <div class="col s12 m3 center-align">
                <br />
                <i class="large material-icons green-text">beenhere</i><br/>
            </div>
            <div class="col s12 m9">
                <h4>ขอขอบคุณที่ให้ความสนใจ</h4>
                <iframe src="https://www.facebook.com/plugins/post.php?href=https%3A%2F%2Fwww.facebook.com%2FTriamUdomOPH%2Fposts%2F565968996941246%3A0&width=500" height="570" style="border:none;overflow:hidden;width:100%" scrolling="no" frameborder="0" allowTransparency="true"></iframe>
                {{--<p>
                    &ensp;ทางคณะผู้จัดงาน ขอขอบพระคุณทุกท่านที่ให้ความสนใจ และเข้าร่วมกิจกรรม "๗๙ ปีเตรียมอุดมศึกษา นิทรรศการวิชาการ ตามพระราชปณิธานรัชกาลที่ ๙" ในครั้งนี้
                    ทางคณะผู้จัดงานหวังเป็นอย่างยิ่งว่า ทุกท่านจะได้รับความสุข และรู้จักโรงเรียนเตรียมอุดมศึกษาในมุมมองต่างๆมากยิ่งขึ้น!<br /><br />
                    &ensp;แล้วพบกันใหม่ปีหน้า #TriamUdomOpenHouse2017
                </p>
                <sub>คณะกรรมการบริหารงานกิจกรรมพัฒนาผู้เรียน</sub>--}}
            </div>
        </div>
    </div>
@endsection
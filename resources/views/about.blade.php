<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Về chúng tôi - Leviosa Resort</title>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/about.css') }}">
</head>

<body>

    {{-- HEADER --}}
    @include('layouts.header')

    {{-- HERO --}}
    <section class="about-hero">
        <div class="overlay"></div>
        <div class="hero-content">
            <h1>Leviosa Resort</h1>
            <p>
                Nơi giao thoa giữa thiên nhiên thuần khiết,<br>
                kiến trúc cổ điển và hơi thở hiện đại.
            </p>
        </div>
    </section>

    {{-- HEADER 2 --}}
    <section class="section-title">
        <h2>Câu chuyện Leviosa</h2>
    </section>

    {{-- SECTION + --}}
    <section class="about-plus">
        <div class="plus-left">
            <div class="plus-left-overlay"></div>

            <div class="plus-left-content">
                <h3>Tinh hoa nghỉ dưỡng</h3>
                <p>
                    Leviosa được kiến tạo như một khu nghỉ dưỡng sinh thái cao cấp,
                    nơi mỗi chi tiết đều phản ánh sự hài hòa giữa con người và thiên nhiên.
                </p>
                <a href="{{ route('home') }}" class="btn-primary">Khám phá thêm</a>
            </div>
        </div>

        <div class="plus-right">
            <div class="plus-grid">
                <div class="plus-box top-left">Thiên nhiên</div>
                <div class="plus-box top-right">Cổ điển</div>
                <div class="plus-box bottom-left">Hiện đại</div>
                <div class="plus-box bottom-right">Tinh tế</div>
            </div>
        </div>
    </section>


    {{-- TEXT + IMAGE --}}
    <section class="about-split">
        <div class="split-text">
            <h3>Không gian truyền cảm hứng</h3>
            <p>
                Mỗi căn phòng, mỗi khu vườn tại Leviosa
                đều được thiết kế để mang lại cảm giác bình yên,
                thư thái và riêng tư tuyệt đối.
            </p>
        </div>

        <div class="split-image">
            <div class="image-overlay">
                <p>
                    Leviosa – nơi thời gian chậm lại<br>
                    để bạn tận hưởng trọn vẹn từng khoảnh khắc.
                </p>
            </div>
        </div>
    </section>

    {{-- GALLERY --}}
    <section class="section-title">
        <h2>Không gian & Trải nghiệm</h2>
    </section>

    <section class="about-gallery">
        <img src="{{ asset('assets/images/about1.jpg') }}">
        <img src="{{ asset('assets/images/about2.png') }}">
        <img src="{{ asset('assets/images/about3.png') }}">
        <img src="{{ asset('assets/images/about4.jpg') }}">
    </section>

    {{-- FOOTER --}}
    @include('layouts.footer')

</body>

</html>
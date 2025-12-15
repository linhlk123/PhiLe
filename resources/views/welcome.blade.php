<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Welcome - Leviosa Resort</title>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/welcome.css') }}">
</head>

<body>

    <section class="welcome-hero">
        <div class="welcome-overlay"></div>

        <div class="welcome-content">
            <h1>Chào mừng bạn đến với<br>Leviosa Resort</h1>

            <p>
                Không gian nghỉ dưỡng đậm nét di sản, nơi thiên nhiên,
                văn hóa và kiến trúc Việt Nam hòa quyện tạo nên trải nghiệm
                tinh tế và thanh bình.
            </p>

            <a href="{{ route('home') }}" class="welcome-btn">
                Khám phá ngay
            </a>
        </div>
    </section>

</body>

</html>
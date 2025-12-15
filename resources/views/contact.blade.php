<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Liên hệ - Leviosa Resort</title>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/contact.css') }}">

    <!-- Icon -->
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
</head>

<body>

    {{-- HEADER --}}
    @include('layouts.header')

    {{-- CONTACT SECTION --}}
    <section class="contact-section">

        {{-- LOGO --}}
        <div class="contact-logo">
            <img src="{{ asset('assets/images/LEVIOSA_logo.png') }}" alt="Legacy Hoi An Resort">
        </div>

        {{-- TITLE --}}
        <h3>Liên hệ với Leviosa Resort</h3>

        <p class="contact-desc">
            Chúng tôi luôn sẵn sàng lắng nghe và đồng hành cùng bạn
            trên hành trình nghỉ dưỡng giữa thiên nhiên thanh bình.
        </p>

        {{-- INFO --}}
        <div class="contact-info">
            <p><strong>LEVIOSA RESORT</strong></p>
            <p>Khu phố 6, phường Linh Trung, Thủ Đức, TP. Hồ Chí Minh</p>
            <p>Email: <a href="mailto:LeviosaResort@gmail.com">LeviosaResort@gmail.com</a></p>
            <p>Tel: <a href="tel:+84123456789">+84 123 456 789</a></p>
        </div>

        {{-- SOCIAL --}}
        <div class="contact-social">
            <a href="#"><i class="fab fa-facebook-f"></i> Facebook</a>
            <a href="#"><i class="fab fa-instagram"></i> Instagram</a>
            <a href="#"><i class="fab fa-youtube"></i> YouTube</a>
            <a href="#"><i class="fab fa-tiktok"></i> TikTok</a>
            <a href="#"><i class="fab fa-linkedin-in"></i> LinkedIn</a>
        </div>

        {{-- MAP --}}
        <div class="contact-map">
            <iframe
                src="https://www.google.com/maps?q=Linh%20Trung%20Thu%20Duc%20Ho%20Chi%20Minh&output=embed"
                loading="lazy">
            </iframe>
        </div>

    </section>

    {{-- FOOTER --}}
    @include('layouts.footer')

</body>

</html>
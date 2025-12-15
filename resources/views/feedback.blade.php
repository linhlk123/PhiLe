<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Phản ánh ý kiến - Leviosa Resort</title>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/feedback.css') }}">
</head>

<body>

    {{-- HEADER --}}
    @include('layouts.header')

    {{-- FEEDBACK SECTION --}}
    <section class="feedback-section">
        <img src="{{ asset('assets/images/feedback.png') }}" class="feedback-bg" alt="Feedback Background">


        <div class="feedback-overlay"></div>

        <div class="feedback-container">

            {{-- LEFT CONTENT --}}
            <div class="feedback-left">
                <h2>Chúng tôi rất muốn lắng nghe<br>lời nhắn từ bạn.</h2>

                <p class="contact-item">
                    <strong>T.</strong> +84 (0) 123 456 789
                </p>
                <p class="contact-item">
                    <strong>E.</strong> LeviosaResort@gmail.com
                </p>
            </div>

            {{-- RIGHT FORM --}}
            <div class="feedback-right">
                <form action="{{ route('feedback.send') }}" method="POST" class="feedback-form">
                    @csrf

                    <label>Tên của bạn*</label>
                    <input type="text" name="full_name" required>

                    <label>Email*</label>
                    <input type="email" name="email" required>

                    <label>Số điện thoại*</label>
                    <input type="text" name="phone" required>

                    <label>Nội dung</label>
                    <textarea name="message" rows="4"></textarea>

                    <button type="submit">Gửi</button>
                </form>
            </div>

        </div>
    </section>

    {{-- FOOTER --}}
    @include('layouts.footer')

</body>

</html>
<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leviosa Resort - Khu Nghỉ Dưỡng Việt Nam</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <header class="header">
        <div class="header-top">
            <div class="container">
                <button class="menu-toggle">
                    <i class="fas fa-bars"></i>
                </button>

                <div class="logo">
                    <img src="{{ asset('assets/images/LEVIOSA_logo.png') }}" alt="Legacy Hoi An Resort">
                </div>

                <div class="header-actions">
                    <div class="language-selector">
                        <img src="{{ asset('assets/images/index/Flag_of_Vietnam.svg.webp') }}" alt="Vietnamese">
                        <i class="fas fa-chevron-down"></i>
                    </div>
                    @auth('customer')
                    <div class="customer-welcome">
                        <span>Xin chào, {{ Auth::guard('customer')->user()->FullName }}!</span>
                        <form action="{{ route('customer.logout') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn-logout">Đăng xuất</button>
                        </form>
                    </div>
                    @else
                    <a href="{{ route('login') }}" class="btn-auth">Đăng nhập/Đăng ký</a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- MENU CHÍNH -->
    <div class="nav-wrapper">
        <div class="main-nav">
            <nav class="nav-menu">
                <button class="menu-close">&times;</button>

                <ul class="nav-list">
                    <li><a href="{{ route('home') }}">Trang chủ</a></li>
                    <li class="nav-item dropdown" data-target="phongnghi"><a href="{{ route('booking') }}">Phòng nghỉ</a></li>
                    <li class="nav-item dropdown" data-target="dichvu"><a href="{{ route('services') }}">Dịch vụ</a></li>
                    <li><a href="{{ route('welcome') }}">Welcome</a></li>
                    <li><a href="{{ route('about') }}">Về chúng tôi</a></li>
                    <li><a href="{{ route('contact') }}">Liên hệ</a></li>
                    <li><a href="{{ route('policy') }}">Điều khoản & Chính sách</a></li>
                    <li><a href="{{ route('feedback') }}">Phản ánh ý kiến</a></li>
                    {{-- CHỈ HIỆN KHI KHÁCH ĐÃ ĐĂNG NHẬP --}}
                    @auth('customer')
                    <li>
                        <a href="{{ route('customer.profile') }}">Thông tin cá nhân</a>
                    </li>
                    <li>
                        <a href="{{ route('customer.payments') }}">Hóa đơn</a>
                    </li>
                    @endauth
                </ul>
            </nav>
        </div>
    </div>

    <script>
        const menuToggle = document.querySelector('.menu-toggle');
        const navWrapper = document.querySelector('.nav-wrapper');
        const menuClose = document.querySelector('.menu-close');
        const body = document.body;

        // Mở / đóng menu
        menuToggle.addEventListener('click', () => {
            navWrapper.classList.add('active');
            body.style.overflow = 'hidden';
        });

        // Đóng menu
        function closeMenu() {
            navWrapper.classList.remove('active');
            body.style.overflow = '';
        }

        menuClose.addEventListener('click', closeMenu);

        // Click overlay để đóng
        navWrapper.addEventListener('click', (e) => {
            if (!e.target.closest('.nav-menu')) {
                closeMenu();
            }
        });

        window.addEventListener('scroll', () => {
            navWrapper.style.display = 'none';
            navWrapper.offsetHeight;
            navWrapper.style.display = '';
        });
    </script>
</body>
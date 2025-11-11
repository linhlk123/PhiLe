<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Legacy Hoi An Resort - Khu Nghỉ Dưỡng Hội An</title>
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
                <a href="{{ route('customer.login') }}" class="btn-auth">Đăng nhập/Đăng ký</a>
            @endauth
        </div>
        </div>

        <!-- MENU CHÍNH -->
        <div class="nav-wrapper">
        <div class="main-nav">
            <nav class="nav-menu">
            <button class="menu-close">&times;</button>

            <ul class="nav-list">
                <li class="nav-item dropdown" data-target="phongnghi"><a href="#">Phòng nghỉ</a></li>
                <li class="nav-item dropdown" data-target="amthuc"><a href="#">Ẩm thực</a></li>
                <li><a href="#">Coco Spa</a></li>
                <li><a href="#">Tiện ích</a></li>
                <li><a href="#">Dịch vụ</a></li>
                <li><a href="#">Hội nghị & Sự kiện</a></li>
                <li><a href="#">Ưu đãi</a></li>
                <li><a href="#">Điểm đến</a></li>
                <li><a href="#">Thư viện</a></li>
                <li><a href="#">Liên hệ</a></li>
            </ul>
            </nav>
        </div>
        </div>
    </div>
    </header>
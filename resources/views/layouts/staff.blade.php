<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản lý - Leviosa Resort')</title>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/staff.new.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

    @yield('styles')
</head>

<body>
    <header class="staff-header">
        <h1>@yield('page-title', 'Quản lý')</h1>
        <div class="staff-meta">
            <div class="user-dropdown">
                <button class="dropdown-toggle">
                    Xin chào, <strong>{{ Auth::guard('staff')->user()->name ?? 'Nhân viên' }}</strong>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="dropdown-menu">
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-user"></i> Thông tin cá nhân
                    </a>
                    <form action="{{ route('staff.staff.logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="dropdown-item" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div style="display: flex; gap: 20px;">
        <!-- Danh mục panel -->
        <div style="width: 270px; background: white; border-radius: 10px; padding: 20px; margin: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: fit-content;">
            <h3 style="margin-top: 0; color: #1a237e; border-bottom: 2px solid #1a237e; padding-bottom: 10px;">Danh mục</h3>
            <ul style="list-style: none; padding: 0;">
                <li style="margin: 10px 0;">
                    <a href="{{ route('staff.staff-room') }}" style="text-decoration: none; color: #1d5a2e; display: block; padding: 8px 12px; border-radius: 5px; background: rgba(29, 90, 46, 0.1); transition: all 0.3s;">
                        <i class="fas fa-bed" style="margin-right: 8px;"></i>Quản lý phòng
                    </a>
                </li>
                <li style="margin: 10px 0;">
                    <a href="{{ route('staff.booking') }}" style="text-decoration: none; color: #455a64; display: block; padding: 8px 12px; border-radius: 5px; transition: all 0.3s;">
                        <i class="fas fa-calendar-check" style="margin-right: 8px;"></i>Quản lý đặt phòng
                    </a>
                </li>
                <li style="margin: 10px 0;">
                    <a href="{{ route('staff.customer') }}" style="text-decoration: none; color: #455a64; display: block; padding: 8px 12px; border-radius: 5px; transition: all 0.3s;">
                        <i class="fas fa-user-friends" style="margin-right: 8px;"></i>Quản lý khách hàng
                    </a>
                </li>
                <li style="margin: 10px 0;">
                    <a href="{{ route('staff.employee') }}" style="text-decoration: none; color: #455a64; display: block; padding: 8px 12px; border-radius: 5px; transition: all 0.3s;">
                        <i class="fas fa-users" style="margin-right: 8px;"></i>Quản lý nhân viên
                    </a>
                </li>
                <li style="margin: 10px 0;">
                    <a href="{{ route('staff.service') }}" style="text-decoration: none; color: #455a64; display: block; padding: 8px 12px; border-radius: 5px; transition: all 0.3s;">
                        <i class="fas fa-concierge-bell" style="margin-right: 8px;"></i>Quản lý dịch vụ
                    </a>
                </li>
                <li style="margin: 10px 0;">
                    <a href="{{ route('staff.invoice') }}" style="text-decoration: none; color: #455a64; display: block; padding: 8px 12px; border-radius: 5px; transition: all 0.3s;">
                        <i class="fas fa-file-invoice-dollar" style="margin-right: 8px;"></i>Quản lý hóa đơn
                    </a>
                </li>
                <li style="margin: 10px 0;">
                    <a href="{{ route('staff.profile') }}" style="text-decoration: none; color: #455a64; display: block; padding: 8px 12px; border-radius: 5px; transition: all 0.3s;">
                        <i class="fas fa-id-card" style="margin-right: 8px;"></i>Thông tin cá nhân
                    </a>
                </li>
                <li style="margin: 10px 0;">
                    <a href="{{ route('welcome') }}" style="text-decoration: none; color: #455a64; display: block; padding: 8px 12px; border-radius: 5px; transition: all 0.3s;">
                        <i class="fas fa-id-card" style="margin-right: 8px;"></i>Welcome
                    </a>
                </li>
                <li style="margin: 10px 0;">
                    <a href="{{ route('policy') }}" style="text-decoration: none; color: #455a64; display: block; padding: 8px 12px; border-radius: 5px; transition: all 0.3s;">
                        <i class="fas fa-id-card" style="margin-right: 8px;"></i>Điều khoản & chính sách
                    </a>
                </li>
                <li style="margin: 10px 0;">
                    <a href="{{ route('feedback') }}" style="text-decoration: none; color: #455a64; display: block; padding: 8px 12px; border-radius: 5px; transition: all 0.3s;">
                        <i class="fas fa-id-card" style="margin-right: 8px;"></i>Phản ánh ý kiến
                    </a>
                </li>
            </ul>
        </div>

        <!-- Main content -->
        <div style="flex: 1;">
            @yield('content')
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @yield('scripts')
</body>

</html>
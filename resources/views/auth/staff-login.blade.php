<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Quản lý - Legacy Hoi An Resort')</title>
    
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/staff.new.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    @yield('styles')
    <style>
        .auth-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: #f6f8f2;
            padding: 20px;
        }

        .auth-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }

        .auth-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .auth-header i {
            font-size: 48px;
            color: #184f2b;
            margin-bottom: 15px;
        }

        .auth-header h1 {
            color: #184f2b;
            font-size: 24px;
            margin: 0;
        }

        .auth-header p {
            color: #666;
            margin-top: 10px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #184f2b;
            font-weight: 600;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
            transition: border-color 0.3s ease;
        }

        .form-group input:focus {
            border-color: #184f2b;
            outline: none;
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background: #184f2b;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s ease;
        }

        .btn-submit:hover {
            background: #1d5a2e;
        }

        .return-links {
            text-align: center;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .return-links a {
            color: #666;
            text-decoration: none;
            font-size: 14px;
            display: inline-block;
            margin: 0 10px;
        }

        .return-links a:hover {
            color: #184f2b;
            text-decoration: underline;
        }

        .return-home {
            position: absolute;
            top: 20px;
            left: 20px;
            color: #184f2b;
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .return-home:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <a href="{{ route('home') }}" class="return-home">
        <i class="fas fa-arrow-left"></i>
        Trở về trang chủ
    </a>
    <div class="auth-container">
        <div class="auth-box">
            <div class="auth-header">
                <i class="fas fa-user-tie"></i>
                <h1>Đăng nhập nhân viên</h1>
                <p>Vui lòng đăng nhập để truy cập hệ thống</p>
            </div>
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('staff.login') }}" class="auth-form">
                @csrf

                <div class="form-group">
                    <label for="Email">Email</label>
                    <input type="email" id="Email" name="Email" value="{{ old('Email') }}" required>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group remember-me">
                    <label>
                        <input type="checkbox" name="remember"> Ghi nhớ đăng nhập
                    </label>
                </div>

                <button type="submit" class="btn-submit">Đăng nhập</button>
                <div class="return-links">
                    <a href="{{ route('customer.login') }}">
                        <i class="fas fa-user"></i> Đăng nhập khách hàng
                    </a>
                    <a href="#">
                        <i class="fas fa-user-shield"></i> Đăng nhập quản lý
                    </a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Legacy Hoi An Resort</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h2>Đăng nhập</h2>
            
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('customer.login') }}" class="auth-form">
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

                <div class="auth-links">
                    <a href="{{ route('customer.register') }}">Chưa có tài khoản? Đăng ký</a>
                    <a href="{{ route('password.request') }}">Quên mật khẩu?</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
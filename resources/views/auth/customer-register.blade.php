<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng ký - Legacy Hoi An Resort</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="auth-container">
        <div class="auth-box">
            <h2>Đăng ký tài khoản</h2>
            
            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <form method="POST" action="{{ route('customer.register') }}" class="auth-form">
                @csrf
                
                <div class="form-group">
                    <label for="FullName">Họ và tên</label>
                    <input type="text" id="FullName" name="FullName" value="{{ old('FullName') }}" required>
                </div>

                <div class="form-group">
                    <label for="Gender">Giới tính</label>
                    <select id="Gender" name="Gender" required>
                        <option value="">Chọn giới tính</option>
                        <option value="Nam" {{ old('Gender') == 'Nam' ? 'selected' : '' }}>Nam</option>
                        <option value="Nữ" {{ old('Gender') == 'Nữ' ? 'selected' : '' }}>Nữ</option>
                        <option value="Khác" {{ old('Gender') == 'Khác' ? 'selected' : '' }}>Khác</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="Phone">Số điện thoại</label>
                    <input type="tel" id="Phone" name="Phone" value="{{ old('Phone') }}" required>
                </div>

                <div class="form-group">
                    <label for="Email">Email</label>
                    <input type="email" id="Email" name="Email" value="{{ old('Email') }}" required>
                </div>

                <div class="form-group">
                    <label for="IDNumber">Số CCCD/CMND</label>
                    <input type="text" id="IDNumber" name="IDNumber" value="{{ old('IDNumber') }}" required>
                </div>

                <div class="form-group">
                    <label for="Address">Địa chỉ</label>
                    <textarea id="Address" name="Address" required>{{ old('Address') }}</textarea>
                </div>

                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Xác nhận mật khẩu</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" required>
                </div>

                <button type="submit" class="btn-submit">Đăng ký</button>

                <div class="auth-links">
                    <a href="{{ route('customer.login') }}">Đã có tài khoản? Đăng nhập</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
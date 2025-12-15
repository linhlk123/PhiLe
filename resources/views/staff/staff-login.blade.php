@extends('layouts.auth')

@section('title', 'Đăng nhập nhân viên - Leviosa Resort')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
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
@endsection

@section('content')
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

        <form action="{{ route('staff.login') }}" method="POST">
            @csrf

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            <div class="form-group">
                <label for="staffId">Mã nhân viên</label>
                <input type="text" id="staffId" name="staffId" value="{{ old('staffId') }}" required>
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="btn-submit">Đăng nhập</button>


            <div class="return-links">
                <a href="#">
                    <i class="fas fa-user"></i> Đăng nhập khách hàng
                </a>
                <a href="#">
                    <i class="fas fa-user-shield"></i> Đăng nhập quản lý
                </a>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.querySelector('form').addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const data = {};
        formData.forEach((value, key) => data[key] = value);

        fetch('{{ route('
                staff.login ') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify(data),
                    credentials: 'include'
                })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href = data.redirect || '{{ route('
                    staff.room ') }}';
                } else {
                    throw new Error(data.message || 'Sai mã nhân viên hoặc mật khẩu');
                }
            })
            .catch(error => {
                alert(error.message || 'Có lỗi xảy ra khi đăng nhập. Vui lòng thử lại.');
            });
    });
</script>
@endsection
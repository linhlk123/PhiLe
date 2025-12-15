<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Đăng nhập - Khách hàng</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <style>
        :root {
            --primary-color: #184f2b;
            --primary-hover: #1d5a2e;
            --secondary-color: #f6f8f2;
            --text-color: #2c3e50;
            --border-color: #e1e8ed;
            --shadow-color: rgba(0,0,0,0.1);
            --transition: all 0.3s ease;
        }

        body {
            font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
            color: var(--text-color);
            background: linear-gradient(135deg, #f6f8f2 0%, #ffffff 100%);
        }

        .auth-container {
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background: linear-gradient(135deg, rgba(24,79,43,0.05) 0%, rgba(24,79,43,0.1) 100%);
        }

        .auth-box {
            background: white;
            padding: 40px;
            border-radius: 20px;
            box-shadow: 0 10px 30px var(--shadow-color);
            width: 100%;
            max-width: 450px;
            transform: translateY(0);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .auth-box:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 35px rgba(0,0,0,0.15);
        }

        .auth-tabs {
            display: flex;
            margin-bottom: 40px;
            border-bottom: 2px solid var(--border-color);
            position: relative;
        }

        .auth-tab {
            flex: 1;
            padding: 15px 5px;
            text-align: center;
            cursor: pointer;
            color: var(--text-color);
            font-weight: 600;
            font-size: 1.1em;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .auth-tab::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 2px;
            background: var(--primary-color);
            transform: scaleX(0);
            transition: var(--transition);
        }

        .auth-tab.active {
            color: var(--primary-color);
        }

        .auth-tab.active::after {
            transform: scaleX(1);
        }

        .auth-form {
            display: none;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .auth-form.active {
            display: block;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: var(--text-color);
            font-weight: 500;
            font-size: 0.95em;
            transition: var(--transition);
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 16px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            font-size: 1em;
            transition: var(--transition);
            background: white;
            color: var(--text-color);
        }

        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 4px rgba(24,79,43,0.1);
            outline: none;
        }

        .form-group input:focus + label,
        .form-group select:focus + label,
        .form-group textarea:focus + label {
            color: var(--primary-color);
        }

        .btn-submit {
            width: 100%;
            padding: 15px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1em;
            font-weight: 600;
            cursor: pointer;
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .btn-submit:hover {
            background: var(--primary-hover);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(24,79,43,0.3);
        }

        .btn-submit:active {
            transform: translateY(0);
        }

        .forgot-password {
            text-align: center;
            margin-top: 25px;
        }

        .forgot-password a {
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 500;
            transition: var(--transition);
            position: relative;
        }

        .forgot-password a::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 1px;
            background: var(--primary-color);
            transform: scaleX(0);
            transition: var(--transition);
        }

        .forgot-password a:hover::after {
            transform: scaleX(1);
        }

        .social-login {
            margin-top: 35px;
            text-align: center;
            position: relative;
        }

        .social-login p {
            margin-bottom: 20px;
            color: #666;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .social-login p::before,
        .social-login p::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--border-color);
            margin: 0 15px;
        }

        .social-buttons {
            display: flex;
            gap: 15px;
        }

        .btn-social {
            flex: 1;
            padding: 12px;
            border: 2px solid var(--border-color);
            border-radius: 10px;
            background: white;
            cursor: pointer;
            transition: var(--transition);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-weight: 500;
        }

        .btn-social:hover {
            background: #f8f9fa;
            border-color: var(--primary-color);
            transform: translateY(-2px);
        }

        .btn-social img {
            width: 24px;
            height: 24px;
            transition: var(--transition);
        }

        .btn-social:hover img {
            transform: scale(1.1);
        }

        .return-home {
            position: absolute;
            top: 30px;
            left: 30px;
            color: var(--primary-color);
            text-decoration: none;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 8px;
            transition: var(--transition);
            padding: 10px 20px;
            border-radius: 30px;
            background: white;
            box-shadow: 0 2px 10px var(--shadow-color);
        }

        .return-home:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px var(--shadow-color);
        }

        .return-home i {
            transition: var(--transition);
        }

        .return-home:hover i {
            transform: translateX(-3px);
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .auth-box {
                padding: 30px 20px;
            }

            .return-home {
                top: 20px;
                left: 20px;
                padding: 8px 15px;
                font-size: 0.9em;
            }
        }

        @media (max-width: 480px) {
            .social-buttons {
                flex-direction: column;
            }

            .btn-social {
                width: 100%;
            }
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
            <div class="auth-tabs">
                <div class="auth-tab active" onclick="switchTab('signin')">Đăng nhập</div>
                <div class="auth-tab" onclick="switchTab('signup')">Đăng ký</div>
            </div>

            <!-- Form đăng nhập -->
            <form class="auth-form active" id="signin-form" action="{{ route('login') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="login-email">Email</label>
                    <input type="email" id="login-email" name="Email" required>
                </div>
                <div class="form-group">
                    <label for="login-password">Mật khẩu</label>
                    <input type="password" id="login-password" name="password" required>
                </div>
                <button type="submit" class="btn-submit">Đăng nhập</button>
                <div class="forgot-password">
                    <a href="#">Quên mật khẩu?</a>
                </div>
                <div class="social-login">
                    <p>Hoặc đăng nhập với</p>
                    <div class="social-buttons">
                        <button type="button" class="btn-social">
                            <img src="{{ asset('assets/images/icons/google.png') }}" alt="Google">
                            Google
                        </button>
                        <button type="button" class="btn-social">
                            <img src="{{ asset('assets/images/icons/facebook.png') }}" alt="Facebook">
                            Facebook
                        </button>
                    </div>
                </div>
                <div class="staff-login" style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                    <p style="text-align: center; color: #666; margin-bottom: 15px;">Đăng nhập với vai trò khác</p>
                    <div style="display: flex; gap: 10px;">
                        <a href="{{ route('staff.login') }}" class="btn-social" style="text-decoration: none; color: #333; font-weight: 500;">
                            <i class="fas fa-user-tie" style="color: #184f2b;"></i>
                            Nhân viên
                        </a>
                        <a href="#" class="btn-social" style="text-decoration: none; color: #333; font-weight: 500;">
                            <i class="fas fa-user-shield" style="color: #184f2b;"></i>
                            Quản lý
                        </a>
                    </div>
                </div>

            </form>

            <!-- Form đăng ký -->
            <form class="auth-form" id="signup-form" action="{{ route('customer.register') }}" method="POST">
                @csrf
                <div class="form-group">
                    <label for="FullName">Họ tên</label>
                    <input type="text" id="FullName" name="FullName" required>
                </div>
                <div class="form-group">
                    <label for="Gender">Giới tính</label>
                    <select id="Gender" name="Gender" required>
                        <option value="">Chọn giới tính</option>
                        <option value="Nam">Nam</option>
                        <option value="Nữ">Nữ</option>
                        <option value="Khác">Khác</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="Email">Email</label>
                    <input type="email" id="Email" name="Email" required>
                </div>
                <div class="form-group">
                    <label for="Phone">Số điện thoại</label>
                    <input type="tel" id="Phone" name="Phone" required>
                </div>
                <div class="form-group">
                    <label for="IDNumber">Số CCCD/CMND</label>
                    <input type="text" id="IDNumber" name="IDNumber" required>
                </div>
                <div class="form-group">
                    <label for="Address">Địa chỉ</label>
                    <textarea id="Address" name="Address" required></textarea>
                </div>
                <div class="form-group">
                    <label for="password">Mật khẩu</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <button type="submit" class="btn-submit">Đăng ký</button>
            </form>
        </div>
    </div>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script>
        function switchTab(tab) {
            // Remove active class from all tabs and forms
            document.querySelectorAll('.auth-tab').forEach(t => t.classList.remove('active'));
            document.querySelectorAll('.auth-form').forEach(f => f.classList.remove('active'));
            
            // Add active class to selected tab and form
            if (tab === 'signin') {
                document.querySelector('.auth-tab:first-child').classList.add('active');
                document.getElementById('signin-form').classList.add('active');
            } else {
                document.querySelector('.auth-tab:last-child').classList.add('active');
                document.getElementById('signup-form').classList.add('active');
            }
        }

        // Xử lý form đăng nhập
        document.getElementById('signin-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const data = {};
            formData.forEach((value, key) => data[key] = value);
            
            // Log dữ liệu trước khi gửi
            console.log('Sending login data:', data);
            
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(data)
            })
            .then(response => {
                if (response.ok) {
                    return response.json();
                }
                throw new Error('Network response was not ok');
            })
            .then(data => {
                if (data.success) {
                    // Nếu đăng nhập thành công, chuyển hướng về trang chủ
                    window.location.href = '/';
                } else {
                    // Nếu có lỗi, hiển thị thông báo
                    alert(data.message || 'Email hoặc mật khẩu không chính xác');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi đăng nhập. Vui lòng thử lại sau.');
            });
        });

        // Xử lý form đăng ký
        document.getElementById('signup-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Chuyển form data thành object
            const formData = new FormData(this);
            const data = {};
            formData.forEach((value, key) => data[key] = value);
            
            // Lấy CSRF token từ meta tag
            const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            
            // Gửi form bằng AJAX đến Laravel server
            fetch(this.action, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': token
                },
                body: JSON.stringify(data)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Hiển thị thông báo thành công
                    alert(data.message);
                    
                    // Chuyển sang tab đăng nhập
                    switchTab('signin');
                    
                    // Reset form đăng ký
                    this.reset();
                } else {
                    // Hiển thị lỗi từ server
                    if (data.errors) {
                        const errorMessages = Object.values(data.errors).flat().join('\n');
                        alert('Lỗi đăng ký:\n' + errorMessages);
                    } else {
                        alert(data.message || 'Có lỗi xảy ra khi đăng ký');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi kết nối đến server. Vui lòng thử lại sau.');
            });
        });

    </script>
</body>
</html>
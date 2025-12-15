<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Thông tin cá nhân - Leviosa Resort</title>
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/staff.new.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .profile-container {
            background: white;
            border-radius: 10px;
            padding: 20px;
            margin: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .profile-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 30px;
        }

        .profile-avatar {
            width: 120px;
            height: 120px;
            border-radius: 60px;
            background: #e0e0e0;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 48px;
            color: #9e9e9e;
        }

        .profile-info h2 {
            margin: 0;
            color: #1d5a2e;
        }

        .profile-info p {
            margin: 5px 0;
            color: #666;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #455a64;
            font-weight: 500;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: 500;
        }

        .btn-primary {
            background: #1d5a2e;
            color: white;
        }

        .btn-secondary {
            background: #e0e0e0;
            color: #333;
        }

        .profile-actions {
            margin-top: 30px;
            display: flex;
            gap: 10px;
        }

        .profile-container {
            margin-top: 90px;
        }


        nav.top-nav {
            background: #1d5a2e;
            padding: 10px 20px;
            margin-bottom: 20px;
        }

        nav.top-nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            gap: 20px;
        }

        nav.top-nav ul li a {
            color: white;
            text-decoration: none;
            padding: 5px 10px;
            border-radius: 5px;
            transition: background 0.3s;
        }

        nav.top-nav ul li a:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        /* Dropdown styles */
        .user-dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-toggle {
            background: none;
            border: none;
            color: white;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            padding: 5px;
        }

        .dropdown-menu {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            background: white;
            border-radius: 5px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            width: 150px;
            z-index: 1000;
        }

        .dropdown-item {
            display: block;
            padding: 10px 15px;
            color: #333;
            text-decoration: none;
            transition: background 0.2s;
        }

        .dropdown-item:hover {
            background: #f5f5f5;
        }

        .dropdown-item i {
            margin-right: 8px;
            color: #666;
        }

        .change-password-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1000;
        }

        .modal-content {
            position: relative;
            background: white;
            width: 90%;
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
        }

        .modal-close {
            position: absolute;
            right: 15px;
            top: 15px;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }

        .modal-header h3 {
            margin-top: 0;
            color: #1d5a2e;
        }
    </style>
</head>

<body>
    <header class="staff-header">
        <h1>Thông tin cá nhân</h1>
        <div class="staff-meta">
            <div class="user-dropdown">
                <button class="dropdown-toggle">
                    <strong>Xin chào, {{ Auth::guard('customer')->user()->FullName ?? 'Khách hàng' }}</strong>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <div class="dropdown-menu">
                    <a href="{{ route('customer.profile') }}" class="dropdown-item">
                        <i class="fas fa-user"></i> Thông tin cá nhân
                    </a>
                    <form action="{{ route('staff.staff.logout') }}" method="POST" style="display: inline;">
                        @csrf
                        <button type="submit" class="dropdown-item" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
                            <i class="fas fa-sign-out-alt"></i> Đăng xuất
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <div style="display: flex; gap: 20px;">
        <!-- Main content -->
        <div style="flex: 1;">
            <div class="profile-container">
                <div class="profile-header">
                    <div class="profile-avatar">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="profile-info">
                        <h2>{{ $customer->FullName }}</h2>
                        <p>ID: {{ $customer->CustomerID }}</p>
                    </div>
                </div>

                <form id="profileForm">
                    @csrf
                    <div class="form-group">
                        <label for="fullName">Họ và tên</label>
                        <input type="text" id="fullName" name="fullName" value="{{ $customer->FullName }}" required>
                    </div>

                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" id="email" name="email" value="{{ $customer->Email }}" required>
                    </div>

                    <div class="form-group">
                        <label for="phone">Số điện thoại</label>
                        <input type="tel" id="phone" name="phone" value="{{ $customer->Phone }}" required>
                    </div>

                    <div class="form-group">
                        <label for="cccd">CCCD</label>
                        <input type="text" id="cccd" name="cccd" value="{{ $customer->CCCD ?? '' }}">
                    </div>

                    <div class="form-group">
                        <label for="address">Địa chỉ</label>
                        <input type="text" id="address" name="address" value="{{ $customer->Address ?? '' }}">
                    </div>

                    <div class="profile-actions">
                        <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
                        <button type="button" class="btn btn-secondary" id="changePasswordBtn">Đổi mật khẩu</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Change Password Modal -->
    <div id="changePasswordModal" class="change-password-modal">
        <div class="modal-content">
            <span class="modal-close">&times;</span>
            <div class="modal-header">
                <h3>Đổi mật khẩu</h3>
            </div>
            <form id="changePasswordForm">
                @csrf
                <div class="form-group">
                    <label for="currentPassword">Mật khẩu hiện tại</label>
                    <input type="password" id="currentPassword" name="currentPassword" required>
                </div>
                <div class="form-group">
                    <label for="newPassword">Mật khẩu mới</label>
                    <input type="password" id="newPassword" name="newPassword" required>
                </div>
                <div class="form-group">
                    <label for="confirmPassword">Xác nhận mật khẩu mới</label>
                    <input type="password" id="confirmPassword" name="confirmPassword" required>
                </div>
                <div class="profile-actions">
                    <button type="submit" class="btn btn-primary">Xác nhận</button>
                    <button type="button" class="btn btn-secondary" id="cancelChangePassword">Hủy</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Dropdown functionality
            const dropdownToggle = document.querySelector('.dropdown-toggle');
            const dropdownMenu = document.querySelector('.dropdown-menu');

            dropdownToggle.addEventListener('click', function(e) {
                e.stopPropagation();
                dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(e) {
                if (!e.target.closest('.user-dropdown')) {
                    dropdownMenu.style.display = 'none';
                }
            });

            // Profile form submission
            const profileForm = document.getElementById('profileForm');
            profileForm.addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });

                fetch('/customer/profile', {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Thông tin đã được cập nhật thành công!');
                            location.reload();
                        } else {
                            alert('Có lỗi xảy ra: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi cập nhật thông tin');
                    });
            });

            // Change password modal functionality
            const changePasswordBtn = document.getElementById('changePasswordBtn');
            const changePasswordModal = document.getElementById('changePasswordModal');
            const closePasswordModal = document.querySelector('#changePasswordModal .modal-close');
            const cancelChangePassword = document.getElementById('cancelChangePassword');
            const changePasswordForm = document.getElementById('changePasswordForm');

            function closeModal() {
                changePasswordModal.style.display = 'none';
                changePasswordForm.reset();
            }

            changePasswordBtn.addEventListener('click', function() {
                changePasswordModal.style.display = 'block';
            });

            closePasswordModal.addEventListener('click', closeModal);
            cancelChangePassword.addEventListener('click', closeModal);

            window.addEventListener('click', function(e) {
                if (e.target === changePasswordModal) {
                    closeModal();
                }
            });

            changePasswordForm.addEventListener('submit', function(e) {
                e.preventDefault();
                const newPassword = document.getElementById('newPassword').value;
                const confirmPassword = document.getElementById('confirmPassword').value;

                if (newPassword !== confirmPassword) {
                    alert('Mật khẩu mới và xác nhận mật khẩu không khớp!');
                    return;
                }

                const formData = new FormData(this);
                const data = {};
                formData.forEach((value, key) => {
                    data[key] = value;
                });

                fetch('/staff/change-password', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify(data)
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('Mật khẩu đã được thay đổi thành công!');
                            closeModal();
                        } else {
                            alert('Có lỗi xảy ra: ' + data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Có lỗi xảy ra khi đổi mật khẩu');
                    });
            });
        });
    </script>
</body>

</html>
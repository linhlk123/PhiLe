<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Quản lý nhân viên - Leviosa Resort</title>
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/staff.new.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    .staff-table-container {
      padding: 20px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
      margin: 20px;
    }

    .staff-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    .staff-table th,
    .staff-table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    .staff-table th {
      background-color: #1d5a2e;
      color: white;
      font-weight: 500;
    }

    .staff-table tbody tr:hover {
      background-color: #f5f5f5;
    }

    .staff-tools {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .btn {
      padding: 8px 16px;
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

    .btn-danger {
      background: #dc3545;
      color: white;
    }

    .modal {
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

    .form-group {
      margin-bottom: 15px;
    }

    .form-group label {
      display: block;
      margin-bottom: 5px;
      color: #455a64;
      font-weight: 500;
    }

    .form-group input,
    .form-group select {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
    }

    .modal-footer {
      margin-top: 20px;
      padding-top: 15px;
      border-top: 1px solid #eee;
      display: flex;
      justify-content: flex-end;
      gap: 10px;
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

    nav.top-nav ul li a.active {
      background: rgba(255, 255, 255, 0.2);
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

    .dropdown-menu.show {
      display: block;
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
  </style>
</head>

<body>
  <header class="staff-header">
    <h1>Quản lý nhân viên</h1>
    <div class="staff-meta">
      <div class="user-dropdown">
        <button class="dropdown-toggle">
          <strong>Xin chào, {{ Auth::guard('staff')->user()->FullName ?? 'Nhân viên' }}</strong>
          <i class="fas fa-chevron-down"></i>
        </button>
        <div class="dropdown-menu">
          <a href="#" class="dropdown-item">
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
    <!-- Danh mục panel -->
    <div style="width: 270px; background: white; border-radius: 10px; padding: 20px; margin: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: fit-content;">
      <h3 style="margin-top: 0; color: #1d5a2e; border-bottom: 2px solid #1d5a2e; padding-bottom: 10px;">Danh mục</h3>
      <ul style="list-style: none; padding: 0;">
        <li style="margin: 10px 0;">
          <a href="{{ route('staff.staff-room') }}" style="text-decoration: none; color: #455a64; display: block; padding: 8px 12px; border-radius: 5px; transition: all 0.3s;">
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
          <a href="{{ route('staff.employee') }}" style="text-decoration: none; color: #1d5a2e; display: block; padding: 8px 12px; border-radius: 5px; background: rgba(29, 90, 46, 0.1); transition: all 0.3s;">
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
      <nav class="top-nav">
        <ul>
          <li><a href="{{ route('staff.staff-room') }}">Quản lý phòng</a></li>
          <li><a href="{{ route('staff.booking') }}">Quản lý đặt phòng</a></li>
          <li><a href="{{ route('staff.customer') }}">Quản lý khách hàng</a></li>
          <li><a href="{{ route('staff.employee') }}" class="active">Quản lý nhân viên</a></li>
          <li><a href="{{ route('staff.service') }}">Quản lý dịch vụ</a></li>
          <li><a href="{{ route('staff.invoice') }}">Quản lý hóa đơn</a></li>
          <li><a href="{{ route('welcome') }}">Welcome</a></li>
          <li><a href="{{ route('policy') }}">Điều khoản & chính sách</a></li>
          <li><a href="{{ route('feedback') }}">Phản ánh ý kiến</a></li>
        </ul>
      </nav>
      <div class="staff-table-container">
        <div class="staff-tools">
          <input type="text" id="staffSearch" placeholder="Tìm kiếm nhân viên..."
            style="padding: 8px; border: 1px solid #ddd; border-radius: 5px; width: 300px;">
          <button id="addStaffBtn" class="btn btn-primary">+ Thêm nhân viên</button>
        </div>

        <table class="staff-table">
          <thead>
            <tr>
              <th>Họ và tên</th>
              <th>Chức vụ</th>
              <th>SĐT</th>
              <th>Email</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody id="staffTableBody">
            @foreach($staffs as $staff)
            <tr data-staff-id="{{ $staff->StaffID }}">
              <td>{{ $staff->FullName }}</td>
              <td>{{ $staff->Role }}</td>
              <td>{{ $staff->Phone }}</td>
              <td>{{ $staff->Email }}</td>
              <td>
                <button onclick="editStaff({{ $staff->StaffID }})" class="btn btn-secondary">Sửa</button>
                <button onclick="deleteStaff({{ $staff->StaffID }})" class="btn btn-danger">Xóa</button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Modal for staff management -->
      <div id="staffModal" class="modal">
        <div class="modal-content">
          <span class="modal-close">&times;</span>
          <div class="modal-header">
            <h3 id="staffModalTitle">Thêm nhân viên mới</h3>
          </div>
          <form id="staffForm">
            @csrf
            <input type="hidden" id="staffId" name="staffId">
            <div class="form-group">
              <label for="staffName">Họ và tên *</label>
              <input type="text" id="staffName" name="fullName" required>
            </div>
            <div class="form-group">
              <label for="staffRole">Chức vụ *</label>
              <select id="staffRole" name="role" required>
                <option value="Lễ tân">Lễ tân</option>
                <option value="Phục vụ phòng">Phục vụ phòng</option>
                <option value="Bảo vệ">Bảo vệ</option>
                <option value="Kỹ thuật">Kỹ thuật</option>
                <option value="Quản lý">Quản lý</option>
              </select>
            </div>
            <div class="form-group">
              <label for="staffPhone">Số điện thoại *</label>
              <input type="tel" id="staffPhone" name="phone" required>
            </div>
            <div class="form-group">
              <label for="staffEmail">Email *</label>
              <input type="email" id="staffEmail" name="email" required>
            </div>
            <div class="form-group">
              <label for="staffPassword">Mật khẩu *</label>
              <input type="password" id="staffPassword" name="password">
              <small style="color: #666;">Để trống nếu không muốn thay đổi mật khẩu</small>
            </div>
            <div class="form-group">
              <label for="staffIdNumber">CCCD/CMND</label>
              <input type="text" id="staffIdNumber" name="cccd">
            </div>
            <div class="form-group">
              <label for="staffAddress">Địa chỉ</label>
              <input type="text" id="staffAddress" name="address">
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" id="cancelStaffEdit">Hủy</button>
              <button type="submit" class="btn btn-primary">Lưu</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      const staffTableBody = document.getElementById('staffTableBody');
      const staffModal = document.getElementById('staffModal');
      const addStaffBtn = document.getElementById('addStaffBtn');
      const closeStaffModal = document.querySelector('#staffModal .modal-close');
      const cancelStaffEdit = document.getElementById('cancelStaffEdit');
      const staffForm = document.getElementById('staffForm');
      const staffSearch = document.getElementById('staffSearch');

      // User dropdown functionality
      const dropdownToggle = document.querySelector('.dropdown-toggle');
      const dropdownMenu = document.querySelector('.dropdown-menu');

      dropdownToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdownMenu.style.display = dropdownMenu.style.display === 'block' ? 'none' : 'block';
      });

      document.addEventListener('click', function(e) {
        if (!e.target.closest('.user-dropdown')) {
          dropdownMenu.style.display = 'none';
        }
      });

      // Edit staff function
      window.editStaff = function(id) {
        fetch(`/staff/employee/${id}`)
          .then(response => response.json())
          .then(staff => {
            document.getElementById('staffModalTitle').textContent = 'Chỉnh sửa thông tin nhân viên';
            document.getElementById('staffId').value = staff.StaffID;
            document.getElementById('staffName').value = staff.FullName;
            document.getElementById('staffRole').value = staff.Role;
            document.getElementById('staffPhone').value = staff.Phone;
            document.getElementById('staffEmail').value = staff.Email;
            document.getElementById('staffIdNumber').value = staff.CCCD || '';
            document.getElementById('staffAddress').value = staff.Address || '';
            document.getElementById('staffPassword').value = '';
            document.getElementById('staffPassword').removeAttribute('required');

            staffModal.style.display = 'block';
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải thông tin nhân viên');
          });
      }

      // Delete staff function
      window.deleteStaff = function(id) {
        if (confirm('Bạn có chắc muốn xóa nhân viên này?')) {
          fetch(`/staff/employee/${id}`, {
              method: 'DELETE',
              headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Content-Type': 'application/json'
              }
            })
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                alert('Xóa nhân viên thành công!');
                location.reload();
              } else {
                alert('Có lỗi xảy ra: ' + data.message);
              }
            })
            .catch(error => {
              console.error('Error:', error);
              alert('Có lỗi xảy ra khi xóa nhân viên');
            });
        }
      }

      // Add staff button
      addStaffBtn.addEventListener('click', function() {
        document.getElementById('staffModalTitle').textContent = 'Thêm nhân viên mới';
        document.getElementById('staffId').value = '';
        staffForm.reset();
        document.getElementById('staffPassword').setAttribute('required', 'required');
        staffModal.style.display = 'block';
      });

      // Close modal
      closeStaffModal.addEventListener('click', () => staffModal.style.display = 'none');
      cancelStaffEdit.addEventListener('click', () => staffModal.style.display = 'none');
      window.addEventListener('click', (e) => {
        if (e.target === staffModal) staffModal.style.display = 'none';
      });

      // Submit form
      staffForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const staffId = document.getElementById('staffId').value;

        const url = staffId ? `/staff/employee/${staffId}` : '/staff/employee';
        const method = staffId ? 'PUT' : 'POST';

        // Convert FormData to JSON for PUT request
        const data = {};
        formData.forEach((value, key) => {
          data[key] = value;
        });

        fetch(url, {
            method: method,
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert(staffId ? 'Cập nhật thông tin nhân viên thành công!' : 'Thêm nhân viên mới thành công!');
              staffModal.style.display = 'none';
              location.reload();
            } else {
              alert('Có lỗi xảy ra: ' + data.message);
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi lưu thông tin nhân viên');
          });
      });

      // Search functionality
      staffSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = staffTableBody.querySelectorAll('tr');

        rows.forEach(row => {
          const text = row.textContent.toLowerCase();
          row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
      });
    });
  </script>
</body>

</html>
<!DOCTYPE html>
<html lang="vi">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Quản lý khách hàng - Leviosa Resort</title>
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

    .modal.active {
      display: block;
    }

    .modal-content {
      position: relative;
      background: white;
      width: 90%;
      max-width: 600px;
      margin: 50px auto;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
      max-height: 90vh;
      overflow-y: auto;
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

    .alert {
      padding: 12px 20px;
      margin: 20px;
      border-radius: 5px;
      display: none;
    }

    .alert.show {
      display: block;
    }

    .alert-success {
      background: #d4edda;
      color: #155724;
      border: 1px solid #c3e6cb;
    }

    .alert-danger {
      background: #f8d7da;
      color: #721c24;
      border: 1px solid #f5c6cb;
    }
  </style>
</head>

<body>
  <header class="staff-header">
    <h1>Quản lý khách hàng</h1>
    <div class="staff-meta">
      <div class="user-dropdown">
        <button class="dropdown-toggle">
          <strong>Xin chào, {{ Auth::guard('staff')->user()->FullName ?? 'Nhân viên' }}</strong>
          <i class="fas fa-chevron-down"></i>
        </button>
        <div class="dropdown-menu">
          <a href="{{ route('staff.profile') }}" class="dropdown-item">
            <i class="fas fa-user"></i> Thông tin cá nhân
          </a>
          <form action="{{ route('staff.staff.logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="dropdown-item" style="width: 100%; text-align: left; background: none; border: none; cursor: pointer;">
              <i class="fas fa-sign-out-alt"></i> Đăng xuất
            </button>
          </form>
        </div>
      </div>
    </div>
  </header>

  <div id="alertContainer"></div>

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
          <a href="{{ route('staff.customer') }}" style="text-decoration: none; color: #1d5a2e; display: block; padding: 8px 12px; border-radius: 5px; background: rgba(29, 90, 46, 0.1); transition: all 0.3s;">
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
      <nav class="top-nav">
        <ul>
          <li><a href="{{ route('staff.staff-room') }}">Quản lý phòng</a></li>
          <li><a href="{{ route('staff.booking') }}">Quản lý đặt phòng</a></li>
          <li><a href="{{ route('staff.customer') }}" class="active">Quản lý khách hàng</a></li>
          <li><a href="{{ route('staff.employee') }}">Quản lý nhân viên</a></li>
          <li><a href="{{ route('staff.service') }}">Quản lý dịch vụ</a></li>
          <li><a href="{{ route('staff.invoice') }}">Quản lý hóa đơn</a></li>
          <li><a href="{{ route('welcome') }}">Welcome</a></li>
          <li><a href="{{ route('policy') }}">Điều khoản & chính sách</a></li>
          <li><a href="{{ route('feedback') }}">Phản ánh ý kiến</a></li>
        </ul>
      </nav>

      <div class="staff-table-container">
        <div class="staff-tools">
          <input type="text" id="customerSearch" placeholder="Tìm kiếm khách hàng (tên, SĐT, email, CCCD)..."
            style="padding: 8px; border: 1px solid #ddd; border-radius: 5px; width: 400px;">
          <button id="addCustomerBtn" class="btn btn-primary">
            <i class="fas fa-plus"></i> Thêm khách hàng
          </button>
        </div>

        <table class="staff-table">
          <thead>
            <tr>
              <th>ID</th>
              <th>Họ và tên</th>
              <th>Giới tính</th>
              <th>SĐT</th>
              <th>Email</th>
              <th>CCCD</th>
              <th>Địa chỉ</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody id="customerTableBody">
            @forelse($customers as $customer)
            <tr data-id="{{ $customer->CustomerID }}">
              <td>{{ $customer->CustomerID }}</td>
              <td>{{ $customer->FullName }}</td>
              <td>{{ $customer->Gender }}</td>
              <td>{{ $customer->Phone }}</td>
              <td>{{ $customer->Email }}</td>
              <td>{{ $customer->IDNumber }}</td>
              <td>{{ $customer->Address }}</td>
              <td>
                <button onclick="editCustomer({{ $customer->CustomerID }})" class="btn btn-secondary">
                  <i class="fas fa-edit"></i> Sửa
                </button>
                <button onclick="deleteCustomer({{ $customer->CustomerID }})" class="btn btn-danger">
                  <i class="fas fa-trash"></i> Xóa
                </button>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="8" style="text-align: center; padding: 20px; color: #999;">
                Chưa có khách hàng nào trong hệ thống
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>

      <!-- Modal for customer management -->
      <div id="customerModal" class="modal">
        <div class="modal-content">
          <span class="modal-close">&times;</span>
          <div class="modal-header">
            <h3 id="customerModalTitle">Thêm khách hàng mới</h3>
          </div>
          <form id="customerForm">
            @csrf
            <input type="hidden" id="customerId" name="customer_id">
            <input type="hidden" id="formMethod" name="_method" value="POST">

            <div class="form-group">
              <label for="customerName">Họ và tên *</label>
              <input type="text" id="customerName" name="FullName" required>
            </div>

            <div class="form-group">
              <label for="customerGender">Giới tính *</label>
              <select id="customerGender" name="Gender" required>
                <option value="Nam">Nam</option>
                <option value="Nữ">Nữ</option>
                <option value="Khác">Khác</option>
              </select>
            </div>

            <div class="form-group">
              <label for="customerPhone">Số điện thoại *</label>
              <input type="tel" id="customerPhone" name="Phone" required>
            </div>

            <div class="form-group">
              <label for="customerEmail">Email *</label>
              <input type="email" id="customerEmail" name="Email" required>
            </div>

            <div class="form-group">
              <label for="customerIdNumber">CCCD/CMND *</label>
              <input type="text" id="customerIdNumber" name="IDNumber" required>
            </div>

            <div class="form-group">
              <label for="customerAddress">Địa chỉ *</label>
              <input type="text" id="customerAddress" name="Address" required>
            </div>

            <div class="form-group">
              <label for="customerPassword">Mật khẩu <span id="passwordHint">(để trống nếu không đổi)</span></label>
              <input type="password" id="customerPassword" name="Password">
            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" id="cancelCustomerEdit">Hủy</button>
              <button type="submit" class="btn btn-primary">Lưu</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    // Setup CSRF token for all AJAX requests
    const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

    // Store original customer data for search
    let allCustomers = @json($customers);

    const customerModal = document.getElementById('customerModal');
    const addCustomerBtn = document.getElementById('addCustomerBtn');
    const closeCustomerModal = document.querySelector('#customerModal .modal-close');
    const cancelCustomerEdit = document.getElementById('cancelCustomerEdit');
    const customerForm = document.getElementById('customerForm');
    const customerSearch = document.getElementById('customerSearch');
    const customerTableBody = document.getElementById('customerTableBody');

    // Show alert message
    function showAlert(message, type = 'success') {
      const alertContainer = document.getElementById('alertContainer');
      const alertDiv = document.createElement('div');
      alertDiv.className = `alert alert-${type} show`;
      alertDiv.textContent = message;
      alertContainer.appendChild(alertDiv);

      setTimeout(() => {
        alertDiv.classList.remove('show');
        setTimeout(() => alertDiv.remove(), 300);
      }, 3000);
    }

    // Open add customer modal
    addCustomerBtn.addEventListener('click', function() {
      document.getElementById('customerModalTitle').textContent = 'Thêm khách hàng mới';
      document.getElementById('customerId').value = '';
      document.getElementById('formMethod').value = 'POST';
      document.getElementById('passwordHint').textContent = '*';
      customerForm.reset();
      customerModal.classList.add('active');
    });

    // Close modal
    closeCustomerModal.addEventListener('click', () => customerModal.classList.remove('active'));
    cancelCustomerEdit.addEventListener('click', () => customerModal.classList.remove('active'));
    window.addEventListener('click', (e) => {
      if (e.target === customerModal) customerModal.classList.remove('active');
    });

    // Edit customer
    window.editCustomer = async function(id) {
      try {
        const response = await fetch(`/staff/customer/${id}`);
        const data = await response.json();

        if (data.success) {
          const customer = data.customer;

          document.getElementById('customerModalTitle').textContent = 'Chỉnh sửa thông tin khách hàng';
          document.getElementById('customerId').value = customer.CustomerID;
          document.getElementById('formMethod').value = 'PUT';
          document.getElementById('customerName').value = customer.FullName;
          document.getElementById('customerGender').value = customer.Gender;
          document.getElementById('customerPhone').value = customer.Phone;
          document.getElementById('customerEmail').value = customer.Email;
          document.getElementById('customerIdNumber').value = customer.IDNumber;
          document.getElementById('customerAddress').value = customer.Address;
          document.getElementById('customerPassword').value = '';
          document.getElementById('passwordHint').textContent = '(để trống nếu không đổi)';

          customerModal.classList.add('active');
        }
      } catch (error) {
        showAlert('Không thể tải thông tin khách hàng', 'danger');
      }
    }

    // Delete customer
    window.deleteCustomer = async function(id) {
      if (!confirm('Bạn có chắc muốn xóa khách hàng này?')) return;

      try {
        const response = await fetch(`/staff/customer/${id}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Content-Type': 'application/json'
          }
        });

        const data = await response.json();

        if (data.success) {
          showAlert('Xóa khách hàng thành công!');
          // Remove row from table
          document.querySelector(`tr[data-id="${id}"]`).remove();
          // Update allCustomers array
          allCustomers = allCustomers.filter(c => c.CustomerID !== id);
        } else {
          showAlert(data.message || 'Có lỗi xảy ra', 'danger');
        }
      } catch (error) {
        showAlert('Có lỗi xảy ra khi xóa khách hàng', 'danger');
      }
    }

    // Submit form (Add/Edit)
    customerForm.addEventListener('submit', async function(e) {
      e.preventDefault();

      const formData = new FormData(customerForm);
      const customerId = document.getElementById('customerId').value;
      const method = document.getElementById('formMethod').value;

      let url = '/staff/customer';
      if (customerId) {
        url += `/${customerId}`;
      }

      try {
        const response = await fetch(url, {
          method: 'POST',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
          },
          body: formData
        });

        const data = await response.json();

        if (data.success) {
          showAlert(customerId ? 'Cập nhật khách hàng thành công!' : 'Thêm khách hàng thành công!');
          customerModal.classList.remove('active');

          // Reload page to show updated data
          setTimeout(() => location.reload(), 1000);
        } else {
          showAlert(data.message || 'Có lỗi xảy ra', 'danger');
        }
      } catch (error) {
        showAlert('Có lỗi xảy ra khi lưu dữ liệu', 'danger');
      }
    });

    // Search functionality
    customerSearch.addEventListener('input', function() {
      const searchTerm = this.value.toLowerCase().trim();

      if (searchTerm === '') {
        // Show all customers
        renderCustomerTable(allCustomers);
      } else {
        // Filter customers
        const filteredCustomers = allCustomers.filter(customer =>
          customer.FullName.toLowerCase().includes(searchTerm) ||
          customer.Phone.includes(searchTerm) ||
          customer.Email.toLowerCase().includes(searchTerm) ||
          customer.IDNumber.includes(searchTerm)
        );
        renderCustomerTable(filteredCustomers);
      }
    });

    // Render customer table
    function renderCustomerTable(customers) {
      if (customers.length === 0) {
        customerTableBody.innerHTML = `
          <tr>
            <td colspan="8" style="text-align: center; padding: 20px; color: #999;">
              Không tìm thấy khách hàng phù hợp
            </td>
          </tr>
        `;
        return;
      }

      customerTableBody.innerHTML = customers.map(customer => `
        <tr data-id="${customer.CustomerID}">
          <td>${customer.CustomerID}</td>
          <td>${customer.FullName}</td>
          <td>${customer.Gender}</td>
          <td>${customer.Phone}</td>
          <td>${customer.Email}</td>
          <td>${customer.IDNumber}</td>
          <td>${customer.Address}</td>
          <td>
            <button onclick="editCustomer(${customer.CustomerID})" class="btn btn-secondary">
              <i class="fas fa-edit"></i> Sửa
            </button>
            <button onclick="deleteCustomer(${customer.CustomerID})" class="btn btn-danger">
              <i class="fas fa-trash"></i> Xóa
            </button>
          </td>
        </tr>
      `).join('');
    }

    // Dropdown functionality
    const dropdownToggle = document.querySelector('.dropdown-toggle');
    const dropdownMenu = document.querySelector('.dropdown-menu');

    if (dropdownToggle && dropdownMenu) {
      dropdownToggle.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdownMenu.classList.toggle('show');
      });

      document.addEventListener('click', function() {
        dropdownMenu.classList.remove('show');
      });
    }
  </script>
</body>

</html>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Quản lý dịch vụ - Resort</title>
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/staff.new.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    .service-table-container {
      background: white;
      border-radius: 10px;
      padding: 20px;
      margin: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .service-tools {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .service-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    .service-table th,
    .service-table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    .service-table th {
      background-color: #1d5a2e;
      color: white;
      font-weight: 500;
    }

    .service-table tbody tr:hover {
      background-color: #f5f5f5;
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
    .form-group select,
    .form-group textarea {
      width: 100%;
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 14px;
    }

    .form-group textarea {
      resize: vertical;
      min-height: 60px;
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
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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

    .usage-history {
      margin-top: 30px;
    }

    .price-display {
      padding: 8px;
      background: #f5f5f5;
      border-radius: 5px;
      font-weight: bold;
      color: #1d5a2e;
    }
  </style>
</head>
<body>
  <header class="staff-header">
    <h1>Quản lý dịch vụ</h1>
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
    <div style="width: 250px; background: white; border-radius: 10px; padding: 20px; margin: 20px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); height: fit-content;">
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
          <a href="{{ route('staff.employee') }}" style="text-decoration: none; color: #455a64; display: block; padding: 8px 12px; border-radius: 5px; transition: all 0.3s;">
            <i class="fas fa-users" style="margin-right: 8px;"></i>Quản lý nhân viên
          </a>
        </li>
        <li style="margin: 10px 0;">
          <a href="{{ route('staff.service') }}" style="text-decoration: none; color: #1d5a2e; display: block; padding: 8px 12px; border-radius: 5px; background: rgba(29, 90, 46, 0.1); transition: all 0.3s;">
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
    </ul>
  </div>

  <!-- Main content -->
  <div style="flex: 1;">
    <nav class="top-nav">
      <ul>
        <li><a href="{{ route('staff.staff-room') }}">Quản lý phòng</a></li>
        <li><a href="{{ route('staff.booking') }}">Quản lý đặt phòng</a></li>
        <li><a href="{{ route('staff.customer') }}">Quản lý khách hàng</a></li>
        <li><a href="{{ route('staff.employee') }}">Quản lý nhân viên</a></li>
        <li><a href="{{ route('staff.service') }}" class="active">Quản lý dịch vụ</a></li>
        <li><a href="{{ route('staff.invoice') }}">Quản lý hóa đơn</a></li>
      </ul>
    </nav>      <!-- Services Table -->
      <div class="service-table-container">
        <h3 style="margin-top: 0;">Danh sách dịch vụ</h3>
        <div class="service-tools">
          <input type="text" id="serviceSearch" placeholder="Tìm kiếm dịch vụ..." 
                 style="padding: 8px; border: 1px solid #ddd; border-radius: 5px; width: 300px;">
          <button id="addServiceBtn" class="btn btn-primary">+ Thêm dịch vụ</button>
        </div>

        <table class="service-table">
          <thead>
            <tr>
              <th>Tên dịch vụ</th>
              <th>Giá</th>
              <th>Mô tả</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody id="serviceTableBody">
            @foreach($services as $service)
            <tr data-service-id="{{ $service->ServiceID }}">
              <td>{{ $service->ServiceName }}</td>
              <td>{{ number_format($service->Price, 0, ',', '.') }} VNĐ</td>
              <td>{{ $service->Description ?? 'N/A' }}</td>
              <td>
                <button onclick="editService({{ $service->ServiceID }})" class="btn btn-secondary">Sửa</button>
                <button onclick="deleteService({{ $service->ServiceID }})" class="btn btn-danger">Xóa</button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Usage History Table -->
      <div class="service-table-container usage-history">
        <h3>Lịch sử sử dụng dịch vụ</h3>
        <div class="service-tools">
          <input type="text" id="usageSearch" placeholder="Tìm kiếm theo mã đặt phòng/tên khách..." 
                 style="padding: 8px; border: 1px solid #ddd; border-radius: 5px; width: 300px;">
          <button id="addUsageBtn" class="btn btn-primary">+ Thêm lượt sử dụng</button>
        </div>

        <table class="service-table">
          <thead>
            <tr>
              <th>Mã đặt phòng</th>
              <th>Tên khách hàng</th>
              <th>Dịch vụ</th>
              <th>Số lượng</th>
              <th>Đơn giá</th>
              <th>Thành tiền</th>
              <th>Phòng</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody id="usageTableBody">
            @foreach($serviceUsages as $usage)
            <tr data-usage-id="{{ $usage->UsageID }}">
              <td>{{ $usage->BookingID }}</td>
              <td>{{ $usage->CustomerName ?? 'N/A' }}</td>
              <td>{{ $usage->ServiceName }}</td>
              <td>{{ $usage->Quantity }}</td>
              <td>{{ number_format($usage->ServicePrice, 0, ',', '.') }} VNĐ</td>
              <td>{{ number_format($usage->TotalPrice, 0, ',', '.') }} VNĐ</td>
              <td>
                @if($usage->RoomNumbers)
                  <span style="color: #1d5a2e; font-weight: bold;">{{ $usage->RoomNumbers }}</span>
                @else
                  <span style="color: #999;">Chưa có phòng</span>
                @endif
              </td>
              <td>
                <button onclick="assignRoom({{ $usage->UsageID }}, {{ $usage->BookingID }})" class="btn btn-secondary" title="Chọn phòng">
                  <i class="fas fa-door-open"></i>
                </button>
                <button onclick="editUsage({{ $usage->UsageID }})" class="btn btn-secondary">Sửa</button>
                <button onclick="deleteUsage({{ $usage->UsageID }})" class="btn btn-danger">Xóa</button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>

      <!-- Modal for service management -->
      <div id="serviceModal" class="modal">
        <div class="modal-content">
          <span class="modal-close">&times;</span>
          <div class="modal-header">
            <h3 id="serviceModalTitle">Thêm dịch vụ mới</h3>
          </div>
          <form id="serviceForm">
            @csrf
            <input type="hidden" id="serviceId" name="serviceId">
            <div class="form-group">
              <label for="serviceName">Tên dịch vụ *</label>
              <input type="text" id="serviceName" name="serviceName" required>
            </div>
            <div class="form-group">
              <label for="servicePrice">Giá (VNĐ) *</label>
              <input type="number" id="servicePrice" name="price" required min="0" step="1000">
            </div>
            <div class="form-group">
              <label for="serviceDesc">Mô tả</label>
              <textarea id="serviceDesc" name="description"></textarea>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" id="cancelServiceEdit">Hủy</button>
              <button type="submit" class="btn btn-primary">Lưu</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Modal for service usage -->
      <div id="usageModal" class="modal">
        <div class="modal-content">
          <span class="modal-close">&times;</span>
          <div class="modal-header">
            <h3 id="usageModalTitle">Thêm lượt sử dụng dịch vụ</h3>
          </div>
          <form id="usageForm">
            @csrf
            <input type="hidden" id="usageId" name="usageId">
            <div class="form-group">
              <label for="bookingId">Mã đặt phòng *</label>
              <select id="bookingId" name="bookingId" required>
                <option value="">Chọn mã đặt phòng</option>
                @foreach($bookings as $booking)
                <option value="{{ $booking->BookingID }}">
                  {{ $booking->BookingID }} - {{ $booking->CustomerName ?? 'N/A' }} 
                  @if($booking->RoomNumbers)
                  (Phòng: {{ $booking->RoomNumbers }})
                  @else
                  (Chưa có phòng)
                  @endif
                  @if($booking->CheckInDate && $booking->CheckOutDate)
                  - {{ \Carbon\Carbon::parse($booking->CheckInDate)->format('d/m/Y') }} đến {{ \Carbon\Carbon::parse($booking->CheckOutDate)->format('d/m/Y') }}
                  @endif
                </option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="selectedService">Dịch vụ *</label>
              <select id="selectedService" name="serviceId" required>
                <option value="">Chọn dịch vụ</option>
                @foreach($services as $service)
                <option value="{{ $service->ServiceID }}" data-price="{{ $service->Price }}">
                  {{ $service->ServiceName }} - {{ number_format($service->Price, 0, ',', '.') }} VNĐ
                </option>
                @endforeach
              </select>
            </div>
            <div class="form-group">
              <label for="quantity">Số lượng *</label>
              <input type="number" id="quantity" name="quantity" required min="1" value="1">
            </div>
            <div class="form-group">
              <label>Đơn giá</label>
              <div class="price-display" id="unitPrice">0 VNĐ</div>
            </div>
            <div class="form-group">
              <label>Thành tiền</label>
              <div class="price-display" id="totalAmount">0 VNĐ</div>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" id="cancelUsageEdit">Hủy</button>
              <button type="submit" class="btn btn-primary">Lưu</button>
            </div>
          </form>
        </div>
      </div>

      <!-- Modal for assigning room -->
      <div id="roomModal" class="modal">
        <div class="modal-content">
          <span class="modal-close" onclick="closeRoomModal()">&times;</span>
          <div class="modal-header">
            <h3>Chọn phòng dịch vụ</h3>
          </div>
          <form id="roomForm">
            @csrf
            <input type="hidden" id="assignUsageId">
            <input type="hidden" id="assignBookingId">
            <div class="form-group">
              <label for="roomSelect">Phòng dịch vụ (E-...) *</label>
              <select id="roomSelect" name="roomId" required>
                <option value="">Chọn phòng</option>
                @foreach($serviceRooms as $room)
                <option value="{{ $room->RoomID }}">
                  {{ $room->RoomNumber }} 
                  @if($room->Status == 'available')
                  <span style="color: green;">(Trống)</span>
                  @else
                  <span style="color: orange;">({{ ucfirst($room->Status) }})</span>
                  @endif
                </option>
                @endforeach
              </select>
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" onclick="closeRoomModal()">Hủy</button>
              <button type="submit" class="btn btn-primary">Gán phòng</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
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

      // Service Management
      const serviceModal = document.getElementById('serviceModal');
      const addServiceBtn = document.getElementById('addServiceBtn');
      const closeServiceModal = document.querySelector('#serviceModal .modal-close');
      const cancelServiceEdit = document.getElementById('cancelServiceEdit');
      const serviceForm = document.getElementById('serviceForm');
      const serviceSearch = document.getElementById('serviceSearch');

      window.editService = function(id) {
        fetch(`/staff/service/${id}`)
          .then(response => response.json())
          .then(service => {
            document.getElementById('serviceModalTitle').textContent = 'Chỉnh sửa dịch vụ';
            document.getElementById('serviceId').value = service.ServiceID;
            document.getElementById('serviceName').value = service.ServiceName;
            document.getElementById('servicePrice').value = service.Price;
            document.getElementById('serviceDesc').value = service.Description || '';
            serviceModal.style.display = 'block';
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải thông tin dịch vụ');
          });
      }

      window.deleteService = function(id) {
        if (confirm('Bạn có chắc muốn xóa dịch vụ này?')) {
          fetch(`/staff/service/${id}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'Content-Type': 'application/json'
            }
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert('Xóa dịch vụ thành công!');
              location.reload();
            } else {
              alert('Có lỗi xảy ra: ' + data.message);
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa dịch vụ');
          });
        }
      }

      addServiceBtn.addEventListener('click', function() {
        document.getElementById('serviceModalTitle').textContent = 'Thêm dịch vụ mới';
        document.getElementById('serviceId').value = '';
        serviceForm.reset();
        serviceModal.style.display = 'block';
      });

      closeServiceModal.addEventListener('click', () => serviceModal.style.display = 'none');
      cancelServiceEdit.addEventListener('click', () => serviceModal.style.display = 'none');
      window.addEventListener('click', (e) => {
        if (e.target === serviceModal) serviceModal.style.display = 'none';
      });

      serviceForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const serviceId = document.getElementById('serviceId').value;
        
        const url = serviceId ? `/staff/service/${serviceId}` : '/staff/service';
        const method = serviceId ? 'PUT' : 'POST';

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
            alert(serviceId ? 'Cập nhật dịch vụ thành công!' : 'Thêm dịch vụ mới thành công!');
            serviceModal.style.display = 'none';
            location.reload();
          } else {
            alert('Có lỗi xảy ra: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Có lỗi xảy ra khi lưu thông tin dịch vụ');
        });
      });

      serviceSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#serviceTableBody tr');
        
        rows.forEach(row => {
          const text = row.textContent.toLowerCase();
          row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
      });

      // Usage Management
      const usageModal = document.getElementById('usageModal');
      const addUsageBtn = document.getElementById('addUsageBtn');
      const closeUsageModal = document.querySelector('#usageModal .modal-close');
      const cancelUsageEdit = document.getElementById('cancelUsageEdit');
      const usageForm = document.getElementById('usageForm');
      const usageSearch = document.getElementById('usageSearch');

      function updateTotalAmount() {
        const serviceSelect = document.getElementById('selectedService');
        const quantity = document.getElementById('quantity').value;
        const selectedOption = serviceSelect.options[serviceSelect.selectedIndex];
        const price = selectedOption ? selectedOption.getAttribute('data-price') : 0;
        
        if (price) {
          document.getElementById('unitPrice').textContent = `${parseInt(price).toLocaleString('vi-VN')} VNĐ`;
          const total = price * parseInt(quantity);
          document.getElementById('totalAmount').textContent = `${total.toLocaleString('vi-VN')} VNĐ`;
        }
      }

      window.editUsage = function(id) {
        fetch(`/staff/service-usage/${id}`)
          .then(response => response.json())
          .then(usage => {
            document.getElementById('usageModalTitle').textContent = 'Chỉnh sửa lượt sử dụng';
            document.getElementById('usageId').value = usage.UsageID;
            document.getElementById('bookingId').value = usage.BookingID;
            document.getElementById('selectedService').value = usage.ServiceID;
            document.getElementById('quantity').value = usage.Quantity;
            updateTotalAmount();
            usageModal.style.display = 'block';
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi tải thông tin lượt sử dụng');
          });
      }

      window.deleteUsage = function(id) {
        if (confirm('Bạn có chắc muốn xóa lượt sử dụng này?')) {
          fetch(`/staff/service-usage/${id}`, {
            method: 'DELETE',
            headers: {
              'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
              'Content-Type': 'application/json'
            }
          })
          .then(response => response.json())
          .then(data => {
            if (data.success) {
              alert('Xóa lượt sử dụng thành công!');
              location.reload();
            } else {
              alert('Có lỗi xảy ra: ' + data.message);
            }
          })
          .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi xóa lượt sử dụng');
          });
        }
      }

      addUsageBtn.addEventListener('click', function() {
        document.getElementById('usageModalTitle').textContent = 'Thêm lượt sử dụng mới';
        document.getElementById('usageId').value = '';
        usageForm.reset();
        document.getElementById('unitPrice').textContent = '0 VNĐ';
        document.getElementById('totalAmount').textContent = '0 VNĐ';
        usageModal.style.display = 'block';
      });

      closeUsageModal.addEventListener('click', () => usageModal.style.display = 'none');
      cancelUsageEdit.addEventListener('click', () => usageModal.style.display = 'none');
      window.addEventListener('click', (e) => {
        if (e.target === usageModal) usageModal.style.display = 'none';
      });

      document.getElementById('selectedService').addEventListener('change', updateTotalAmount);
      document.getElementById('quantity').addEventListener('input', updateTotalAmount);

      usageForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const usageId = document.getElementById('usageId').value;
        
        const url = usageId ? `/staff/service-usage/${usageId}` : '/staff/service-usage';
        const method = usageId ? 'PUT' : 'POST';

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
            alert(usageId ? 'Cập nhật lượt sử dụng thành công!' : 'Thêm lượt sử dụng mới thành công!');
            usageModal.style.display = 'none';
            location.reload();
          } else {
            alert('Có lỗi xảy ra: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Có lỗi xảy ra khi lưu thông tin lượt sử dụng');
        });
      });

      usageSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#usageTableBody tr');
        
        rows.forEach(row => {
          const text = row.textContent.toLowerCase();
          row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
      });

      // Room Assignment
      const roomModal = document.getElementById('roomModal');
      const roomForm = document.getElementById('roomForm');

      window.assignRoom = function(usageId, bookingId) {
        document.getElementById('assignUsageId').value = usageId;
        document.getElementById('assignBookingId').value = bookingId;
        document.getElementById('roomSelect').value = '';
        roomModal.style.display = 'block';
      }

      window.closeRoomModal = function() {
        roomModal.style.display = 'none';
      }

      window.addEventListener('click', (e) => {
        if (e.target === roomModal) {
          closeRoomModal();
        }
      });

      roomForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const usageId = document.getElementById('assignUsageId').value;
        const roomId = document.getElementById('roomSelect').value;

        if (!roomId) {
          alert('Vui lòng chọn phòng');
          return;
        }

        fetch(`/staff/service-usage/${usageId}/assign-room`, {
          method: 'PUT',
          headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Content-Type': 'application/json'
          },
          body: JSON.stringify({ roomId: roomId })
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            // Cập nhật trực tiếp trên giao diện
            const row = document.querySelector(`tr[data-usage-id="${usageId}"]`);
            if (row) {
              const roomCell = row.querySelector('td:nth-child(7)'); // Cột "Phòng"
              if (roomCell) {
                roomCell.innerHTML = `<span style="color: #1d5a2e; font-weight: bold;">${data.roomNumber}</span>`;
              }
            }
            
            alert('Gán phòng thành công: ' + data.roomNumber);
            closeRoomModal();
            
            // Reload sau khi user đóng alert để đảm bảo đồng bộ dữ liệu
            setTimeout(() => {
              location.reload();
            }, 100);
          } else {
            alert('Có lỗi xảy ra: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Có lỗi xảy ra khi gán phòng');
        });
      });
    });
  </script>
</body>
</html>

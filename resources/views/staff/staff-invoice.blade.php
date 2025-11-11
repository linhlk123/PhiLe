<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Quản lý hóa đơn - Resort</title>
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/staff.new.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    .invoice-table-container {
      background: white;
      border-radius: 10px;
      padding: 20px;
      margin: 20px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    }

    .invoice-tools {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 15px;
    }

    .invoice-table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 15px;
    }

    .invoice-table th,
    .invoice-table td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    .invoice-table th {
      background-color: #1d5a2e;
      color: white;
      font-weight: 500;
    }

    .invoice-table tbody tr:hover {
      background-color: #f5f5f5;
    }

    .btn {
      padding: 8px 16px;
      border: none;
      border-radius: 5px;
      cursor: pointer;
      font-weight: 500;
      margin: 0 4px;
    }

    .btn-primary {
      background: #1d5a2e;
      color: white;
    }

    .btn-secondary {
      background: #e0e0e0;
      color: #333;
    }

    .btn-print {
      background: #4caf50;
      color: white;
    }

    .btn-danger {
      background: #f44336;
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

    .status-badge {
      padding: 4px 8px;
      border-radius: 4px;
      font-size: 12px;
      font-weight: 500;
    }

    .status-paid {
      background: #e8f5e9;
      color: #2e7d32;
    }

    .status-pending {
      background: #fff3e0;
      color: #e65100;
    }

    .status-cancelled {
      background: #ffebee;
      color: #c62828;
    }

    .payment-method {
      text-transform: capitalize;
    }
  </style>
</head>
<body>
  <header class="staff-header">
    <h1>Quản lý hóa đơn</h1>
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
          <a href="{{ route('home') }}" class="dropdown-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i> Đăng xuất
          </a>
          <form id="logout-form" action="{{ route('staff.staff.logout') }}" method="POST" style="display: none;">
            @csrf
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
          <a href="{{ route('staff.service') }}" style="text-decoration: none; color: #455a64; display: block; padding: 8px 12px; border-radius: 5px; transition: all 0.3s;">
            <i class="fas fa-concierge-bell" style="margin-right: 8px;"></i>Quản lý dịch vụ
          </a>
        </li>
        <li style="margin: 10px 0;">
          <a href="{{ route('staff.invoice') }}" style="text-decoration: none; color: #1d5a2e; display: block; padding: 8px 12px; border-radius: 5px; background: rgba(29, 90, 46, 0.1); transition: all 0.3s;">
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
          <li><a href="{{ route('staff.invoice') }}" class="active">Quản lý hóa đơn</a></li>
        </ul>
      </nav>

      <div class="invoice-table-container">
        <div class="invoice-tools">
          <input type="text" id="invoiceSearch" placeholder="Tìm kiếm hóa đơn..." 
                style="padding: 8px; border: 1px solid #ddd; border-radius: 5px; width: 300px;">
          <button id="addInvoiceBtn" class="btn btn-primary">+ Thêm hóa đơn</button>
        </div>

        <table class="invoice-table">
          <thead>
            <tr>
              <th>Mã hóa đơn</th>
              <th>Mã đặt phòng</th>
              <th>Tên khách hàng</th>
              <th>Phòng</th>
              <th>Ngày nhận/trả</th>
              <th>Ngày thanh toán</th>
              <th>Số tiền</th>
              <th>Phương thức</th>
              <th>Trạng thái</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody id="invoiceTableBody">
            @foreach($payments as $payment)
            <tr>
              <td>{{ $payment->PaymentID }}</td>
              <td>{{ $payment->BookingID }}</td>
              <td>{{ $payment->CustomerName ?? 'N/A' }}</td>
              <td>{{ $payment->RoomNumbers ?? 'N/A' }}</td>
              <td>
                @if($payment->CheckInDate && $payment->CheckOutDate)
                  {{ \Carbon\Carbon::parse($payment->CheckInDate)->format('d/m/Y') }} - 
                  {{ \Carbon\Carbon::parse($payment->CheckOutDate)->format('d/m/Y') }}
                @else
                  N/A
                @endif
              </td>
              <td>{{ \Carbon\Carbon::parse($payment->PaymentDate)->format('d/m/Y H:i') }}</td>
              <td>{{ number_format($payment->Amount, 0, ',', '.') }} VNĐ</td>
              <td class="payment-method">{{ $payment->PaymentMethod }}</td>
              <td>
                <span class="status-badge status-{{ 
                  $payment->PaymentStatus === 'Đã thanh toán' ? 'paid' : 
                  ($payment->PaymentStatus === 'Chờ thanh toán' ? 'pending' : 'cancelled') 
                }}">
                  {{ $payment->PaymentStatus }}
                </span>
              </td>
              <td>
                <button onclick="editInvoice({{ $payment->PaymentID }})" class="btn btn-secondary">
                  <i class="fas fa-edit"></i> Sửa
                </button>
                <button onclick="deleteInvoice({{ $payment->PaymentID }})" class="btn btn-danger">
                  <i class="fas fa-trash"></i> Xóa
                </button>
                <button onclick="printInvoice({{ $payment->PaymentID }})" class="btn btn-print">
                  <i class="fas fa-print"></i> In
                </button>
              </td>
            </tr>
            @endforeach
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <!-- Modal for invoice management -->
  <div id="invoiceModal" class="modal">
    <div class="modal-content">
      <span class="modal-close">&times;</span>
      <div class="modal-header">
        <h3 id="invoiceModalTitle">Chỉnh sửa hóa đơn</h3>
      </div>
      <form id="invoiceForm">
        <input type="hidden" id="paymentId">
        <div class="form-group">
          <label for="bookingId">Mã đặt phòng *</label>
          <select id="bookingId" required>
            <option value="">Chọn mã đặt phòng</option>
            @foreach($bookings as $booking)
            <option value="{{ $booking->BookingID }}" 
                    data-customer="{{ $booking->CustomerName ?? 'N/A' }}"
                    data-rooms="{{ $booking->RoomNumbers ?? 'N/A' }}"
                    data-room-total="{{ $booking->RoomTotal ?? 0 }}"
                    data-service-total="{{ $booking->ServiceTotal ?? 0 }}"
                    data-amount="{{ $booking->TotalAmount ?? 0 }}">
              Booking #{{ $booking->BookingID }} - {{ $booking->CustomerName ?? 'N/A' }} (Phòng: {{ $booking->RoomNumbers ?? 'N/A' }})
            </option>
            @endforeach
          </select>
        </div>
        <div class="form-group">
          <label for="customerName">Tên khách hàng</label>
          <input type="text" id="customerName" readonly>
        </div>
        <div class="form-group">
          <label for="roomNumbers">Phòng</label>
          <input type="text" id="roomNumbers" readonly>
        </div>
        <div class="form-group">
          <label>Chi phí phòng</label>
          <input type="text" id="roomTotal" readonly style="background: #f5f5f5;">
        </div>
        <div class="form-group">
          <label>Chi phí dịch vụ</label>
          <input type="text" id="serviceTotal" readonly style="background: #f5f5f5;">
        </div>
        <div class="form-group">
          <label for="paymentDate">Ngày thanh toán *</label>
          <input type="datetime-local" id="paymentDate" required>
        </div>
        <div class="form-group">
          <label for="amount">Tổng số tiền (VNĐ) *</label>
          <input type="number" id="amount" required min="0" step="1000" style="font-weight: bold; color: #1d5a2e;">
        </div>
        <div class="form-group">
          <label for="paymentMethod">Phương thức thanh toán *</label>
          <select id="paymentMethod" required>
            <option value="Tiền mặt">Tiền mặt</option>
            <option value="Thẻ tín dụng">Thẻ tín dụng</option>
            <option value="Chuyển khoản">Chuyển khoản</option>
            <option value="Ví điện tử">Ví điện tử</option>
          </select>
        </div>
        <div class="form-group">
          <label for="paymentStatus">Trạng thái *</label>
          <select id="paymentStatus" required>
            <option value="Đã thanh toán">Đã thanh toán</option>
            <option value="Chờ thanh toán">Chờ thanh toán</option>
            <option value="Đã hủy">Đã hủy</option>
          </select>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" id="cancelInvoiceEdit">Hủy</button>
          <button type="submit" class="btn btn-primary">Lưu</button>
        </div>
      </form>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // CSRF token for AJAX requests
      const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

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

      // Booking select change handler
      document.getElementById('bookingId').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
          const customerName = selectedOption.getAttribute('data-customer');
          const roomNumbers = selectedOption.getAttribute('data-rooms');
          const roomTotal = parseFloat(selectedOption.getAttribute('data-room-total')) || 0;
          const serviceTotal = parseFloat(selectedOption.getAttribute('data-service-total')) || 0;
          const totalAmount = parseFloat(selectedOption.getAttribute('data-amount')) || 0;
          
          document.getElementById('customerName').value = customerName;
          document.getElementById('roomNumbers').value = roomNumbers;
          document.getElementById('roomTotal').value = roomTotal.toLocaleString('vi-VN') + ' VNĐ';
          document.getElementById('serviceTotal').value = serviceTotal.toLocaleString('vi-VN') + ' VNĐ';
          document.getElementById('amount').value = totalAmount;
        } else {
          document.getElementById('customerName').value = '';
          document.getElementById('roomNumbers').value = '';
          document.getElementById('roomTotal').value = '';
          document.getElementById('serviceTotal').value = '';
          document.getElementById('amount').value = '';
        }
      });

      // Invoice management
      const invoiceModal = document.getElementById('invoiceModal');
      const addInvoiceBtn = document.getElementById('addInvoiceBtn');
      const closeInvoiceModal = document.querySelector('#invoiceModal .modal-close');
      const cancelInvoiceEdit = document.getElementById('cancelInvoiceEdit');
      const invoiceForm = document.getElementById('invoiceForm');
      const invoiceSearch = document.getElementById('invoiceSearch');

      addInvoiceBtn.addEventListener('click', function() {
        document.getElementById('invoiceModalTitle').textContent = 'Thêm hóa đơn mới';
        document.getElementById('paymentId').value = '';
        invoiceForm.reset();
        document.getElementById('paymentDate').value = new Date().toISOString().slice(0, 16);
        invoiceModal.style.display = 'block';
      });

      closeInvoiceModal.addEventListener('click', () => invoiceModal.style.display = 'none');
      cancelInvoiceEdit.addEventListener('click', () => invoiceModal.style.display = 'none');
      window.addEventListener('click', (e) => {
        if (e.target === invoiceModal) invoiceModal.style.display = 'none';
      });

      invoiceForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const paymentId = document.getElementById('paymentId').value;
        const formData = {
          bookingId: document.getElementById('bookingId').value,
          paymentDate: document.getElementById('paymentDate').value,
          amount: document.getElementById('amount').value,
          paymentMethod: document.getElementById('paymentMethod').value,
          paymentStatus: document.getElementById('paymentStatus').value
        };

        const url = paymentId 
          ? `/staff/invoice/${paymentId}` 
          : '/staff/invoice';
        
        const method = paymentId ? 'PUT' : 'POST';

        fetch(url, {
          method: method,
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          },
          body: JSON.stringify(formData)
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert(data.message);
            location.reload();
          } else {
            alert('Lỗi: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Có lỗi xảy ra khi lưu hóa đơn!');
        });
      });

      // Search functionality
      invoiceSearch.addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const rows = document.querySelectorAll('#invoiceTableBody tr');
        
        rows.forEach(row => {
          const text = row.textContent.toLowerCase();
          row.style.display = text.includes(searchTerm) ? '' : 'none';
        });
      });

      // Global functions
      window.editInvoice = function(id) {
        fetch(`/staff/invoice/${id}`, {
          headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
          }
        })
        .then(response => response.json())
        .then(payment => {
          document.getElementById('invoiceModalTitle').textContent = 'Chỉnh sửa hóa đơn';
          document.getElementById('paymentId').value = payment.PaymentID;
          document.getElementById('bookingId').value = payment.BookingID;
          document.getElementById('customerName').value = payment.CustomerName || 'N/A';
          document.getElementById('roomNumbers').value = payment.RoomNumbers || 'N/A';
          
          // Hiển thị chi phí phòng và chi phí dịch vụ
          const roomTotal = parseFloat(payment.RoomTotal) || 0;
          const serviceTotal = parseFloat(payment.ServiceTotal) || 0;
          document.getElementById('roomTotal').value = roomTotal.toLocaleString('vi-VN') + ' VNĐ';
          document.getElementById('serviceTotal').value = serviceTotal.toLocaleString('vi-VN') + ' VNĐ';
          
          // Format datetime for input
          const date = new Date(payment.PaymentDate);
          document.getElementById('paymentDate').value = date.toISOString().slice(0, 16);
          
          document.getElementById('amount').value = payment.Amount;
          document.getElementById('paymentMethod').value = payment.PaymentMethod;
          document.getElementById('paymentStatus').value = payment.PaymentStatus;

          invoiceModal.style.display = 'block';
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Có lỗi xảy ra khi tải thông tin hóa đơn!');
        });
      };

      window.deleteInvoice = function(id) {
        if (!confirm('Bạn có chắc chắn muốn xóa hóa đơn này?')) {
          return;
        }

        fetch(`/staff/invoice/${id}`, {
          method: 'DELETE',
          headers: {
            'X-CSRF-TOKEN': csrfToken,
            'Accept': 'application/json'
          }
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert(data.message);
            location.reload();
          } else {
            alert('Lỗi: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Error:', error);
          alert('Có lỗi xảy ra khi xóa hóa đơn!');
        });
      };

      window.printInvoice = function(id) {
        // In thực tế sẽ mở một trang mới với template hóa đơn để in
        alert('Đang chuẩn bị in hóa đơn #' + id);
        // Có thể redirect đến route in hóa đơn
        // window.open('/staff/invoice/' + id + '/print', '_blank');
      };
    });
  </script>
</body>
</html>

<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Quản lý đặt phòng - Resort</title>
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/staff.new.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <style>
    .staff-header {
      background: white;
      color: white;
      padding: 15px 30px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .staff-header h1 {
      margin: 0;
      font-size: 24px;
    }

    .staff-meta {
      display: flex;
      align-items: center;
      gap: 20px;
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
      font-size: 14px;
    }

    .dropdown-menu {
      display: none;
      position: absolute;
      right: 0;
      top: 100%;
      background: white;
      border-radius: 5px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      min-width: 180px;
      z-index: 1000;
      margin-top: 5px;
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

    .staff-table-container {
      padding: 20px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
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
      cursor: pointer;
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

    .btn-success {
      background: #2e7d32;
      color: white;
    }

    .btn-danger {
      background: #c62828;
      color: white;
    }

    .status-badge {
      padding: 4px 12px;
      border-radius: 15px;
      font-size: 12px;
      font-weight: 500;
      display: inline-block;
    }

    .status-pending {
      background: #fff3e0;
      color: #ef6c00;
    }

    .status-confirmed {
      background: #e8f5e9;
      color: #2e7d32;
    }

    .status-cancelled {
      background: #ffebee;
      color: #c62828;
    }

    .modal-overlay {
      display: none;
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background: rgba(0, 0, 0, 0.5);
      z-index: 1000;
      overflow-y: auto;
      padding: 20px;
    }

    .modal-overlay.active {
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .modal-content {
      position: relative;
      background: white;
      width: 90%;
      max-width: 900px;
      border-radius: 10px;
      box-shadow: 0 3px 10px rgba(0, 0, 0, 0.2);
      max-height: 90vh;
      overflow-y: auto;
    }

    .modal-header {
      background: #1d5a2e;
      color: white;
      padding: 20px;
      border-radius: 10px 10px 0 0;
      position: sticky;
      top: 0;
      z-index: 10;
    }

    .modal-header h2 {
      margin: 0;
      font-size: 24px;
    }

    .modal-close {
      position: absolute;
      right: 20px;
      top: 20px;
      font-size: 28px;
      cursor: pointer;
      color: white;
      background: none;
      border: none;
      width: 32px;
      height: 32px;
      display: flex;
      align-items: center;
      justify-content: center;
      border-radius: 50%;
      transition: background 0.2s;
    }

    .modal-close:hover {
      background: rgba(255,255,255,0.2);
    }

    .modal-body {
      padding: 20px;
    }

    .booking-detail-sections {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
      margin-bottom: 20px;
    }

    .detail-section {
      background: #f5f5f5;
      border-radius: 8px;
      padding: 15px;
    }

    .detail-section h3 {
      color: #1d5a2e;
      margin-top: 0;
      margin-bottom: 15px;
      font-size: 18px;
      border-bottom: 2px solid #1d5a2e;
      padding-bottom: 8px;
    }

    .detail-item {
      margin-bottom: 10px;
    }

    .detail-label {
      font-weight: 600;
      color: #455a64;
      display: inline-block;
      min-width: 120px;
    }

    .detail-value {
      color: #263238;
    }

    /* Style cho danh sách phòng trong modal */
    .rooms-list {
      background: #fff;
      border-radius: 8px;
      padding: 15px;
      margin-top: 15px;
    }

    .room-item {
      background: #f9f9f9;
      border: 1px solid #e0e0e0;
      border-radius: 5px;
      padding: 12px;
      margin-bottom: 10px;
    }

    .room-item:last-child {
      margin-bottom: 0;
    }

    .room-item-header {
      font-weight: 600;
      color: #1d5a2e;
      margin-bottom: 8px;
      font-size: 16px;
    }

    .room-item-detail {
      display: flex;
      justify-content: space-between;
      margin: 5px 0;
      font-size: 14px;
    }

    .modal-footer {
      padding: 15px 20px;
      border-top: 1px solid #e0e0e0;
      display: flex;
      gap: 10px;
      justify-content: flex-end;
      position: sticky;
      bottom: 0;
      background: white;
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

    .booking-stats {
      display: flex;
      gap: 20px;
      margin-bottom: 20px;
    }

    .stat-card {
      flex: 1;
      background: white;
      border-radius: 8px;
      padding: 15px;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .stat-card h4 {
      margin: 0 0 10px 0;
      color: #666;
      font-size: 14px;
    }

    .stat-card .stat-number {
      font-size: 32px;
      font-weight: bold;
      color: #1d5a2e;
    }

    .search-filter {
      display: flex;
      gap: 10px;
      align-items: center;
    }

    .search-filter select {
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 5px;
    }
  </style>
</head>
<body>
  <header class="staff-header">
    <h1>Quản lý đặt phòng</h1>
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
          <form action="{{ route('staff.staff.logout') }}" method="POST" style="display: inline;">
            @csrf
            <button type="submit" class="dropdown-item" style="background: none; border: none; width: 100%; text-align: left; cursor: pointer;">
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
          <a href="{{ route('staff.booking') }}" style="text-decoration: none; color: #1d5a2e; display: block; padding: 8px 12px; border-radius: 5px; background: rgba(29, 90, 46, 0.1); transition: all 0.3s;">
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
          <li><a href="{{ route('staff.booking') }}" class="active">Quản lý đặt phòng</a></li>
          <li><a href="{{ route('staff.customer') }}">Quản lý khách hàng</a></li>
          <li><a href="{{ route('staff.employee') }}">Quản lý nhân viên</a></li>
          <li><a href="{{ route('staff.service') }}">Quản lý dịch vụ</a></li>
          <li><a href="{{ route('staff.invoice') }}">Quản lý hóa đơn</a></li>
        </ul>
      </nav>

      <!-- Statistics Cards -->
      <div style="margin: 0 20px;">
        <div class="booking-stats">
      <div class="stat-card">
        <h4>Tổng đặt phòng</h4>
        <div class="stat-number" id="totalBookings">{{ $bookings->count() }}</div>
      </div>
      <div class="stat-card">
        <h4>Chờ xử lý</h4>
        <div class="stat-number" style="color: #ef6c00;" id="pendingBookings">{{ $bookings->where('Status', 'Pending')->count() }}</div>
      </div>
      <div class="stat-card">
        <h4>Đã xác nhận</h4>
        <div class="stat-number" style="color: #2e7d32;" id="confirmedBookings">{{ $bookings->where('Status', 'Confirmed')->count() }}</div>
      </div>
      <div class="stat-card">
        <h4>Đã hủy</h4>
        <div class="stat-number" style="color: #c62828;" id="cancelledBookings">{{ $bookings->where('Status', 'Cancelled')->count() }}</div>
      </div>
    </div>

    <div class="staff-table-container">
    <div class="staff-tools">
      <div class="search-filter">
        <input type="text" id="bookingSearch" placeholder="Tìm kiếm theo tên khách hàng, số phòng, email..." 
               style="padding: 8px; border: 1px solid #ddd; border-radius: 5px; width: 350px;">
        <select id="statusFilter">
          <option value="">Tất cả trạng thái</option>
          <option value="Pending">Chờ xử lý</option>
          <option value="Confirmed">Đã xác nhận</option>
          <option value="Cancelled">Đã hủy</option>
        </select>
      </div>
      <button id="refreshBtn" class="btn btn-primary" onclick="location.reload()">
        <i class="fas fa-sync-alt"></i> Làm mới
      </button>
    </div>

    <table class="staff-table">
      <thead>
        <tr>
          <th>Mã đặt phòng</th>
          <th>Tên khách hàng</th>
          <th>Giới tính</th>
          <th>SĐT</th>
          <th>Email</th>
          <th>Số phòng</th>
          <th>Trạng thái</th>
          <th>Tổng tiền</th>
        </tr>
      </thead>
      <tbody id="bookingTableBody">
        @forelse($bookings as $booking)
        <tr onclick='openBookingDetail(@json($booking))' style="cursor: pointer;">
          <td>{{ $booking->BookingID }}</td>
          <td>{{ $booking->FullName }}</td>
          <td>{{ $booking->Gender }}</td>
          <td>{{ $booking->Phone }}</td>
          <td>{{ $booking->Email }}</td>
          <td>{{ $booking->RoomNumber }}</td>
          <td>
            <span class="status-badge status-{{ strtolower($booking->Status) }}">
              @if($booking->Status === 'Pending')
                Chờ xử lý
              @elseif($booking->Status === 'Confirmed')
                Đã xác nhận
              @else
                Đã hủy
              @endif
            </span>
          </td>
          <td>{{ number_format($booking->TotalAmount, 0, ',', '.') }} VNĐ</td>
        </tr>
        @empty
        <tr>
          <td colspan="8" style="text-align: center; padding: 40px; color: #999;">Chưa có đặt phòng nào</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

    <!-- Modal chi tiết đặt phòng -->
  <div id="bookingDetailModal" class="modal-overlay" onclick="closeModalOnOutsideClick(event)">
    <div class="modal-content">
      <div class="modal-header">
        <h2>Chi tiết đặt phòng #<span id="modalBookingId"></span></h2>
        <button class="modal-close" onclick="closeBookingDetail()">&times;</button>
      </div>
      <div class="modal-body">
        <div class="booking-detail-sections">
          <!-- Thông tin đặt phòng -->
          <div class="detail-section">
            <h3>📝 Thông tin đặt phòng</h3>
            <div class="detail-item">
              <span class="detail-label">Mã đặt phòng:</span>
              <span class="detail-value" id="detailBookingId"></span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Ngày đặt:</span>
              <span class="detail-value" id="detailBookingDate"></span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Người lớn:</span>
              <span class="detail-value" id="detailAdults"></span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Trẻ em:</span>
              <span class="detail-value" id="detailChildren"></span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Tổng tiền:</span>
              <span class="detail-value" id="detailTotalAmount" style="font-weight: 700; color: #c62828;"></span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Trạng thái:</span>
              <span class="detail-value" id="detailStatus"></span>
            </div>
          </div>

          <!-- Thông tin khách hàng -->
          <div class="detail-section">
            <h3>👤 Thông tin khách hàng</h3>
            <div class="detail-item">
              <span class="detail-label">Họ tên:</span>
              <span class="detail-value" id="detailCustomerName"></span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Giới tính:</span>
              <span class="detail-value" id="detailGender"></span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Email:</span>
              <span class="detail-value" id="detailEmail"></span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Số điện thoại:</span>
              <span class="detail-value" id="detailPhone"></span>
            </div>
            <div class="detail-item">
              <span class="detail-label">CMND/CCCD:</span>
              <span class="detail-value" id="detailIDNumber"></span>
            </div>
            <div class="detail-item">
              <span class="detail-label">Địa chỉ:</span>
              <span class="detail-value" id="detailAddress"></span>
            </div>
          </div>
        </div>

        <!-- Danh sách phòng đã đặt -->
        <div class="rooms-list">
          <h3 style="color: #1d5a2e; margin-top: 0; margin-bottom: 15px;">🏠 Danh sách phòng đã đặt</h3>
          <div id="roomsList"></div>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" onclick="closeBookingDetail()">Đóng</button>
        <button class="btn btn-danger" id="btnReject" onclick="updateBookingStatus('Cancelled')">Từ chối</button>
        <button class="btn btn-success" id="btnConfirm" onclick="updateBookingStatus('Confirmed')">Xác nhận</button>
      </div>
    </div>
  </div>
  </div>

  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // User dropdown functionality
      const dropdownToggle = document.querySelector('.dropdown-toggle');
      const dropdownMenu = document.querySelector('.dropdown-menu');
      
      if (dropdownToggle && dropdownMenu) {
        dropdownToggle.addEventListener('click', function(e) {
          e.stopPropagation();
          dropdownMenu.classList.toggle('show');
        });
        
        document.addEventListener('click', function(e) {
          if (!e.target.closest('.user-dropdown')) {
            dropdownMenu.classList.remove('show');
          }
        });
      }

      // Search and filter functionality
      const bookingSearch = document.getElementById('bookingSearch');
      const statusFilter = document.getElementById('statusFilter');

      bookingSearch.addEventListener('input', filterBookings);
      statusFilter.addEventListener('change', filterBookings);

      function filterBookings() {
        const searchText = bookingSearch.value.toLowerCase();
        const statusValue = statusFilter.value;

        const tbody = document.getElementById('bookingTableBody');
        const rows = Array.from(tbody.querySelectorAll('tr'));

        rows.forEach(row => {
          if (row.querySelector('td[colspan]')) return; // Skip empty state row
          
          const text = row.textContent.toLowerCase();
          const statusBadge = row.querySelector('.status-badge');
          const matchesSearch = text.includes(searchText);
          const matchesStatus = !statusValue || (statusBadge && statusBadge.classList.contains(`status-${statusValue.toLowerCase()}`));

          row.style.display = matchesSearch && matchesStatus ? '' : 'none';
        });
      }
    });

    let currentBookingId = null;

    function openBookingDetail(booking) {
      currentBookingId = booking.BookingID;
      
      // Populate modal - Thông tin booking
      document.getElementById('modalBookingId').textContent = booking.BookingID;
      document.getElementById('detailBookingId').textContent = booking.BookingID;
      document.getElementById('detailBookingDate').textContent = formatDateTime(booking.BookingDate);
      document.getElementById('detailAdults').textContent = booking.AdultAmount;
      document.getElementById('detailChildren').textContent = booking.ChildAmount;
      document.getElementById('detailTotalAmount').textContent = formatMoney(booking.TotalAmount) + ' VNĐ';
      
      const statusMap = {
        'Pending': 'Chờ xử lý',
        'Confirmed': 'Đã xác nhận',
        'Cancelled': 'Đã hủy'
      };
      const statusClass = `status-badge status-${booking.Status.toLowerCase()}`;
      document.getElementById('detailStatus').innerHTML = `<span class="${statusClass}">${statusMap[booking.Status]}</span>`;

      // Customer info
      document.getElementById('detailCustomerName').textContent = booking.FullName;
      document.getElementById('detailGender').textContent = booking.Gender;
      document.getElementById('detailEmail').textContent = booking.Email;
      document.getElementById('detailPhone').textContent = booking.Phone;
      document.getElementById('detailIDNumber').textContent = booking.IDNumber || 'Chưa cập nhật';
      document.getElementById('detailAddress').textContent = booking.Address || 'Chưa cập nhật';

      // Fetch và hiển thị danh sách phòng từ BOOKING_ROOMS
      fetchBookingRooms(booking.BookingID);

      // Show/hide action buttons based on status
      const btnConfirm = document.getElementById('btnConfirm');
      const btnReject = document.getElementById('btnReject');
      
      if (booking.Status === 'Pending') {
        btnConfirm.style.display = 'inline-block';
        btnReject.style.display = 'inline-block';
      } else if (booking.Status === 'Confirmed') {
        btnConfirm.style.display = 'none';
        btnReject.style.display = 'inline-block';
      } else {
        btnConfirm.style.display = 'none';
        btnReject.style.display = 'none';
      }

      // Show modal
      document.getElementById('bookingDetailModal').classList.add('active');
    }

    // Hàm lấy danh sách phòng của booking
    async function fetchBookingRooms(bookingId) {
      const roomsList = document.getElementById('roomsList');
      roomsList.innerHTML = '<p style="text-align: center; color: #999;">Đang tải...</p>';

      try {
        const response = await fetch(`/staff/booking/${bookingId}/rooms`);
        const data = await response.json();

        if (data.success && data.rooms.length > 0) {
          let html = '';
          data.rooms.forEach((room, index) => {
            const checkIn = formatDate(room.CheckInDate);
            const checkOut = formatDate(room.CheckOutDate);
            const nights = calculateNights(room.CheckInDate, room.CheckOutDate);
            
            html += `
              <div class="room-item">
                <div class="room-item-header">📍 Phòng ${room.RoomNumber} - ${room.TypeName}</div>
                <div class="room-item-detail">
                  <span>Check-in:</span>
                  <strong>${checkIn}</strong>
                </div>
                <div class="room-item-detail">
                  <span>Check-out:</span>
                  <strong>${checkOut}</strong>
                </div>
                <div class="room-item-detail">
                  <span>Số đêm:</span>
                  <strong>${nights} đêm</strong>
                </div>
                <div class="room-item-detail">
                  <span>Giường đơn:</span>
                  <strong>${room.Single_Bed}</strong>
                </div>
                <div class="room-item-detail">
                  <span>Giường đôi:</span>
                  <strong>${room.Double_Bed}</strong>
                </div>
                <div class="room-item-detail">
                  <span>Trạng thái phòng:</span>
                  <strong>${room.RoomStatus}</strong>
                </div>
                <div class="room-item-detail">
                  <span>Giá phòng:</span>
                  <strong style="color: #c62828;">${formatMoney(room.TotalAmount)} VNĐ</strong>
                </div>
              </div>
            `;
          });
          roomsList.innerHTML = html;
        } else {
          roomsList.innerHTML = '<p style="text-align: center; color: #999;">Không có phòng nào</p>';
        }
      } catch (error) {
        console.error('Error fetching rooms:', error);
        roomsList.innerHTML = '<p style="text-align: center; color: #f44336;">Lỗi khi tải danh sách phòng</p>';
      }
    }

    // Hàm tính số đêm
    function calculateNights(checkIn, checkOut) {
      const start = new Date(checkIn);
      const end = new Date(checkOut);
      const diffTime = Math.abs(end - start);
      const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
      return diffDays;
    }

    function closeBookingDetail() {
      document.getElementById('bookingDetailModal').classList.remove('active');
      currentBookingId = null;
    }

    function closeModalOnOutsideClick(event) {
      if (event.target === event.currentTarget) {
        closeBookingDetail();
      }
    }

    function updateBookingStatus(status) {
      if (!currentBookingId) return;

      const statusMap = {
        'Confirmed': 'xác nhận',
        'Cancelled': 'từ chối'
      };

      if (!confirm(`Bạn có chắc chắn muốn ${statusMap[status]} đặt phòng này?`)) {
        return;
      }

      fetch(`/staff/booking/${currentBookingId}/status`, {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ status: status })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          alert(data.message);
          location.reload(); // Reload to show updated data
        } else {
          alert('Có lỗi xảy ra: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật trạng thái');
      });
    }

    function formatDate(dateString) {
      const date = new Date(dateString);
      const day = String(date.getDate()).padStart(2, '0');
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const year = date.getFullYear();
      return `${day}/${month}/${year}`;
    }

    function formatDateTime(dateString) {
      const date = new Date(dateString);
      const day = String(date.getDate()).padStart(2, '0');
      const month = String(date.getMonth() + 1).padStart(2, '0');
      const year = date.getFullYear();
      const hours = String(date.getHours()).padStart(2, '0');
      const minutes = String(date.getMinutes()).padStart(2, '0');
      return `${day}/${month}/${year} ${hours}:${minutes}`;
    }

    function formatMoney(amount) {
      return new Intl.NumberFormat('vi-VN').format(amount);
    }
  </script>
</body>
</html>

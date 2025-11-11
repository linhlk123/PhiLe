@extends('layouts.staff')

@section('title', 'Quản lý phòng - Resort')
@section('page-title', 'Quản lý phòng')

@section('styles')
<style>
  .room-types-grid {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 20px;
    padding: 20px;
  }

  .room-type-card {
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    padding: 20px;
    cursor: pointer;
    transition: transform 0.2s;
  }

  .room-type-card:hover {
    transform: translateY(-5px);
  }

  .room-type-header {
    margin-bottom: 15px;
  }

  .room-type-header h3 {
    color: #1d5a2e;
    margin: 0;
    font-size: 24px;
  }

  .room-count {
    color: #455a64;
    font-size: 18px;
    margin: 5px 0;
  }

  .room-status {
    display: flex;
    gap: 15px;
    margin-top: 10px;
  }

  .status-badge {
    padding: 4px 12px;
    border-radius: 15px;
    font-size: 14px;
  }

  .status-available {
    background: #e8f5e9;
    color: #2e7d32;
  }

  .status-occupied {
    background: #ffebee;
    color: #c62828;
  }

  .status-cleaning {
    background: #fff3e0;
    color: #ef6c00;
  }

  .status-maintenance {
    background: #e3f2fd;
    color: #1565c0;
  }

  .btn-map {
    display: block;
    width: 100%;
    padding: 10px;
    margin-top: 15px;
    background: #1d5a2e;
    color: white;
    text-align: center;
    text-decoration: none;
    border-radius: 5px;
    font-weight: 500;
    transition: background 0.3s;
  }

  .btn-map:hover {
    background: #283593;
  }

  .rooms-grid {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 15px;
    padding: 20px;
    display: none;
  }

  .room-box {
    border-radius: 8px;
    padding: 15px;
    color: white;
    min-height: 100px;
    position: relative;
    cursor: pointer;
    transition: transform 0.2s;
  }

  .room-box:hover {
    transform: scale(1.05);
  }

  .room-box.available {
    background: #4caf50;
  }

  .room-box.occupied {
    background: #f44336;
  }

  .room-box.booked {
    background: #d32f2f;
  }

  .room-box.cleaning {
    background: #ee9613;
  }

  .room-box.maintenance {
    background: #2196f3;
  }

  .room-number {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 10px;
  }

  .guest-info {
    font-size: 14px;
    opacity: 0.9;
  }

  .room-details {
    margin-top: 8px;
    font-size: 12px;
    opacity: 0.8;
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

  .form-group select,
  .form-group input {
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

  #backButton {
    background: #1d5a2e;
    color: white;
    border: none;
    padding: 8px 16px;
    border-radius: 5px;
    cursor: pointer;
    margin-bottom: 20px;
    display: none;
  }

  #backButton:hover {
    background: #283593;
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
</style>
@endsection

@section('content')

  <!-- Main content -->
  <div style="flex: 1;">
    <nav class="top-nav">
      <ul>
        <li><a href="{{ route('staff.staff-room') }}" class="active">Quản lý phòng</a></li>
        <li><a href="{{ route('staff.booking') }}">Quản lý đặt phòng</a></li>
        <li><a href="{{ route('staff.customer') }}">Quản lý khách hàng</a></li>
        <li><a href="{{ route('staff.employee') }}">Quản lý nhân viên</a></li>
        <li><a href="{{ route('staff.service') }}">Quản lý dịch vụ</a></li>
        <li><a href="{{ route('staff.invoice') }}">Quản lý hóa đơn</a></li>
      </ul>
    </nav>

    <div class="main-content">
      <button id="backButton">← Quay lại danh sách phòng</button>

      <div class="room-types-grid" id="roomTypesGrid">
        @foreach(['Standard', 'Superior', 'Deluxe', 'Villa', 'Service'] as $type)
        @php
          $stats = $roomStats[$type] ?? null;
          $total = $stats ? $stats->total : 0;
          $available = $stats ? $stats->available : 0;
          $occupied = $stats ? $stats->occupied : 0;
          $cleaning = $stats ? $stats->cleaning : 0;
          $maintenance = $stats ? $stats->maintenance : 0;
        @endphp
        <div class="room-type-card" data-type="{{ $type }}">
          <div class="room-type-header">
            <h3>{{ $type }}</h3>
            <div class="room-count">{{ $total }} phòng</div>
          </div>
          <div class="room-status">
            <span class="status-badge status-available">Trống: {{ $available }}</span>
            <span class="status-badge status-occupied">Đã đặt: {{ $occupied }}</span>
          </div>
          <div class="room-status" style="margin-top: 5px;">
            <span class="status-badge status-cleaning">Đang dọn dẹp: {{ $cleaning }}</span>
            <span class="status-badge status-maintenance">Đang bảo trì: {{ $maintenance }}</span>
          </div>
          <a href="{{ route('staff.map') }}" class="btn-map">🗺️ Xem bản đồ</a>
        </div>
        @endforeach
      </div>

      <div id="roomsGrid" class="rooms-grid">
        <!-- Room boxes will be inserted here by JavaScript -->
      </div>
    </div>

    <!-- Modal for editing room -->
    <div id="roomModal" class="modal">
      <div class="modal-content">
        <span class="modal-close">&times;</span>
        <div class="modal-header">
          <h3>Chỉnh sửa thông tin phòng <span id="modalRoomNumber"></span></h3>
        </div>
        <form id="roomEditForm" action="{{ route('staff.room.update') }}" method="POST">
          @csrf
          <input type="hidden" id="editRoomId" name="roomId">
          <div class="form-group">
            <label for="editStatus">Trạng thái</label>
            <select id="editStatus" name="status" required>
              <option value="Available">Trống</option>
              <option value="Occupied">Đã đặt</option>
              <option value="Cleaning">Đang dọn dẹp</option>
              <option value="Maintenance">Đang bảo trì</option>
            </select>
          </div>
          <div class="form-group">
            <label for="editSingleBeds">Số giường đơn</label>
            <input type="number" id="editSingleBeds" name="singleBeds" min="0" max="5" required>
          </div>
          <div class="form-group">
            <label for="editDoubleBeds">Số giường đôi</label>
            <input type="number" id="editDoubleBeds" name="doubleBeds" min="0" max="5" required>
          </div>
          <div id="guestInfoSection">
            <div class="form-group">
              <label for="editGuestName">Tên khách hàng</label>
              <input type="text" id="editGuestName" name="guestName">
            </div>
            <div class="form-group">
              <label for="editCheckIn">Ngày check-in</label>
              <input type="date" id="editCheckIn" name="checkIn">
            </div>
            <div class="form-group">
              <label for="editCheckOut">Ngày check-out</label>
              <input type="date" id="editCheckOut" name="checkOut">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" id="cancelEdit">Hủy</button>
            <button type="submit" class="btn btn-primary">Lưu thay đổi</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@section('scripts')
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

  const roomTypesGrid = document.getElementById('roomTypesGrid');
  const roomsGrid = document.getElementById('roomsGrid');
  const backButton = document.getElementById('backButton');
  const modal = document.getElementById('roomModal');
  const modalClose = document.querySelector('.modal-close');
  const cancelEdit = document.getElementById('cancelEdit');
  const roomEditForm = document.getElementById('roomEditForm');
  const guestInfoSection = document.getElementById('guestInfoSection');
  const editStatus = document.getElementById('editStatus');

  // Xử lý click vào loại phòng
  const roomTypeCards = document.querySelectorAll('.room-type-card');
  roomTypeCards.forEach(card => {
    card.addEventListener('click', function(e) {
      // Nếu click vào button "Xem bản đồ", không xử lý
      if (e.target.closest('.btn-map')) {
        return;
      }
      
      const roomType = this.getAttribute('data-type');
      
      roomTypesGrid.style.display = 'none';
      roomsGrid.style.display = 'grid';
      backButton.style.display = 'block';

      fetch(`/staff/rooms/${roomType}`)
      .then(res => res.json())
      .then(rooms => {
        roomsGrid.innerHTML = ''; // Clear cũ

        rooms.forEach(room => {
          const div = document.createElement('div');
          div.className = `room-box ${room.Status.toLowerCase()}`;
          div.innerHTML = `
            <div class="room-number">${room.RoomNumber}</div>
            <div class="guest-info">${room.Status}</div>
            <div class="room-details">
              Giường đơn: ${room.Single_Bed} | Giường đôi: ${room.Double_Bed}
            </div>
          `;
          div.addEventListener('click', () => openEditModal(room));
          roomsGrid.appendChild(div);
        });
      })
      .catch(err => {
        console.error('Lỗi khi tải phòng:', err);
      });

    });
  });

  // Xử lý nút quay lại
  backButton.addEventListener('click', function() {
    roomsGrid.style.display = 'none';
    roomTypesGrid.style.display = 'grid';
    backButton.style.display = 'none';
  });

  // Xử lý đóng modal
  function closeModal() {
    modal.style.display = 'none';
  }

  modalClose.addEventListener('click', closeModal);
  cancelEdit.addEventListener('click', closeModal);
  window.addEventListener('click', (e) => {
    if (e.target === modal) closeModal();
  });

  // Xử lý hiện/ẩn thông tin khách hàng dựa trên trạng thái
  editStatus.addEventListener('change', function() {
    if (this.value === 'Occupied') {
      guestInfoSection.style.display = 'block';
      document.getElementById('editGuestName').required = true;
      document.getElementById('editCheckIn').required = true;
      document.getElementById('editCheckOut').required = true;
    } else {
      guestInfoSection.style.display = 'none';
      document.getElementById('editGuestName').required = false;
      document.getElementById('editCheckIn').required = false;
      document.getElementById('editCheckOut').required = false;
    }
  });

  function displayRooms(rooms) {
    rooms.forEach(room => {
      const roomBox = document.createElement('div');
      roomBox.className = `room-box ${room.Status.toLowerCase()}`;
      
      let content = `
        <div class="room-number">${room.RoomNumber}</div>
      `;

      if (room.Status === 'Occupied' && room.guestName) {
        const checkInDate = new Date(room.checkInDate).toLocaleDateString('vi-VN');
        content += `
          <div class="guest-info">
            Khách: ${room.guestName}<br>
            Check-in: ${checkInDate}
          </div>
        `;
      } else if (room.Status === 'Cleaning') {
        content += '<div class="guest-info">Đang dọn dẹp</div>';
      } else if (room.Status === 'Maintenance') {
        content += '<div class="guest-info">Đang bảo trì</div>';
      }

      content += `
        <div class="room-details">
          ${room.Single_Bed} giường đơn, ${room.Double_Bed} giường đôi
        </div>
      `;

      roomBox.innerHTML = content;
      roomBox.title = `Phòng ${room.RoomNumber} - ${room.Status}`;
      
      roomBox.addEventListener('click', () => {
        openEditModal(room);
      });

      roomsGrid.appendChild(roomBox);
    });
  }

  // Mở modal và điền thông tin phòng
  function openEditModal(room) {
    document.getElementById('modalRoomNumber').textContent = room.RoomNumber;
    document.getElementById('editRoomId').value = room.RoomID;
    document.getElementById('editStatus').value = room.Status;
    document.getElementById('editSingleBeds').value = room.Single_Bed;
    document.getElementById('editDoubleBeds').value = room.Double_Bed;
    document.getElementById('editGuestName').value = room.guestName || '';
    
    if (room.checkInDate) {
      document.getElementById('editCheckIn').value = room.checkInDate.split('T')[0];
    } else {
      document.getElementById('editCheckIn').value = '';
    }
    
    if (room.checkOutDate) {
      document.getElementById('editCheckOut').value = room.checkOutDate.split('T')[0];
    } else {
      document.getElementById('editCheckOut').value = '';
    }

    if (room.Status === 'Occupied') {
      guestInfoSection.style.display = 'block';
      document.getElementById('editGuestName').required = true;
      document.getElementById('editCheckIn').required = true;
      document.getElementById('editCheckOut').required = true;
    } else {
      guestInfoSection.style.display = 'none';
      document.getElementById('editGuestName').required = false;
      document.getElementById('editCheckIn').required = false;
      document.getElementById('editCheckOut').required = false;
    }

    modal.style.display = 'block';
  }

  // Xử lý submit form
  roomEditForm.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    fetch(this.action, {
      method: 'POST',
      body: formData,
      headers: {
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
      }
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Đã cập nhật thông tin phòng thành công!');
        closeModal();
        // Refresh danh sách phòng
        const roomType = document.querySelector('.room-type-card[style*="none"]')?.getAttribute('data-type');
        if (roomType) {
          fetch(`/staff/rooms/${roomType}`)
            .then(response => response.json())
            .then(rooms => {
              roomsGrid.innerHTML = '';
              displayRooms(rooms);
            });
        }
      } else {
        alert('Có lỗi xảy ra: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error:', error);
      alert('Có lỗi xảy ra khi cập nhật thông tin phòng');
    });
  });
});
</script>
@endsection
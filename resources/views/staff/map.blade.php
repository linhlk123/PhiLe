<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sơ đồ phòng resort Leviosa</title>
  <link rel="stylesheet" href="{{ asset('assets/css/map.css') }}">
  <style>
    .house {
      position: relative;
      cursor: pointer;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: bold;
      font-size: 8px;
      color: #333;
    }
    .house.disabled {
      cursor: not-allowed;
      opacity: 0.3;
    }
    /* Giữ nguyên màu gốc cho phòng available */
    .status-menu {
      display: none;
      position: fixed;
      background: white;
      border: 1px solid #ccc;
      border-radius: 8px;
      padding: 10px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.2);
      z-index: 1000;
    }
    .status-menu.active {
      display: block;
    }
    .status-menu ul {
      list-style: none;
      padding: 0;
      margin: 0;
    }
    .status-menu li {
      padding: 8px 12px;
      cursor: pointer;
      border-radius: 4px;
    }
    .status-menu li:hover {
      background-color: #f0f0f0;
    }
  </style>
</head>
<body>
  <h1>SƠ ĐỒ PHÒNG RESORT LEVIOSA</h1>

  <div class="legend">
    <div><span class="icon garden"></span> Khu A: Leviosa Garden (Standard)</div>
    <div><span class="icon lake"></span> Khu B: Leviosa Lake View (Superior)</div>
    <div><span class="icon beach"></span> Khu C: Leviosa Beach Villas (Deluxe)</div>
    <div><span class="icon royal"></span> Khu D: Leviosa Royal (Villa)</div>
    <div><span class="icon service"></span> Khu E: Khu dịch vụ</div>
  </div>

  <!-- Hộp thông báo chọn phòng -->
  <div id="room-selection" class="room-selection-box" style="display: none;">
    <div class="selection-content">
      <h3>Bạn đang chọn phòng</h3>
      <p class="room-list"><span id="selected-room-number"></span></p>
      <p class="room-type-info">Loại phòng: <span id="selected-room-type"></span></p>
      <p class="help-text">💡 Nhấn vào phòng để thêm/bỏ chọn</p>
      <div class="selection-buttons">
        <button id="confirm-btn" class="btn-confirm">Xác nhận chọn phòng</button>
        <button id="cancel-btn" class="btn-cancel">Hủy tất cả</button>
      </div>
    </div>
  </div>

  <div class="map-container">
    <div class="map">
    <!-- Màu xanh lơ -->
    <!-- Khu A (hàng trên cùng) -->
    <div class="row-top"> 
      <div class="house service"></div> <div class="house service"></div> <div class="house service"></div>
      <div class="house service"></div> <div class="house service"></div> <div class="house service"></div>
      <div class="house service"></div> <div class="house service"></div> <div class="house service"></div>
      <div class="house service"></div> <div class="house service"></div> <div class="house service"></div>
      <div class="house service"></div> <div class="house service"></div> <div class="house service"></div>
      <div class="house service"></div> <div class="house service"></div> <div class="house service"></div>
      <div class="house service"></div> <div class="house service"></div> <div class="house service"></div>
      <div class="house service"></div> <div class="house service"></div> <div class="house service"></div>
      <div class="house service"></div>
    </div>

    <!-- Khu giữa -->
    <div class="middle">

      <!-- Khu màu xanh tím + vàng -->
      <div class="left-group">
        <!-- Trái: Standard + Deluxe -->
        <div class="left-standard">
          <div class="standard-Lrow1"> 
            <div class="house standard"></div> <div class="house standard"></div> <div class="house standard"></div>
            <div class="house standard"></div> <div class="house standard"></div>
          </div>
          <div class="standard-Lrow2"> 
            <div class="house standard"></div> <div class="house standard"></div> <div class="house standard"></div>
            <div class="house standard"></div> <div class="house standard"></div>
          </div>
          <div class="standard-Lrow3"> 
            <div class="house standard"></div> <div class="house standard"></div> <div class="house standard"></div>
            <div class="house standard"></div> <div class="house standard"></div>
          </div>
          <div class="standard-Lrow4"> 
            <div class="house standard"></div> <div class="house standard"></div> <div class="house standard"></div>
            <div class="house standard"></div> <div class="house standard"></div>
          </div>

          <div class="standard-Lrow5"> 
            <div class="house standard"></div> <div class="house standard"></div> <div class="house standard"></div>
            <div class="house standard"></div> <div class="house standard"></div>
          </div>
          <div class="standard-Lrow6"> 
            <div class="house standard"></div> <div class="house standard"></div> <div class="house standard"></div>
            <div class="house standard"></div> <div class="house standard"></div>
          </div>
          <div class="standard-Lrow7"> 
            <div class="house standard"></div> <div class="house standard"></div> <div class="house standard"></div>
            <div class="house standard"></div> <div class="house standard"></div>
          </div>
          <div class="standard-Lrow8"> 
            <div class="house standard"></div> <div class="house standard"></div> <div class="house standard"></div>
            <div class="house standard"></div> <div class="house standard"></div>
          </div>
        </div>

        <div class="left-deluxe">
          <div class="deluxe-Lrow1"> 
            <div class="house deluxe"></div> <div class="house deluxe"></div> <div class="house deluxe"></div>
          </div>
          <div class="deluxe-Lrow2"> 
              <div class="house deluxe"></div> <div class="house deluxe"></div> <div class="house deluxe"></div>
          </div>
          <div class="deluxe-Lrow3"> 
              <div class="house deluxe"></div> <div class="house deluxe"></div>
          </div>
          <div class="deluxe-Lrow4"> 
              <div class="house deluxe"></div> <div class="house deluxe"></div>
          </div>

          <div class="deluxe-Lrow5"> 
            <div class="house deluxe"></div> <div class="house deluxe"></div> <div class="house deluxe"></div>
          </div>
          <div class="deluxe-Lrow6"> 
              <div class="house deluxe"></div> <div class="house deluxe"></div> <div class="house deluxe"></div>  
          </div>
          <div class="deluxe-Lrow7"> 
              <div class="house deluxe"></div> <div class="house deluxe"></div>
          </div>
          <div class="deluxe-Lrow8"> 
              <div class="house deluxe"></div> <div class="house deluxe"></div>
          </div>
        </div>
        
      </div>

      <!-- Khu màu đỏ -->
      <!-- Giữa: Royal hình chữ T -->
      <div class="center-group"> 
        <div class="royal-row1"> <!-- Hàng 1 -->
          <div class="house royal"></div><div class="house royal"></div><div class="house royal"></div>
          <div class="house royal"></div><div class="house royal"></div><div class="house royal"></div>

          <div class="house royal"></div><div class="house royal"></div><div class="house royal"></div>
          <div class="house royal"></div><div class="house royal"></div><div class="house royal"></div>
        </div>
        <div class="royal-row2"> <!-- Hàng 2 -->
          <div class="house royal"></div><div class="house royal"></div><div class="house royal"></div>
          <div class="house royal"></div><div class="house royal"></div><div class="house royal"></div>

          <div class="house royal"></div><div class="house royal"></div><div class="house royal"></div>
          <div class="house royal"></div><div class="house royal"></div><div class="house royal"></div>
        </div>

        <div class="royal-row3"> <!-- Hàng 3 -->
          <div class="house royal"></div>
          <div class="house royal"></div>
          <div class="house royal"></div>
          <div class="house royal"></div>
        </div>
        <div class="royal-row4"> <!-- Hàng 4 -->
          <div class="house royal"></div>
          <div class="house royal"></div>
          <div class="house royal"></div>
          <div class="house royal"></div>
        </div>
        <div class="royal-row5"> <!-- Hàng 5 -->
          <div class="house royal"></div>
          <div class="house royal"></div>
          <div class="house royal"></div>
          <div class="house royal"></div>
        </div>
        <div class="royal-row6"> <!-- Hàng 6 -->
          <div class="house royal"></div>
          <div class="house royal"></div>
          <div class="house royal"></div>
          <div class="house royal"></div>
        </div>
        <div class="royal-row7"> <!-- Hàng 7 -->
          <div class="house royal"></div>
          <div class="house royal"></div>
          <div class="house royal"></div>
          <div class="house royal"></div>
        </div>

        <div class="royal-row8"> <!-- Hàng 8 -->
          <div class="house royal"></div>
          <div class="house royal"></div>
        </div>
        <div class="royal-row9"> <!-- Hàng 9 -->
          <div class="house royal"></div>
          <div class="house royal"></div>
        </div>
        <div class="royal-row10"> <!-- Hàng 10 -->
          <div class="house royal"></div>
          <div class="house royal"></div>
        </div>
      </div>

      <div class="right-group">
        <!-- Phải: Superior + Deluxe -->
        <div class="right-superior">
          <div class="superior-Rrow1"> 
            <div class="house superior"></div> <div class="house superior"></div> <div class="house superior"></div>
            <div class="house superior"></div> <div class="house superior"></div>
          </div>
          <div class="superior-Rrow2"> 
            <div class="house superior"></div> <div class="house superior"></div> <div class="house superior"></div>
            <div class="house superior"></div> <div class="house superior"></div>
          </div>
          <div class="superior-Rrow3"> 
            <div class="house superior"></div> <div class="house superior"></div> <div class="house superior"></div>
            <div class="house superior"></div> <div class="house superior"></div>
          </div>
          <div class="superior-Rrow4"> 
            <div class="house superior"></div> <div class="house superior"></div> <div class="house superior"></div>
            <div class="house superior"></div> <div class="house superior"></div>
          </div>

          <div class="superior-Rrow5"> 
            <div class="house superior"></div> <div class="house superior"></div> <div class="house superior"></div>
            <div class="house superior"></div> <div class="house superior"></div>
          </div>
          <div class="superior-Rrow6"> 
            <div class="house superior"></div> <div class="house superior"></div> <div class="house superior"></div>
            <div class="house superior"></div> <div class="house superior"></div>
          </div>
          <div class="superior-Rrow7"> 
            <div class="house superior"></div> <div class="house superior"></div> <div class="house superior"></div>
            <div class="house superior"></div> <div class="house superior"></div>
          </div>
          <div class="superior-Rrow8"> 
            <div class="house superior"></div> <div class="house superior"></div> <div class="house superior"></div>
            <div class="house superior"></div> <div class="house superior"></div>
          </div>
        </div>

        <div class="right-deluxe">
          <div class="deluxe-Rrow1"> 
            <div class="house deluxe"></div> <div class="house deluxe"></div> <div class="house deluxe"></div>
          </div>
          <div class="deluxe-Rrow2"> 
              <div class="house deluxe"></div> <div class="house deluxe"></div> <div class="house deluxe"></div>
          </div>
          <div class="deluxe-Rrow3"> 
              <div class="house deluxe"></div> <div class="house deluxe"></div>
          </div>
          <div class="deluxe-Rrow4"> 
              <div class="house deluxe"></div> <div class="house deluxe"></div>
          </div>

          <div class="deluxe-Rrow5"> 
            <div class="house deluxe"></div> <div class="house deluxe"></div> <div class="house deluxe"></div>
          </div>
          <div class="deluxe-Rrow6"> 
              <div class="house deluxe"></div> <div class="house deluxe"></div> <div class="house deluxe"></div>  
          </div>
          <div class="deluxe-Rrow7"> 
              <div class="house deluxe"></div> <div class="house deluxe"></div>
          </div>
          <div class="deluxe-Rrow8"> 
              <div class="house deluxe"></div> <div class="house deluxe"></div>
          </div>
        </div>
      </div>

    </div>

    <!-- Hàng dưới: thêm Lake + Beach  -->
    <div class="container">
      <div class="block lake"></div>
      <div class="block beach"></div>
      <div class="block service"></div>
    </div>
  </div>

  <!-- Menu cập nhật trạng thái -->
  <div id="status-menu" class="status-menu">
    <h4>Cập nhật trạng thái phòng</h4>
    <ul>
      <li data-status="available">🟢 Khả dụng</li>
      <li data-status="booking">🏨 Đặt phòng cho khách</li>
      <li data-status="maintenance">🔧 Đang bảo trì</li>
      <li data-status="cleaning">🧹 Đang dọn dẹp</li>
    </ul>
  </div>

  <script>
    // Dữ liệu phòng từ server
    const roomsData = @json($roomsData ?? []);
    
    // Mapping loại phòng với class CSS
    const roomTypeMap = {
      'Standard': 'standard',
      'Superior': 'superior',
      'Deluxe': 'deluxe',
      'Villa': 'royal',
      'Service': 'service'
    };
    
    // Mapping status với class CSS
    const statusMap = {
      'Available': 'available',
      'Busy': 'busy',
      'Maintenance': 'maintenance',
      'Cleaning': 'cleaning'
    };
    
    // Hàm khởi tạo số phòng
    function initializeRooms() {
      // Định nghĩa số phòng cho từng khu và tầng
      const roomLayout = {
        // Khu Service (E) - hàng trên
        service: ['E-101', 'E-102', 'E-103', 'E-104', 'E-105', 'E-106', 'E-107', 'E-108', 'E-109', 'E-110', 
                  'E-111', 'E-112', 'E-113', 'E-114', 'E-115', 'E-116', 'E-117', 'E-118', 'E-119', 'E-120',
                  'E-121', 'E-122', 'E-123', 'E-124', 'E-125'],
        
        // Khu Standard (A) - bên trái
        standard: [
          // Tầng 1
          'A-101', 'A-102', 'A-103', 'A-104', 'A-105',
          // Tầng 2
          'A-201', 'A-202', 'A-203', 'A-204', 'A-205',
          // Tầng 3
          'A-301', 'A-302', 'A-303', 'A-304', 'A-305',
          // Tầng 4
          'A-401', 'A-402', 'A-403', 'A-404', 'A-405',
          // Tầng 5
          'A-501', 'A-502', 'A-503', 'A-504', 'A-505',
          // Tầng 6
          'A-601', 'A-602', 'A-603', 'A-604', 'A-605',
          // Tầng 7
          'A-701', 'A-702', 'A-703', 'A-704', 'A-705',
          // Tầng 8
          'A-801', 'A-802', 'A-803', 'A-804', 'A-805'
        ],
        
        // Khu Deluxe bên trái (C)
        deluxeLeft: [
          'C-101', 'C-102', 'C-103',
          'C-104', 'C-105', 'C-106',
          'C-107', 'C-108',
          'C-109', 'C-110',
          'C-201', 'C-202', 'C-203',
          'C-204', 'C-205', 'C-206',
          'C-207', 'C-208',
          'C-209', 'C-210'
        ],
        
        // Khu Royal/Villa (D) - giữa
        royal: [
          // Hàng 1-2 (mỗi hàng 12 phòng)
          'D-101', 'D-102', 'D-103', 'D-104', 'D-105', 'D-106', 'D-107', 'D-108', 'D-109', 'D-110', 'D-111', 'D-112',
          'D-201', 'D-202', 'D-203', 'D-204', 'D-205', 'D-206', 'D-207', 'D-208', 'D-209', 'D-210', 'D-211', 'D-212',
          // Hàng 3-7 (mỗi hàng 4 phòng)
          'D-301', 'D-302', 'D-303', 'D-304',
          'D-401', 'D-402', 'D-403', 'D-404',
          'D-501', 'D-502', 'D-503', 'D-504',
          'D-601', 'D-602', 'D-603', 'D-604',
          'D-701', 'D-702', 'D-703', 'D-704',
          // Hàng 8-10 (mỗi hàng 2 phòng)
          'D-801', 'D-802',
          'D-901', 'D-902',
          'D-1001', 'D-1002'
        ],
        
        // Khu Superior (B) - bên phải
        superior: [
          'B-101', 'B-102', 'B-103', 'B-104', 'B-105',
          'B-201', 'B-202', 'B-203', 'B-204', 'B-205',
          'B-301', 'B-302', 'B-303', 'B-304', 'B-305',
          'B-401', 'B-402', 'B-403', 'B-404', 'B-405',
          'B-501', 'B-502', 'B-503', 'B-504', 'B-505',
          'B-601', 'B-602', 'B-603', 'B-604', 'B-605',
          'B-701', 'B-702', 'B-703', 'B-704', 'B-705',
          'B-801', 'B-802', 'B-803', 'B-804', 'B-805'
        ],
        
        // Khu Deluxe bên phải (C)
        deluxeRight: [
          'C-301', 'C-302', 'C-303',
          'C-304', 'C-305', 'C-306',
          'C-307', 'C-308',
          'C-309', 'C-310',
          'C-401', 'C-402', 'C-403',
          'C-404', 'C-405', 'C-406',
          'C-407', 'C-408',
          'C-409', 'C-410'
        ]
      };
      
      // Gán số phòng cho Service
      const serviceHouses = document.querySelectorAll('.row-top .house.service');
      serviceHouses.forEach((house, index) => {
        if (roomLayout.service[index]) {
          assignRoomToHouse(house, roomLayout.service[index], 'Service');
        }
      });
      
      // Gán số phòng cho Standard
      const standardHouses = document.querySelectorAll('.left-standard .house.standard');
      standardHouses.forEach((house, index) => {
        if (roomLayout.standard[index]) {
          assignRoomToHouse(house, roomLayout.standard[index], 'Standard');
        }
      });
      
      // Gán số phòng cho Deluxe bên trái
      const deluxeLeftHouses = document.querySelectorAll('.left-deluxe .house.deluxe');
      deluxeLeftHouses.forEach((house, index) => {
        if (roomLayout.deluxeLeft[index]) {
          assignRoomToHouse(house, roomLayout.deluxeLeft[index], 'Deluxe');
        }
      });
      
      // Gán số phòng cho Royal
      const royalHouses = document.querySelectorAll('.center-group .house.royal');
      royalHouses.forEach((house, index) => {
        if (roomLayout.royal[index]) {
          assignRoomToHouse(house, roomLayout.royal[index], 'Villa');
        }
      });
      
      // Gán số phòng cho Superior
      const superiorHouses = document.querySelectorAll('.right-superior .house.superior');
      superiorHouses.forEach((house, index) => {
        if (roomLayout.superior[index]) {
          assignRoomToHouse(house, roomLayout.superior[index], 'Superior');
        }
      });
      
      // Gán số phòng cho Deluxe bên phải
      const deluxeRightHouses = document.querySelectorAll('.right-deluxe .house.deluxe');
      deluxeRightHouses.forEach((house, index) => {
        if (roomLayout.deluxeRight[index]) {
          assignRoomToHouse(house, roomLayout.deluxeRight[index], 'Deluxe');
        }
      });
    }
    
    // Hàm gán phòng cho element
    function assignRoomToHouse(house, roomNumber, roomType) {
      house.textContent = roomNumber;
      house.dataset.roomNumber = roomNumber;
      house.dataset.roomType = roomType;
      
      // Kiểm tra xem phòng có trong database không
      if (roomsData[roomNumber]) {
        const room = roomsData[roomNumber];
        house.dataset.roomId = room.id;
        house.dataset.status = room.status;
        
        // Thêm class status (busy, maintenance, cleaning) - KHÔNG thêm available
        if (room.status === 'Busy') {
          house.classList.add('busy');
        } else if (room.status === 'Maintenance') {
          house.classList.add('maintenance');
        } else if (room.status === 'Cleaning') {
          house.classList.add('cleaning');
        }
        // Available giữ nguyên màu gốc của khu (không thêm class)
        
        // Chỉ cho phép tương tác với phòng Available
        if (room.status !== 'Available') {
          house.classList.add('disabled');
        }
      } else {
        // Phòng không có trong database, mặc định là available (giữ màu gốc)
        house.dataset.status = 'Available';
      }
    }
    
    // Menu ngữ cảnh
    const statusMenu = document.getElementById('status-menu');
    let currentRoom = null;
    
    // Xử lý click chuột phải
    document.addEventListener('contextmenu', function(e) {
      const house = e.target.closest('.house');
      if (house && house.dataset.roomNumber && !house.classList.contains('disabled')) {
        e.preventDefault();
        
        currentRoom = house;
        statusMenu.style.left = e.pageX + 'px';
        statusMenu.style.top = e.pageY + 'px';
        statusMenu.classList.add('active');
      }
    });
    
    // Ẩn menu khi click ra ngoài
    document.addEventListener('click', function(e) {
      if (!e.target.closest('#status-menu')) {
        statusMenu.classList.remove('active');
      }
    });
    
    // Xử lý chọn status
    document.querySelectorAll('#status-menu li').forEach(li => {
      li.addEventListener('click', function() {
        const newStatus = this.dataset.status;
        
        if (newStatus === 'booking' && currentRoom) {
          // Điều hướng đến trang booking với room number
          const roomNumber = currentRoom.dataset.roomNumber;
          window.location.href = "{{ route('booking.room') }}?room=" + roomNumber;
        } else if (currentRoom) {
          // Cập nhật status khác
          updateRoomStatus(currentRoom, newStatus);
        }
        
        statusMenu.classList.remove('active');
      });
    });
    
    // Hàm cập nhật status phòng
    function updateRoomStatus(house, status) {
      const roomId = house.dataset.roomId;
      
      // Gọi API cập nhật status
      fetch('/staff/room/update', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
          roomId: roomId,
          status: status
        })
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          // Cập nhật UI - xóa tất cả class status cũ
          house.classList.remove('busy', 'maintenance', 'cleaning', 'disabled');
          
          // Thêm class status mới (nếu không phải available)
          if (status === 'booking') {
            house.classList.add('busy');
            house.classList.add('disabled');
          } else if (status === 'maintenance') {
            house.classList.add('maintenance');
            house.classList.add('disabled');
          } else if (status === 'cleaning') {
            house.classList.add('cleaning');
            house.classList.add('disabled');
          }
          // available: giữ nguyên màu gốc của khu
          
          house.dataset.status = status;
          
          alert('Cập nhật trạng thái phòng thành công!');
        } else {
          alert('Có lỗi xảy ra: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Error:', error);
        alert('Có lỗi xảy ra khi cập nhật trạng thái phòng');
      });
    }
    
    // Khởi tạo khi trang load
    document.addEventListener('DOMContentLoaded', initializeRooms);
  </script>
</body>
</html>

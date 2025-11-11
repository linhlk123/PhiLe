// Cấu hình phòng cho mỗi khu
const roomConfig = {
  service: { prefix: 'E', name: 'Dịch vụ' },
  standard: { prefix: 'A', name: 'Leviosa Garden (Standard)' },
  deluxe: { prefix: 'C', name: 'Leviosa Beach Villas (Deluxe)' },
  superior: { prefix: 'B', name: 'Leviosa Lake View (Superior)' },
  royal: { prefix: 'D', name: 'Leviosa Royal (Villa)' }
};

// Đếm số phòng theo từng loại và tầng
const roomCounters = {
  service: {},
  standard: {},
  deluxe: {},
  superior: {},
  royal: {}
};

// Biến lưu các phòng đang được chọn (array)
let selectedRooms = [];

// Hàm lấy số phòng dựa trên loại và hàng
function getRoomNumber(type, rowIndex) {
  const config = roomConfig[type];
  if (!config) return '';
  
  const floor = rowIndex + 1; // Lầu 1, 2, 3...
  
  // Khởi tạo counter cho tầng nếu chưa có
  if (!roomCounters[type][floor]) {
    roomCounters[type][floor] = 1;
  }
  
  const roomNum = roomCounters[type][floor];
  roomCounters[type][floor]++;
  
  // Format: A-101, A-102, A-201, A-202...
  return `${config.prefix}-${floor}${String(roomNum).padStart(2, '0')}`;
}

// Gán số phòng cho tất cả các nhà
document.querySelectorAll('.house').forEach((house, index) => {
  // Xác định loại phòng
  let type = '';
  if (house.classList.contains('service')) type = 'service';
  else if (house.classList.contains('standard')) type = 'standard';
  else if (house.classList.contains('deluxe')) type = 'deluxe';
  else if (house.classList.contains('superior')) type = 'superior';
  else if (house.classList.contains('royal')) type = 'royal';
  
  // Tìm hàng (row) của phòng này
  const parent = house.parentElement;
  const parentClasses = parent.className;
  
  // Lấy số hàng từ tên class (vd: standard-Lrow1 -> 0, standard-Lrow2 -> 1)
  let rowIndex = 0;
  const rowMatch = parentClasses.match(/row(\d+)/);
  if (rowMatch) {
    rowIndex = parseInt(rowMatch[1]) - 1;
  }
  
  // Sinh số phòng
  const roomNumber = getRoomNumber(type, rowIndex);
  house.setAttribute('data-room', roomNumber);
  house.setAttribute('data-type', type);
  
  // Cập nhật title
  const config = roomConfig[type];
  house.title = `${roomNumber} - ${config.name}`;
  
  // Thêm sự kiện click chuột phải để hiện menu
  house.addEventListener('contextmenu', (e) => {
    e.preventDefault(); // Ngăn menu chuột phải mặc định
    showStatusMenu(e, house);
  });

  // Thêm sự kiện click chuột trái để chọn phòng
  house.addEventListener('click', () => {
    // Kiểm tra trạng thái phòng
    if (house.classList.contains('busy')) {
      alert('Phòng này đã được đặt!');
      return;
    }
    if (house.classList.contains('maintenance')) {
      alert('Phòng này đang trong quá trình bảo trì!');
      return;
    }
    if (house.classList.contains('cleaning')) {
      alert('Phòng này đang được dọn dẹp!');
      return;
    }
    
    // Toggle chọn/bỏ chọn phòng
    if (house.classList.contains('selected')) {
      // Bỏ chọn phòng
      house.classList.remove('selected');
      selectedRooms = selectedRooms.filter(room => room !== house);
    } else {
      // Chọn phòng mới
      house.classList.add('selected');
      selectedRooms.push(house);
    }
    
    // Cập nhật hiển thị
    if (selectedRooms.length > 0) {
      showRoomSelection();
    } else {
      hideRoomSelection();
    }
  });
});

// Hiển thị hộp thông báo chọn phòng
function showRoomSelection() {
  const selectionBox = document.getElementById('room-selection');
  const roomNumberSpan = document.getElementById('selected-room-number');
  const roomTypeSpan = document.getElementById('selected-room-type');
  
  if (selectedRooms.length === 0) {
    hideRoomSelection();
    return;
  }
  
  // Tạo danh sách phòng đã chọn
  const roomNumbers = selectedRooms.map(room => room.getAttribute('data-room')).join(', ');
  const roomTypes = [...new Set(selectedRooms.map(room => {
    const type = room.getAttribute('data-type');
    return roomConfig[type].name;
  }))].join(', ');
  
  roomNumberSpan.textContent = roomNumbers;
  roomTypeSpan.textContent = selectedRooms.length === 1 ? roomTypes : `${selectedRooms.length} phòng (${roomTypes})`;
  selectionBox.style.display = 'block';
}

// Ẩn hộp thông báo
function hideRoomSelection() {
  const selectionBox = document.getElementById('room-selection');
  selectionBox.style.display = 'none';
}

// Xử lý nút xác nhận
document.getElementById('confirm-btn').addEventListener('click', () => {
  if (selectedRooms.length > 0) {
    const roomNumbers = selectedRooms.map(room => room.getAttribute('data-room')).join(', ');
    
    // Đánh dấu tất cả phòng đã được đặt
    selectedRooms.forEach(room => {
      room.classList.add('busy');
      room.classList.remove('selected');
    });
    
    alert(`Đã xác nhận đặt ${selectedRooms.length} phòng: ${roomNumbers}!`);
    
    selectedRooms = [];
    hideRoomSelection();
  }
});

// Xử lý nút hủy
document.getElementById('cancel-btn').addEventListener('click', () => {
  // Bỏ chọn tất cả phòng
  selectedRooms.forEach(room => {
    room.classList.remove('selected');
  });
  selectedRooms = [];
  hideRoomSelection();
});

// Hiển thị menu cập nhật trạng thái
function showStatusMenu(event, house) {
  const menu = document.getElementById('status-menu');
  menu.style.display = 'block';
  menu.style.left = event.pageX + 'px';
  menu.style.top = event.pageY + 'px';

  // Xóa event listeners cũ
  const menuItems = menu.querySelectorAll('li');
  menuItems.forEach(item => {
    item.replaceWith(item.cloneNode(true));
  });

  // Thêm event listeners mới
  menu.querySelectorAll('li').forEach(item => {
    item.addEventListener('click', () => {
      const status = item.getAttribute('data-status');
      updateRoomStatus(house, status);
      menu.style.display = 'none';
    });
  });

  // Đánh dấu trạng thái hiện tại
  menu.querySelectorAll('li').forEach(item => {
    const status = item.getAttribute('data-status');
    if (house.classList.contains(status)) {
      item.classList.add('active');
    } else {
      item.classList.remove('active');
    }
  });
}

// Cập nhật trạng thái phòng
function updateRoomStatus(house, status) {
  // Xóa tất cả trạng thái cũ
  house.classList.remove('busy', 'maintenance', 'cleaning');
  
  // Thêm trạng thái mới nếu không phải available
  if (status !== 'available') {
    house.classList.add(status);
  }
}

// Đóng menu khi click ra ngoài
document.addEventListener('click', (e) => {
  const menu = document.getElementById('status-menu');
  if (!menu.contains(e.target) && e.target.className !== 'house') {
    menu.style.display = 'none';
  }
});

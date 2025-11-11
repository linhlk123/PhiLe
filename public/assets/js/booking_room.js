// Chờ cho toàn bộ nội dung trang web được tải xong
document.addEventListener('DOMContentLoaded', function() {

    // --- Lấy các phần tử DOM ---
    const checkInInput = document.getElementById('check-in');
    const checkOutInput = document.getElementById('check-out');
    const totalNightsSpan = document.getElementById('total-nights');
    const bookingForm = document.getElementById('booking-form');
    const adultsInput = document.getElementById('adults');
    const childrenInput = document.getElementById('children');

    // --- LẤY PHẦN TỬ VÀ GÁN SỰ KIỆN CHO MODAL ẢNH SƠ ĐỒ ---
    const btnOpenImageMap = document.getElementById('btn-open-image-map');
    const imageMapModal = document.getElementById('image-map-modal');
    const imageMapCloseBtn = document.getElementById('image-map-close-btn');

    if (btnOpenImageMap && imageMapModal && imageMapCloseBtn) {
        
        // 1. Sự kiện MỞ modal ảnh khi click nút "Xem sơ đồ"
        btnOpenImageMap.addEventListener('click', function() {
            imageMapModal.classList.add('active');
        });

        // 2. Sự kiện ĐÓNG modal ảnh khi click nút X
        imageMapCloseBtn.addEventListener('click', function() {
            imageMapModal.classList.remove('active');
        });

        // 3. Sự kiện ĐÓNG modal ảnh khi click ra ngoài
        imageMapModal.addEventListener('click', function(event) {
            // Chỉ đóng khi click vào lớp phủ (màu đen mờ)
            if (event.target === imageMapModal) { 
                imageMapModal.classList.remove('active');
            }
        });

    } else {
        console.warn('Không tìm thấy các phần tử cho modal ảnh sơ đồ.');
    }
    // --- KẾT THÚC MODAL ẢNH SƠ ĐỒ ---

    // [ĐÃ BỎ] Các biến filter giường cũ - giờ mỗi phòng có filter riêng
    // const bedTypeSelect = document.getElementById('bed-type');
    // const bedCountSelect = document.getElementById('bed-count');
    // ...

    // (Hàm calculateTotalNights giữ nguyên)
    function calculateTotalNights() {
        const checkInDate = new Date(checkInInput.value);
        const checkOutDate = new Date(checkOutInput.value);
        if (checkInInput.value && checkOutInput.value && checkOutDate > checkInDate) {
            const timeDiff = checkOutDate.getTime() - checkInDate.getTime();
            const nights = Math.ceil(timeDiff / (1000 * 60 * 60 * 24));
            totalNightsSpan.textContent = nights;
        } else {
            totalNightsSpan.textContent = '0';
        }
    }
    checkInInput.addEventListener('change', calculateTotalNights);
    checkOutInput.addEventListener('change', calculateTotalNights);

    // (Hàm submit form - Cập nhật để cho phép submit)
    bookingForm.addEventListener('submit', function(event) {
        const checkIn = new Date(checkInInput.value);
        const checkOut = new Date(checkOutInput.value);
        const adults = parseInt(adultsInput.value, 10);
        const fullName = document.getElementById('full-name').value;
        const email = document.getElementById('email').value;

        // --- Xác thực (Validation) cơ bản ---
        const today = new Date();
        today.setHours(0, 0, 0, 0); 
        if (!checkInInput.value || !checkOutInput.value) {
            event.preventDefault();
            alert('Vui lòng chọn ngày nhận và trả phòng.');
            return;
        }
        if (checkIn < today) {
            event.preventDefault();
            alert('Ngày nhận phòng không thể là ngày trong quá khứ.');
            return;
        }
        if (checkOut <= checkIn) {
            event.preventDefault();
            alert('Ngày trả phòng phải sau ngày nhận phòng.');
            return;
        }
        if (adults < 1) {
            event.preventDefault();
            alert('Phải có ít nhất 1 người lớn.');
            return;
        }
        if (fullName.trim() === '' || email.trim() === '') {
            event.preventDefault();
            alert('Vui lòng nhập đầy đủ họ tên và email.');
            return;
        }

        // [NÂNG CẤP] Kiểm tra xem đã chọn phòng chưa (kiểm tra tất cả các phòng)
        const allRoomItems = document.querySelectorAll('.room-selection-item');
        let allRoomsSelected = true;
        let missingRoomNumbers = [];
        
        allRoomItems.forEach((item, index) => {
            const roomId = item.querySelector('.selected-room-id');
            if (!roomId || !roomId.value) {
                allRoomsSelected = false;
                missingRoomNumbers.push(index + 1);
            }
        });
        
        if (!allRoomsSelected) {
            event.preventDefault();
            alert(`Vui lòng chọn phòng cho: Phòng ${missingRoomNumbers.join(', Phòng ')}`);
            return;
        }
        
        // Form hợp lệ, cho phép submit
        console.log('Form hợp lệ, đang gửi dữ liệu...');
    });

    
    // --- [NÂNG CẤP] SƠ ĐỒ RESORT (V5) ---

    // [CẬP NHẬT] Cấu trúc dữ liệu sẽ được lấy từ API
    let RESORT_LAYOUT = {
        areas: []
    };

    // [CẬP NHẬT] Dữ liệu đặt phòng sẽ được lấy từ database
    const DUMMY_BOOKINGS = [
        // Sẽ được thay thế bằng dữ liệu thực từ database
    ];

    // Lấy các phần tử DOM của Modal (thêm 2 cái mới)
    const btnOpenRoomSelector = document.getElementById('btn-open-room-selector');
    const roomMapModal = document.getElementById('room-map-modal');
    const modalCloseBtn = document.getElementById('modal-close-btn');
    const btnConfirmRoom = document.getElementById('btn-confirm-room');
    const selectedRoomIdInput = document.getElementById('selected-room-id');
    const roomTypeSelect = document.getElementById('room-type');
    const mapTabsContainer = document.getElementById('map-tabs-container');
    const mapAreasContainer = document.getElementById('map-areas-container');

    let currentSelectedRoomEl = null;
    let currentSelectedRoom = null; // {id, type, capacity, bedCountDouble, bedCountSingle}
    let currentRoomIndex = null; // Index của phòng đang được chọn (0, 1, 2...)

    // --- Hàm mở Modal (Cập nhật) ---
    function openRoomModal(roomIndex) {
        currentRoomIndex = roomIndex;
        
        const checkIn = checkInInput.value;
        const checkOut = checkOutInput.value;
        const totalGuests = (parseInt(adultsInput.value, 10) || 0) + (parseInt(childrenInput.value, 10) || 0);
        
        // [NÂNG CẤP] Lấy thông tin lọc từ form của phòng đang chọn
        const bedTypeSelect = document.getElementById(`bed-type-${roomIndex}`);
        const bedCountSelect = document.getElementById(`bed-count-${roomIndex}`);
        const bedCountDoubleInput = document.getElementById(`bed-count-double-${roomIndex}`);
        const bedCountSingleInput = document.getElementById(`bed-count-single-${roomIndex}`);
        
        const bedType = bedTypeSelect ? bedTypeSelect.value : 'any';
        
        // Tạo một object filter phức tạp
        const bedFilter = {
            type: bedType,
            totalCount: bedCountSelect ? bedCountSelect.value : 'any',
            doubleCount: bedCountDoubleInput ? (parseInt(bedCountDoubleInput.value, 10) || 0) : 0,
            singleCount: bedCountSingleInput ? (parseInt(bedCountSingleInput.value, 10) || 0) : 0
        };

        // (Kiểm tra validation ngày & khách)
        if (!checkIn || !checkOut || totalGuests === 0) {
             alert('Vui lòng chọn Ngày Nhận/Trả phòng và Số lượng khách trước.');
             return;
        }
        if (new Date(checkOut) <= new Date(checkIn)) {
            alert('Ngày trả phòng phải sau ngày nhận phòng.');
            return;
        }

        roomMapModal.classList.add('active');
        
        // [CẬP NHẬT] Truyền object bedFilter
        fetchAndDisplayResortStatus(checkIn, checkOut, totalGuests, bedFilter);
    }

    // (Hàm đóng Modal giữ nguyên)
    function closeRoomModal() {
        roomMapModal.classList.remove('active');
        mapTabsContainer.innerHTML = 'Đang tải...';
        mapAreasContainer.innerHTML = 'Đang tải...';
        currentSelectedRoomEl = null;
        currentSelectedRoom = null;
        btnConfirmRoom.disabled = true;
    }

    // (Hàm kiểm tra chồng chéo ngày giữ nguyên)
    function isDateRangeOverlap(startA_str, endA_str, startB_str, endB_str) {
        const sA = new Date(startA_str);
        const eA = new Date(endA_str);
        const sB = new Date(startB_str);
        const eB = new Date(endB_str);
        // (startA < endB) và (endA > startB)
        return sA < eB && eA > sB;
    }

    // --- [NÂNG CẤP] Hàm lấy và hiển thị sơ đồ ---
    async function fetchAndDisplayResortStatus(checkIn, checkOut, totalGuests, bedFilter) {
        console.log(`Tìm phòng: ${totalGuests} khách, Lọc giường:`, bedFilter);
        
        mapTabsContainer.innerHTML = '<p>Đang tải dữ liệu phòng...</p>';
        mapAreasContainer.innerHTML = '';

        try {
            // Gọi API lấy danh sách phòng available
            const response = await fetch('/api/rooms/available');
            const result = await response.json();

            if (!result.success) {
                throw new Error(result.message || 'Không thể tải dữ liệu phòng');
            }

            RESORT_LAYOUT = result.data;

            // Xóa nội dung loading
            mapTabsContainer.innerHTML = '';
            mapAreasContainer.innerHTML = '';

            if (!RESORT_LAYOUT.areas || RESORT_LAYOUT.areas.length === 0) {
                mapTabsContainer.innerHTML = '<p>Không có phòng nào khả dụng</p>';
                return;
            }

            RESORT_LAYOUT.areas.forEach((area, index) => {
                
                // Tạo Tab cho mỗi loại phòng
                const tabBtn = document.createElement('button');
                tabBtn.classList.add('tab-link');
                tabBtn.textContent = area.name;
                tabBtn.dataset.targetArea = area.id;
                mapTabsContainer.appendChild(tabBtn);

                const areaContent = document.createElement('div');
                areaContent.classList.add('map-area');
                areaContent.id = area.id;
                
                const roomGrid = document.createElement('div');
                roomGrid.classList.add('room-map-grid');
                areaContent.appendChild(roomGrid);
                mapAreasContainer.appendChild(areaContent);

                if (index === 0) {
                    tabBtn.classList.add('active');
                    areaContent.classList.add('active');
                }

                // [NÂNG CẤP] Logic vẽ phòng từ database
                area.rooms.forEach(room => {
                    let status = 'available'; // Mặc định là available vì API đã lọc
                    let totalBedsInRoom = room.bedCountDouble + room.bedCountSingle;

                    // Lọc 1: Sức chứa
                    if (room.capacity < totalGuests) {
                        status = 'unavailable';
                    }

                    // Lọc 2: Bộ lọc giường (phức tạp)
                    if (status === 'available') {
                        // Trường hợp 1: Người dùng chọn "Mixed"
                        if (bedFilter.type === 'mixed') {
                            // Phòng phải có ÍT NHẤT số giường đôi VÀ giường đơn họ muốn
                            if (room.bedCountDouble < bedFilter.doubleCount || room.bedCountSingle < bedFilter.singleCount) {
                                status = 'unavailable';
                            }
                        } 
                        // Trường hợp 2: Người dùng chọn "Giường Đôi"
                        else if (bedFilter.type === 'double') {
                            if (room.bedCountDouble === 0 || room.bedCountSingle > 0) {
                                // Phòng này phải có giường đôi, VÀ không có giường đơn
                                status = 'unavailable'; 
                            }
                        }
                        // Trường hợp 3: Người dùng chọn "Giường Đơn"
                        else if (bedFilter.type === 'single') {
                             if (room.bedCountSingle === 0 || room.bedCountDouble > 0) {
                                // Phòng này phải có giường đơn, VÀ không có giường đôi
                                status = 'unavailable'; 
                            }
                        }
                        // Trường hợp 4: Người dùng chọn "Bất kỳ" (any)
                        // (Không làm gì, bỏ qua lọc loại giường)
                    }

                    // Lọc 3: Số lượng giường TỔNG (chỉ lọc khi KHÔNG phải 'mixed')
                    if (status === 'available' && bedFilter.type !== 'mixed' && bedFilter.totalCount !== 'any') {
                        const requiredBeds = parseInt(bedFilter.totalCount, 10);
                        if (totalBedsInRoom !== requiredBeds) {
                            status = 'unavailable';
                        }
                    }

                    // Lọc 4: Ngày (có thể thêm logic kiểm tra booking từ database sau)
                    if (status === 'available') {
                        for (const booking of DUMMY_BOOKINGS) {
                            if (booking.roomId === room.id && isDateRangeOverlap(checkIn, checkOut, booking.from, booking.to)) {
                                status = 'busy'; 
                                break;
                            }
                        }
                    }
                    
                    // Tạo ô phòng, thêm text giường
                    const roomEl = document.createElement('div');
                    roomEl.classList.add('room-box', status);
                    
                    // Tạo text mô tả giường chi tiết
                    let bedText = '';
                    if (room.bedCountDouble > 0 && room.bedCountSingle > 0) {
                        bedText = `${room.bedCountDouble} Đôi, ${room.bedCountSingle} Đơn`;
                    } else if (room.bedCountDouble > 0) {
                        bedText = `${room.bedCountDouble} Giường Đôi`;
                    } else if (room.bedCountSingle > 0) {
                        bedText = `${room.bedCountSingle} Giường Đơn`;
                    }

                    roomEl.innerHTML = `
                        <span class="room-id">${room.id}</span>
                        <span class="room-type">${room.type}</span>
                        <span class="room-bed">${bedText}</span> 
                    `;

                    // Chỉ cho phép chọn phòng có status = 'available'
                    if (status === 'available') {
                        roomEl.addEventListener('click', () => selectRoom(roomEl, room));
                    }
                    
                    roomGrid.appendChild(roomEl);
                });
            });

            // Gán sự kiện cho Tab
            document.querySelectorAll('.tab-link').forEach(tab => {
                tab.addEventListener('click', () => {
                    const currentActiveTab = document.querySelector('.tab-link.active');
                    const currentActiveArea = document.querySelector('.map-area.active');
                    
                    if (currentActiveTab) currentActiveTab.classList.remove('active');
                    if (currentActiveArea) currentActiveArea.classList.remove('active');
                    
                    tab.classList.add('active');
                    const targetArea = document.getElementById(tab.dataset.targetArea);
                    if (targetArea) targetArea.classList.add('active');
                });
            });

        } catch (error) {
            console.error('Lỗi khi tải dữ liệu phòng:', error);
            mapTabsContainer.innerHTML = '<p style="color: red;">Có lỗi xảy ra khi tải dữ liệu phòng. Vui lòng thử lại!</p>';
        }
    }

    // (Hàm selectRoom giữ nguyên)
    function selectRoom(selectedEl, roomData) {
        if (currentSelectedRoomEl) {
            currentSelectedRoomEl.classList.remove('selected');
        }
        if (currentSelectedRoom && currentSelectedRoom.id === roomData.id) {
            currentSelectedRoom = null;
            currentSelectedRoomEl = null;
            btnConfirmRoom.disabled = true;
        } else {
            selectedEl.classList.add('selected');
            currentSelectedRoomEl = selectedEl;
            currentSelectedRoom = roomData;
            btnConfirmRoom.disabled = false;
        }
    }


    // [NÂNG CẤP] Hàm xác nhận phòng
    function confirmRoomSelection() {
        if (currentSelectedRoom && currentRoomIndex !== null) {
            // Cập nhật thông tin vào form của phòng đang chọn
            const selectedRoomIdInput = document.getElementById(`selected-room-id-${currentRoomIndex}`);
            const roomTypeSelect = document.getElementById(`room-type-${currentRoomIndex}`);
            const bedTypeSelect = document.getElementById(`bed-type-${currentRoomIndex}`);
            const bedCountSelect = document.getElementById(`bed-count-${currentRoomIndex}`);
            const bedCountDoubleInput = document.getElementById(`bed-count-double-${currentRoomIndex}`);
            const bedCountSingleInput = document.getElementById(`bed-count-single-${currentRoomIndex}`);
            
            // Lấy nút "Chọn phòng resort" của phòng này
            const btnSelector = document.querySelector(`.btn-open-room-selector[data-room-index="${currentRoomIndex}"]`);
            
            if (selectedRoomIdInput) {
                selectedRoomIdInput.value = currentSelectedRoom.id;
            }
            if (roomTypeSelect) {
                roomTypeSelect.value = currentSelectedRoom.type;
            }
            
            // Cập nhật text và màu của nút
            if (btnSelector) {
                btnSelector.innerHTML = `<i class="fas fa-check-circle"></i> Đã chọn: ${currentSelectedRoom.id}`;
                btnSelector.style.backgroundColor = '#4caf50';
                btnSelector.style.color = 'white';
            }
            
            // Tự động chọn lại bộ lọc giường trên form
            if (bedTypeSelect) {
                if (currentSelectedRoom.bedCountDouble > 0 && currentSelectedRoom.bedCountSingle > 0) {
                    // Phòng này là 'mixed'
                    bedTypeSelect.value = 'mixed';
                    // Kích hoạt lại logic ẩn/hiện
                    bedTypeSelect.dispatchEvent(new Event('change')); 
                    
                    if (bedCountDoubleInput) bedCountDoubleInput.value = currentSelectedRoom.bedCountDouble;
                    if (bedCountSingleInput) bedCountSingleInput.value = currentSelectedRoom.bedCountSingle;
                } else {
                    // Phòng này là loại thuần
                    bedTypeSelect.value = (currentSelectedRoom.bedCountDouble > 0) ? 'double' : 'single';
                    // Kích hoạt lại logic ẩn/hiện
                    bedTypeSelect.dispatchEvent(new Event('change'));

                    let totalBeds = currentSelectedRoom.bedCountDouble + currentSelectedRoom.bedCountSingle;
                    
                    // Cập nhật dropdown số giường
                    if (bedCountSelect && bedCountSelect.querySelector(`option[value="${totalBeds}"]`)) {
                        bedCountSelect.value = totalBeds.toString();
                    } else if (bedCountSelect) {
                        bedCountSelect.value = 'any'; 
                    }
                }
            }
            
            closeRoomModal();
        }
    }

    // --- Gán sự kiện cho các nút ---
    // Lắng nghe event từ blade template
    document.addEventListener('openRoomModal', function(e) {
        const roomIndex = e.detail.roomIndex;
        openRoomModal(roomIndex);
    });
    
    modalCloseBtn.addEventListener('click', closeRoomModal);
    btnConfirmRoom.addEventListener('click', confirmRoomSelection);

    roomMapModal.addEventListener('click', function(event) {
        if (event.target === roomMapModal) {
            closeRoomModal();
        }
    });

    // --- [NÂNG CẤP] Hàm Reset lựa chọn - Đã bỏ vì giờ có nhiều phòng ---
    // function resetRoomSelection() { ... }
    
    // Không còn các sự kiện reset toàn cục nữa vì mỗi phòng độc lập

});

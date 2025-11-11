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

    
    // [NÂNG CẤP] Lấy TẤT CẢ bộ lọc giường
    const bedTypeSelect = document.getElementById('bed-type');
    const bedCountSelect = document.getElementById('bed-count'); // Dropdown Tổng
    const groupBedCountTotal = document.getElementById('group-bed-count-total'); // div chứa dropdown tổng
    const groupBedMixedDetails = document.getElementById('group-bed-mixed-details'); // div chứa 2 input
    const bedCountDoubleInput = document.getElementById('bed-count-double'); // input giường đôi
    const bedCountSingleInput = document.getElementById('bed-count-single'); // input giường đơn

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

    // (Hàm submit form giữ nguyên)
    bookingForm.addEventListener('submit', function(event) {
        event.preventDefault(); 
        const checkIn = new Date(checkInInput.value);
        const checkOut = new Date(checkOutInput.value);
        const adults = parseInt(adultsInput.value, 10);
        const fullName = document.getElementById('full-name').value;
        const email = document.getElementById('email').value;

        // --- Xác thực (Validation) cơ bản ---
        const today = new Date();
        today.setHours(0, 0, 0, 0); 
        if (!checkInInput.value || !checkOutInput.value) {
            alert('Vui lòng chọn ngày nhận và trả phòng.');
            return;
        }
        if (checkIn < today) {
            alert('Ngày nhận phòng không thể là ngày trong quá khứ.');
            return;
        }
        if (checkOut <= checkIn) {
            alert('Ngày trả phòng phải sau ngày nhận phòng.');
            return;
        }
        if (adults < 1) {
            alert('Phải có ít nhất 1 người lớn.');
            return;
        }
        if (fullName.trim() === '' || email.trim() === '') {
            alert('Vui lòng nhập đầy đủ họ tên và email.');
            return;
        }

        // [NÂNG CẤP] Kiểm tra xem đã chọn phòng chưa
        const selectedRoom = document.getElementById('selected-room-id').value;
        if (!selectedRoom) {
            alert('Vui lòng chọn một phòng cụ thể trên sơ đồ resort.');
            return;
        }
        
        console.log('Form hợp lệ, đang gửi dữ liệu...');
        alert(`Đặt phòng thành công cho phòng ${selectedRoom}! (Đây là demo)`);
    });

    
    // --- [LOGIC NÂNG CẤP] SƠ ĐỒ RESORT (V5) ---

    // [CẬP NHẬT] Cấu trúc dữ liệu chi tiết hơn
    const RESORT_LAYOUT = {
        areas: [
            { id: 'area_a_garden', name: 'Khu A: Leviosa Garden', rooms: [
                { id: 'A-101', type: 'standard', capacity: 2, bedCountDouble: 0, bedCountSingle: 2 }, 
                { id: 'A-102', type: 'standard', capacity: 2, bedCountDouble: 1, bedCountSingle: 0 },
                { id: 'A-103', type: 'standard', capacity: 3, bedCountDouble: 0, bedCountSingle: 2 },
                { id: 'A-104', type: 'standard', capacity: 3, bedCountDouble: 1, bedCountSingle: 0 },
            ]},
            { id: 'area_b_beach', name: 'Khu B: Leviosa Lake View', rooms: [
                { id: 'B-201', type: 'superior', capacity: 4, bedCountDouble: 2, bedCountSingle: 0 },
                { id: 'B-202', type: 'superior', capacity: 4, bedCountDouble: 2, bedCountSingle: 0 },
                { id: 'B-203', type: 'superior', capacity: 6, bedCountDouble: 1, bedCountSingle: 2 }, 
            ]},
            { id: 'area_c_lake', name: 'Khu C: Leviosa Beach Villas', rooms: [
                { id: 'C-301', type: 'deluxe', capacity: 2, bedCountDouble: 1, bedCountSingle: 0 },
                { id: 'C-302', type: 'deluxe', capacity: 2, bedCountDouble: 0, bedCountSingle: 2 },
                { id: 'C-303', type: 'deluxe', capacity: 4, bedCountDouble: 2, bedCountSingle: 0 }, 
                { id: 'C-304', type: 'deluxe', capacity: 4, bedCountDouble: 0, bedCountSingle: 2 },
            ]},
            { id: 'area_d_royal', name: 'Khu D: Leviosa Royal', rooms: [
                { id: 'D-401', type: 'villa', capacity: 8, bedCountDouble: 3, bedCountSingle: 0 }, 
                { id: 'D-402', type: 'villa', capacity: 10, bedCountDouble: 2, bedCountSingle: 2 }, 
            ]}
        ]
    };

    // [CẬP NHẬT] Dữ liệu đặt phòng giả lập (dùng ID phòng mới)
    const DUMMY_BOOKINGS = [
        { roomId: 'A-102', from: '2025-11-05', to: '2025-11-10' }, // Khu A
        { roomId: 'B-203', from: '2025-11-01', to: '2025-11-03' }, // Khu B
        { roomId: 'D-401', from: '2025-11-01', to: '2025-11-08' }, // Khu D
        { roomId: 'C-301', from: '2025-11-07', to: '2025-11-09' }, // Khu C
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

    // --- [MỚI] Logic Ẩn/Hiện bộ lọc "Mixed" ---
    bedTypeSelect.addEventListener('change', function() {
        if (this.value === 'mixed') {
            groupBedMixedDetails.style.display = 'flex'; // Hiện hàng chi tiết
            groupBedCountTotal.style.display = 'none'; // Ẩn dropdown tổng
            
            // Đặt giá trị mặc định khi hiện
            bedCountDoubleInput.value = '1';
            bedCountSingleInput.value = '1';
        } else {
            groupBedMixedDetails.style.display = 'none'; // Ẩn hàng chi tiết
            groupBedCountTotal.style.display = 'flex'; // Hiện lại dropdown tổng
        }
        
        // Reset lựa chọn phòng
        // resetRoomSelection();
    });

    // --- Hàm mở Modal (Cập nhật) ---
    function openRoomModal() {
        const checkIn = checkInInput.value;
        const checkOut = checkOutInput.value;
        const totalGuests = (parseInt(adultsInput.value, 10) || 0) + (parseInt(childrenInput.value, 10) || 0);
        
        // [NÂNG CẤP] Lấy tất cả thông tin lọc
        const bedType = bedTypeSelect.value; 
        
        // Tạo một object filter phức tạp
        const bedFilter = {
            type: bedType,
            totalCount: bedCountSelect.value, // 'any', '1', '2', '3', '4'...
            doubleCount: parseInt(bedCountDoubleInput.value, 10) || 0,
            singleCount: parseInt(bedCountSingleInput.value, 10) || 0
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
    function fetchAndDisplayResortStatus(checkIn, checkOut, totalGuests, bedFilter) {
        console.log(`Tìm phòng: ${totalGuests} khách, Lọc giường:`, bedFilter);
        
        mapTabsContainer.innerHTML = '';
        mapAreasContainer.innerHTML = '';

        setTimeout(() => {
            RESORT_LAYOUT.areas.forEach((area, index) => {
                
                // (Tạo Tab và Khu vực giữ nguyên)
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

                // [NÂNG CẤP] Logic vẽ phòng
                area.rooms.forEach(room => {
                    let status = 'available';
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
                    // [SỬA LỖI] Cập nhật logic này để khớp với dropdown (1, 2, 3, 4...)
                    if (status === 'available' && bedFilter.type !== 'mixed' && bedFilter.totalCount !== 'any') {
                        const requiredBeds = parseInt(bedFilter.totalCount, 10);
                        if (totalBedsInRoom !== requiredBeds) {
                            status = 'unavailable';
                        }
                    }

                    // Lọc 4: Ngày
                    if (status === 'available') {
                        for (const booking of DUMMY_BOOKINGS) {
                            if (booking.roomId === room.id && isDateRangeOverlap(checkIn, checkOut, booking.from, booking.to)) {
                                status = 'busy'; 
                                break;
                            }
                        }
                    }
                    
                    // [NÂNG CẤP] Tạo ô phòng, thêm text giường
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

                    if (status === 'available') {
                        roomEl.addEventListener('click', () => selectRoom(roomEl, room));
                    }
                    
                    roomGrid.appendChild(roomEl);
                });
            });

            // (Gán sự kiện cho Tab giữ nguyên)
            document.querySelectorAll('.tab-link').forEach(tab => {
                tab.addEventListener('click', () => {
                    document.querySelector('.tab-link.active').classList.remove('active');
                    document.querySelector('.map-area.active').classList.remove('active');
                    tab.classList.add('active');
                    document.getElementById(tab.dataset.targetArea).classList.add('active');
                });
            });

        }, 500); 
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
        if (currentSelectedRoom) {
            selectedRoomIdInput.value = currentSelectedRoom.id;
            roomTypeSelect.value = currentSelectedRoom.type;
            
            btnOpenRoomSelector.textContent = `Đã chọn: ${currentSelectedRoom.id}`;
            btnOpenRoomSelector.style.backgroundColor = 'var(--color-header, #509262ff)';
            
            // Tự động chọn lại bộ lọc giường trên form
            if (currentSelectedRoom.bedCountDouble > 0 && currentSelectedRoom.bedCountSingle > 0) {
                // Phòng này là 'mixed'
                bedTypeSelect.value = 'mixed';
                // Kích hoạt lại logic ẩn/hiện
                bedTypeSelect.dispatchEvent(new Event('change')); 
                
                bedCountDoubleInput.value = currentSelectedRoom.bedCountDouble;
                bedCountSingleInput.value = currentSelectedRoom.bedCountSingle;
            } else {
                // Phòng này là loại thuần
                bedTypeSelect.value = (currentSelectedRoom.bedCountDouble > 0) ? 'double' : 'single';
                // Kích hoạt lại logic ẩn/hiện
                bedTypeSelect.dispatchEvent(new Event('change'));

                let totalBeds = currentSelectedRoom.bedCountDouble + currentSelectedRoom.bedCountSingle;
                
                // [SỬA LỖI] Cập nhật logic này để khớp với dropdown (1, 2, 3, 4...)
                // Kiểm tra xem giá trị có tồn tại trong dropdown không
                if (bedCountSelect.querySelector(`option[value="${totalBeds}"]`)) {
                    bedCountSelect.value = totalBeds.toString();
                } else {
                    // Nếu phòng có 9 giường mà dropdown chỉ có 8, chọn "any"
                    bedCountSelect.value = 'any'; 
                }
            }
            
            closeRoomModal();
        }
    }

    // --- Gán sự kiện cho các nút ---
    btnOpenRoomSelector.addEventListener('click', openRoomModal);
    modalCloseBtn.addEventListener('click', closeRoomModal);
    btnConfirmRoom.addEventListener('click', confirmRoomSelection);

    roomMapModal.addEventListener('click', function(event) {
        if (event.target === roomMapModal) {
            closeRoomModal();
        }
    });

    // --- [NÂNG CẤP] Hàm Reset lựa chọn ---
    function resetRoomSelection() {
        selectedRoomIdInput.value = '';
        roomTypeSelect.value = ''; 
        btnOpenRoomSelector.textContent = 'Chọn phòng resort';
        btnOpenRoomSelector.style.backgroundColor = '#f0f0f0';
    }
    
    // Các sự kiện reset
    checkInInput.addEventListener('change', resetRoomSelection);
    checkOutInput.addEventListener('change', resetRoomSelection);
    adultsInput.addEventListener('change', resetRoomSelection);
    childrenInput.addEventListener('change', resetRoomSelection);
    
    // Khi đổi các bộ lọc giường cũng reset
    // (bedTypeSelect đã có ở trên)
    bedCountSelect.addEventListener('change', resetRoomSelection);
    bedCountDoubleInput.addEventListener('change', resetRoomSelection);
    bedCountSingleInput.addEventListener('change', resetRoomSelection);

});

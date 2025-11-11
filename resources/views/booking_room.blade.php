<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />
    <title>@yield('title', 'Resort')</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/booking_room.css') }}">
    <style>
        .room-selection-item {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            background: #f9f9f9;
            position: relative;
        }

        .room-selection-item h4 {
            color: #1a237e;
            margin-bottom: 15px;
        }

        .btn-remove-room {
            position: absolute;
            top: 20px;
            right: 20px;
            background: #f44336;
            color: white;
            border: none;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            cursor: pointer;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.3s;
        }

        .btn-remove-room:hover {
            background: #d32f2f;
        }

        #btn-add-room {
            padding: 12px 30px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: all 0.3s;
        }

        #btn-add-room:hover {
            background: #45a049 !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        }
    </style>

</head>
<body>
    <div class="booking-form-container">
        {{-- Hiển thị thông báo success/error --}}
        @if(session('success'))
            <div style="background: #4caf50; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <i class="fas fa-check-circle"></i> {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div style="background: #f44336; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div style="background: #f44336; color: white; padding: 15px; border-radius: 5px; margin-bottom: 20px;">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form id="booking-form" class="booking-form" action="{{ route('booking.store') }}" method="POST">
            @csrf
            <h2>Đặt phòng Leviosa Resort</h2>
            <p>Trải nghiệm kỳ nghỉ tuyệt vời tại ốc đảo của chúng tôi.</p>
            
            <div class="form-row">
                <div class="form-group">
                    <label for="check-in">Ngày nhận phòng</label>
                    <input type="date" id="check-in" name="check-in" required>
                </div>
                <div class="form-group">
                    <label for="check-out">Ngày trả phòng</label>
                    <input type="date" id="check-out" name="check-out" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="adults">Người lớn</label>
                    <input type="number" id="adults" name="adults" value="2" min="1" required>
                </div>
                <div class="form-group">
                    <label for="children">Trẻ em</label>
                    <input type="number" id="children" name="children" value="0" min="0">
                </div>
            </div>
                <div class="button-group">
                    <button type="button" id="btn-open-image-map" class="second-btn">
                        Xem sơ đồ resort
                    </button>
                </div>

            {{-- Container cho danh sách phòng đã chọn --}}
            <div id="selected-rooms-container">
                {{-- Room 1 (mặc định) --}}
                <div class="room-selection-item" data-room-index="0">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <h4 style="margin: 0; color: #1a237e;">Phòng 1</h4>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="bed-type-0">Loại giường</label>
                            <select id="bed-type-0" name="rooms[0][bed-type]" class="bed-type-select" disabled>
                                <option value="any">-- Chọn loại giường --</option> 
                                <option value="double">Giường Đôi (Double)</option>
                                <option value="single">Giường Đơn (Single)</option>
                                <option value="mixed">Nhiều loại (Mixed)</option>
                            </select>
                        </div>
                        <div class="form-group group-bed-count-total"> 
                            <label for="bed-count-0">Số lượng giường (Tổng)</label>
                            <select id="bed-count-0" name="rooms[0][bed-count]" class="bed-count-select" disabled>
                                <option value="any">-- Chọn số giường --</option>
                                @for ($i = 1; $i <= 8; $i++)
                                    <option value="{{ $i }}">{{ $i }} giường</option>
                                @endfor
                            </select>
                        </div>
                    </div>

                    <div class="form-row group-bed-mixed-details" style="display: none;">
                        <div class="form-group">
                            <label for="bed-count-double-0">Số giường đôi</label>
                            <input type="number" id="bed-count-double-0" name="rooms[0][bed-count-double]" class="bed-count-double-input" value="1" min="0">
                        </div>
                        <div class="form-group">
                            <label for="bed-count-single-0">Số giường đơn</label>
                            <input type="number" id="bed-count-single-0" name="rooms[0][bed-count-single]" class="bed-count-single-input" value="1" min="0">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="room-type-0">Loại phòng (Sẽ tự chọn theo sơ đồ)</label>
                        <select id="room-type-0" name="rooms[0][room-type]" class="room-type-select" disabled>
                            <option value="">-- Vui lòng chọn trên sơ đồ --</option>
                            <option value="standard">Phòng Standard (View vườn)</option>
                            <option value="deluxe">Phòng Deluxe (View biển)</option>
                            <option value="bungalow">Bungalow (Sát biển)</option>
                            <option value="villa">Villa (Hồ bơi riêng)</option>
                        </select>
                    </div>
                    
                    <input type="hidden" id="selected-room-id-0" name="rooms[0][selected-room-id]" class="selected-room-id">
                    
                    {{-- Nút chọn phòng cho phòng này --}}
                    <div class="form-group" style="text-align: center;">
                        <button type="button" class="btn-open-room-selector secondary-btn" data-room-index="0">
                            <i class="fas fa-map-marked-alt"></i> Chọn phòng resort
                        </button>
                    </div>
                </div>
            </div>

            {{-- Nút thêm phòng --}}
            <div class="form-group" style="text-align: center; margin-top: 20px;">
                <button type="button" id="btn-add-room" class="secondary-btn" style="background: #4caf50; color: white;">
                    <i class="fas fa-plus"></i> Thêm phòng
                </button>
            </div>

            <div class="form-group">
                <label for="full-name">Họ và tên</label>
                <input type="text" id="full-name" name="full-name" 
                       placeholder="Ví dụ: Nguyễn Văn A" 
                       value="{{ $user->FullName ?? '' }}"
                       {{ $user ? 'readonly' : '' }} 
                       required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" 
                       placeholder="email@example.com" 
                       value="{{ $user->Email ?? '' }}"
                       {{ $user ? 'readonly' : '' }} 
                       required>
            </div>

            <div class="booking-summary">
                <p><strong>Tổng số đêm:</strong> <span id="total-nights">0</span></p>
            </div>

            <button type="submit" class="submit-btn">Kiểm tra & Đặt phòng</button>
        </form>
    </div>

    {{-- Room Map Modal --}}
    <div id="room-map-modal" class="modal-overlay">
        <div class="modal-content large">
            <button id="modal-close-btn" class="modal-close">&times;</button>
            
            <h3>Sơ đồ Resort Leviosa</h3>
            <p>Vui lòng chọn một phòng còn trống và phù hợp với số lượng khách.</p>
            
            <div class="room-map-legend">
                <div class="legend-item">
                    <span class="room-box-legend available"></span> Khả dụng
                </div>
                <div class="legend-item">
                    <span class="room-box-legend busy"></span> Đã có khách
                </div>
                <div class="legend-item">
                    <span class="room-box-legend unavailable"></span> Không phù hợp
                </div>
                <div class="legend-item">
                    <span class="room-box-legend selected"></span> Đang chọn
                </div>
            </div>

            <div id="map-tabs-container" class="map-tabs">
            </div>

            <div id="map-areas-container" class="map-areas-content">
            </div>
            
            <button id="btn-confirm-room" class="submit-btn" disabled>Xác nhận phòng</button>
        </div>
    </div>

    {{-- Image Map Modal --}}
    <div id="image-map-modal" class="modal-overlay">
        <div class="modal-content">
            <button id="image-map-close-btn" class="modal-close">&times;</button>
            
            <h3>SƠ ĐỒ TỔNG QUAN LEVIOSA RESORT</h3>
            
            <img src="{{ asset('assets/images/Resort Map.png') }}" alt="Sơ đồ tổng quan Leviosa Resort">
        </div>
    </div>
    <script src="{{ asset('assets/js/booking_room.js') }}"></script>
    <script>
        // Biến đếm số phòng
        let roomCount = 1;
        
        // Biến lưu index của phòng đang được chọn
        let currentRoomIndex = null;

        // Hàm mở modal chọn phòng cho phòng cụ thể
        window.openRoomSelectorForRoom = function(roomIndex) {
            currentRoomIndex = roomIndex;
            
            // Lấy ngày check-in và check-out
            const checkIn = document.getElementById('check-in').value;
            const checkOut = document.getElementById('check-out').value;
            
            if (!checkIn || !checkOut) {
                alert('Vui lòng chọn ngày nhận và trả phòng trước khi chọn phòng.');
                return;
            }
            
            if (new Date(checkOut) <= new Date(checkIn)) {
                alert('Ngày trả phòng phải sau ngày nhận phòng.');
                return;
            }
            
            // Mở modal (sẽ gọi hàm trong booking_room.js)
            const event = new CustomEvent('openRoomModal', {
                detail: {
                    roomIndex: roomIndex,
                    checkIn: checkIn,
                    checkOut: checkOut
                }
            });
            document.dispatchEvent(event);
        };

        // Gán sự kiện cho tất cả nút "Chọn phòng resort"
        document.addEventListener('click', function(e) {
            if (e.target.closest('.btn-open-room-selector')) {
                const btn = e.target.closest('.btn-open-room-selector');
                const roomIndex = parseInt(btn.getAttribute('data-room-index'));
                openRoomSelectorForRoom(roomIndex);
            }
        });

        // Xử lý nút thêm phòng
        document.getElementById('btn-add-room').addEventListener('click', function() {
            const container = document.getElementById('selected-rooms-container');
            const newIndex = roomCount;
            
            const roomItem = document.createElement('div');
            roomItem.className = 'room-selection-item';
            roomItem.setAttribute('data-room-index', newIndex);
            
            roomItem.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                    <h4 style="margin: 0; color: #1a237e;">Phòng ${newIndex + 1}</h4>
                    <button type="button" class="btn-remove-room" onclick="removeRoom(${newIndex})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="bed-type-${newIndex}">Loại giường</label>
                        <select id="bed-type-${newIndex}" name="rooms[${newIndex}][bed-type]" class="bed-type-select" disabled>
                            <option value="any">-- Chọn loại giường --</option> 
                            <option value="double">Giường Đôi (Double)</option>
                            <option value="single">Giường Đơn (Single)</option>
                            <option value="mixed">Nhiều loại (Mixed)</option>
                        </select>
                    </div>
                    <div class="form-group group-bed-count-total"> 
                        <label for="bed-count-${newIndex}">Số lượng giường (Tổng)</label>
                        <select id="bed-count-${newIndex}" name="rooms[${newIndex}][bed-count]" class="bed-count-select" disabled>
                            <option value="any">-- Chọn số giường --</option>
                            @for ($i = 1; $i <= 8; $i++)
                                <option value="{{ $i }}">{{ $i }} giường</option>
                            @endfor
                        </select>
                    </div>
                </div>

                <div class="form-row group-bed-mixed-details" style="display: none;">
                    <div class="form-group">
                        <label for="bed-count-double-${newIndex}">Số giường đôi</label>
                        <input type="number" id="bed-count-double-${newIndex}" name="rooms[${newIndex}][bed-count-double]" class="bed-count-double-input" value="1" min="0">
                    </div>
                    <div class="form-group">
                        <label for="bed-count-single-${newIndex}">Số giường đơn</label>
                        <input type="number" id="bed-count-single-${newIndex}" name="rooms[${newIndex}][bed-count-single]" class="bed-count-single-input" value="1" min="0">
                    </div>
                </div>

                <div class="form-group">
                    <label for="room-type-${newIndex}">Loại phòng (Sẽ tự chọn theo sơ đồ)</label>
                    <select id="room-type-${newIndex}" name="rooms[${newIndex}][room-type]" class="room-type-select" disabled>
                        <option value="">-- Vui lòng chọn trên sơ đồ --</option>
                        <option value="standard">Phòng Standard (View vườn)</option>
                        <option value="deluxe">Phòng Deluxe (View biển)</option>
                        <option value="bungalow">Bungalow (Sát biển)</option>
                        <option value="villa">Villa (Hồ bơi riêng)</option>
                    </select>
                </div>
                
                <input type="hidden" id="selected-room-id-${newIndex}" name="rooms[${newIndex}][selected-room-id]" class="selected-room-id">
                
                <div class="form-group" style="text-align: center;">
                    <button type="button" class="btn-open-room-selector secondary-btn" data-room-index="${newIndex}">
                        <i class="fas fa-map-marked-alt"></i> Chọn phòng resort
                    </button>
                </div>
            `;
            
            container.appendChild(roomItem);
            roomCount++;
            
            // Thông báo
            alert(`Đã thêm Phòng ${newIndex + 1}. Vui lòng chọn phòng trên sơ đồ resort.`);
        });

        // Hàm xóa phòng
        window.removeRoom = function(index) {
            if (confirm('Bạn có chắc muốn xóa phòng này?')) {
                const roomItem = document.querySelector(`[data-room-index="${index}"]`);
                if (roomItem) {
                    roomItem.remove();
                    // Cập nhật lại số thứ tự các phòng
                    updateRoomNumbers();
                }
            }
        };

        // Cập nhật lại số thứ tự phòng
        function updateRoomNumbers() {
            const rooms = document.querySelectorAll('.room-selection-item');
            rooms.forEach((room, idx) => {
                const h4 = room.querySelector('h4');
                if (h4) {
                    h4.textContent = `Phòng ${idx + 1}`;
                }
            });
        }

        // Xử lý khi chọn loại giường để hiện/ẩn mixed details
        document.addEventListener('change', function(e) {
            if (e.target.classList.contains('bed-type-select')) {
                const roomItem = e.target.closest('.room-selection-item');
                const mixedDetails = roomItem.querySelector('.group-bed-mixed-details');
                const bedCountTotal = roomItem.querySelector('.group-bed-count-total');
                
                if (e.target.value === 'mixed') {
                    mixedDetails.style.display = 'flex';
                    bedCountTotal.style.display = 'none';
                } else {
                    mixedDetails.style.display = 'none';
                    bedCountTotal.style.display = 'block';
                }
            }
        });
    </script>
</body>
</html>
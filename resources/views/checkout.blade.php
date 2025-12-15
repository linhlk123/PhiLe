<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Thanh Toán</title>
    <link rel="stylesheet" href="{{ asset('assets/css/checkout.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/Cus_header_footer.css') }}"/>
    <link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
    @include('layouts.header')
    <div class="payment-container">
        <div class="payment-card">
            <h2 class="section-title">🛎 Giỏ hàng của bạn</h2>

            <div class="payment-section customer-info">
                <h3 class="section-subtitle">👤 Thông tin đặt phòng</h3>
                @auth('customer')
                    @php
                        $user = Auth::guard('customer')->user();
                    @endphp

                    <div class="info-row">
                        <span>Tên Khách hàng:</span>
                        <!-- Hiển thị FullName từ đối tượng người dùng -->
                        <strong>{{ $user->FullName }}</strong>
                    </div>
                    <div class="info-row">
                        <span>Email:</span>
                        <!-- Hiển thị Email -->
                        <strong>{{ $user->Email }}</strong>
                    </div>
                    <div class="info-row">
                        <span>Số điện thoại:</span>
                        <!-- Hiển thị Phone -->
                        <strong>{{ $user->Phone }}</strong>
                    </div>
                @else
                    <!-- Hiển thị thông báo nếu người dùng chưa đăng nhập -->
                    <p class="info-log">
                        Vui lòng <a href="{{ route('customer.login') }}" >Đăng nhập</a> để thanh toán.
                    </p>
                @endauth
            </div>
            
            <div class="payment-section coupon-section">
                <h3 class="section-subtitle">🏷 Mã giảm giá (Coupon)</h3>
                <div class="coupon-input-group">
                    <input type="text" placeholder="Nhập mã giảm giá..." class="coupon-input">
                    <button class="btn-apply">Áp dụng</button>
                </div>
                <p class="coupon-message success" style="display:none;"></p>
            </div>

            {{-- PHÒNG ĐÃ ĐẶT --}}
            <div class="payment-section">
                <h3 class="section-subtitle">Phòng đã đặt</h3>
                @foreach($rooms as $r)
                <div class="item-box">
                    <div>
                        <div class="item-title">Phòng {{ $r['room']->RoomNumber }}</div>
                        <div class="item-desc">
                            {{ $r['days'] }} đêm 
                            — {{ number_format($r['price']) }}₫ / đêm
                        </div>
                    </div>
                    <div class="item-price">
                        {{ number_format($r['total']) }}₫
                    </div>
                </div>
                @endforeach
            </div>

            {{-- DỊCH VỤ ĐÃ SỬ DỤNG --}}
            <div class="payment-section">
                <h3 class="section-subtitle">Dịch vụ đã sử dụng</h3>
                @forelse($services as $s)
                <div class="item-box">
                    <div>
                        <div class="item-title">{{ $s['service']->ServiceName }}</div>
                        <div class="item-desc">
                            SL: {{ $s['qty'] }} — {{ number_format($s['price']) }}₫ / đơn vị
                        </div>
                    </div>
                    <div class="item-price">
                        {{ number_format($s['total']) }}₫
                    </div>
                </div>
                @empty
                <p class="empty-text">Không có dịch vụ nào được sử dụng.</p>
                @endforelse
            </div>

            {{-- TỔNG KẾT THANH TOÁN --}}
            <div class="payment-summary">
                <h3 class="section-subtitle">Tổng kết thanh toán</h3>

                <div class="summary-row">
                    <span>Tạm tính:</span>
                    <strong>{{ number_format($totalBeforeDiscount) }}₫</strong>
                </div>

                <div class="summary-row">
                    <span>Giảm giá:</span>
                    <strong class="discount">- {{ number_format($discountAmount) }}₫</strong>
                </div>

                <div class="summary-total">
                    <span>Tổng cộng:</span>
                    <strong>{{ number_format($total) }}₫</strong>
                </div>
            </div>
            
            <form method="POST" action="{{ route('payment.store', $bookingId) }}">
            @csrf
            <input type="hidden" name="PaymentMethod" id="payment_method" value="card">

            <div class="payment-details">
                
                <div class="payment-method-content card-content active card-form">
                    <h3 class="section-subtitle">Chi tiết thanh toán qua thẻ</h3>
                        <div class="form-group">
                            <label>Số thẻ:</label>
                            <input type="text" name="card_number" id="card_number" placeholder="xxxx xxxx xxxx xxxx" required>
                        </div>
                        <div class="form-group half-width">
                            <div><label>Hết hạn:</label><input type="text"  name="card_expiry" id="card_expiry" placeholder="MM/YY" required></div>
                            <div><label>CVV:</label><input type="text" name="card_cvv" id="card_cvv" placeholder="xxx" required></div>
                        </div>
                </div>

                <div class="payment-method-content qr-content">
                    <h3 class="section-subtitle">Quét mã QR để thanh toán</h3>
                    <div class="qr-box">
                        <img src="../assets/images/QR.jpg" alt="QR Code Payment" class="qr-image">
                        <p class="qr-note">Số tiền: <strong>{{ number_format($total) }}₫</strong></p>
                        <p class="qr-note">Nội dung CK: **LEVIOSA{{ $bookingId }}**</p>
                    </div>
                </div>

            </div>
            
            {{-- NÚT THANH TOÁN --}}
            <div class="payment-buttons">
                @auth('customer')
                    <!-- HIỂN THỊ NÚT THANH TOÁN THỰC KHI ĐÃ ĐĂNG NHẬP -->
                    <a href="#" class="btn-pay card-tab active" data-target="card">
                        💳 Thanh toán qua thẻ
                    </a>

                    <a href="#" class="btn-pay qr-tab" data-target="qr">
                        🧾 Thanh toán QR ngân hàng
                    </a>
                @else
                    <button class="btn-pay btn-disabled" disabled>
                        🔒 Vui lòng Đăng nhập để Thanh toán
                    </button>
                @endauth
            </div>
            <div class="confirm-payment">
                <button type="submit" class="btn-confirm-pay">
                    ✅ Xác nhận thanh toán
                </button>
            </div>

            </form>
        </div>
    </div>
    @include('layouts.footer')

    <script src="{{ asset('assets/js/checkout.js') }}"></script>
    <script src="{{ asset('assets/js/Cus_header_footer.js') }}"></script>
</body>
</html>
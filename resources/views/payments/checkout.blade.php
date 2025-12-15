@extends('layouts.app')

@section('title', 'Thanh toán')

@section('content')

<style>
    .checkout-container {
        max-width: 1000px;
        margin: 40px auto;
        padding: 0 15px;
    }

    .checkout-header {
        font-size: 28px;
        font-weight: 600;
        margin-bottom: 30px;
        color: #2c3e50;
    }

    .checkout-grid {
        display: grid;
        grid-template-columns: 1fr 350px;
        gap: 25px;
    }

    .checkout-details {
        background: #fff;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    }

    .section-title {
        font-size: 16px;
        font-weight: 600;
        margin-bottom: 15px;
        color: #34495e;
        border-bottom: 2px solid #f0f0f0;
        padding-bottom: 10px;
    }

    .item-group {
        margin-bottom: 20px;
    }

    .item-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 12px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .item-info {
        flex: 1;
    }

    .item-name {
        font-weight: 500;
        color: #2c3e50;
        margin-bottom: 4px;
    }

    .item-details {
        font-size: 13px;
        color: #7f8c8d;
    }

    .item-price {
        text-align: right;
        font-weight: 600;
        color: #e67e22;
        min-width: 100px;
    }

    .summary-section {
        background: #f9f9f9;
        border-radius: 8px;
        padding: 20px;
        margin-top: 20px;
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
        color: #34495e;
    }

    .summary-row.total {
        font-size: 18px;
        font-weight: 700;
        color: #e67e22;
        border-top: 2px solid #ddd;
        padding-top: 10px;
        margin-top: 15px;
    }

    .payment-form {
        background: #fff;
        border-radius: 10px;
        padding: 25px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        position: sticky;
        top: 20px;
    }

    .form-group {
        margin-bottom: 15px;
    }

    .form-label {
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 8px;
        display: block;
    }

    .form-control {
        width: 100%;
        padding: 10px 12px;
        border: 1px solid #ddd;
        border-radius: 6px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-control:focus {
        outline: none;
        border-color: #e67e22;
        box-shadow: 0 0 0 3px rgba(230, 126, 34, 0.1);
    }

    .btn-primary {
        width: 100%;
        padding: 12px;
        background: #e67e22;
        color: #fff;
        border: none;
        border-radius: 6px;
        font-weight: 600;
        font-size: 15px;
        cursor: pointer;
        transition: background 0.3s;
        margin-top: 10px;
    }

    .btn-primary:hover {
        background: #d35400;
    }

    .btn-secondary {
        width: 100%;
        padding: 10px;
        background: #ecf0f1;
        color: #34495e;
        border: none;
        border-radius: 6px;
        font-weight: 500;
        font-size: 14px;
        cursor: pointer;
        margin-bottom: 10px;
        transition: background 0.3s;
    }

    .btn-secondary:hover {
        background: #d5dbdb;
    }

    @media (max-width: 768px) {
        .checkout-grid {
            grid-template-columns: 1fr;
        }

        .payment-form {
            position: static;
        }
    }
</style>

<div class="checkout-container">
    <h1 class="checkout-header">
        <i class="fas fa-shopping-cart"></i> Thanh toán đặt phòng
    </h1>

    <div class="checkout-grid">
        <div class="checkout-details">
            <!-- PHÒNG -->
            <div class="item-group">
                <h3 class="section-title">
                    <i class="fas fa-door-open"></i> Phòng ({{ count($booking->bookingRooms) }})
                </h3>
                @foreach($booking->bookingRooms as $br)
                <div class="item-row">
                    <div class="item-info">
                        <div class="item-name">{{ $br->room->RoomName }}</div>
                        <div class="item-details">
                            {{ \Carbon\Carbon::parse($br->CheckInDate)->diffInDays(\Carbon\Carbon::parse($br->CheckOutDate)) }} đêm x {{ number_format($br->room->PricePerNight) }} VNĐ
                        </div>
                    </div>
                    <div class="item-price">{{ number_format($br->TotalAmount) }} VNĐ</div>
                </div>
                @endforeach
            </div>

            <!-- DỊCH VỤ -->
            @if($booking->services->count() > 0)
            <div class="item-group">
                <h3 class="section-title">
                    <i class="fas fa-concierge-bell"></i> Dịch vụ ({{ $booking->services->count() }})
                </h3>
                @foreach($booking->services as $service)
                <div class="item-row">
                    <div class="item-info">
                        <div class="item-name">{{ $service->service->ServiceName }}</div>
                        <div class="item-details">
                            {{ $service->Quantity }} x {{ number_format($service->service->Price) }} VNĐ
                        </div>
                    </div>
                    <div class="item-price">{{ number_format($service->TotalServicePrice ?? 0) }} VNĐ</div>
                </div>
                @endforeach
            </div>
            @endif

            <!-- TÓM TẮT TIỀN -->
            <div class="summary-section">
                <div class="summary-row">
                    <span>Tiền phòng:</span>
                    <span>{{ number_format($roomTotal) }} VNĐ</span>
                </div>
                @if($serviceTotal > 0)
                <div class="summary-row">
                    <span>Tiền dịch vụ:</span>
                    <span>{{ number_format($serviceTotal) }} VNĐ</span>
                </div>
                @endif
                @if($discountAmount > 0)
                <div class="summary-row" style="color: #27ae60;">
                    <span>Giảm giá:</span>
                    <span>-{{ number_format($discountAmount) }} VNĐ</span>
                </div>
                @endif
                <div class="summary-row total">
                    <span>Tổng cộng:</span>
                    <span>{{ number_format($total) }} VNĐ</span>
                </div>
            </div>
        </div>

        <!-- FORM THANH TOÁN -->
        <form method="POST" action="{{ route('payment.store', $booking->BookingID) }}" class="payment-form">
            @csrf

            <h3 class="section-title">Thanh toán</h3>

            <div class="form-group">
                <label class="form-label">Chọn phương thức thanh toán</label>
                <select name="PaymentMethod" class="form-control" required>
                    <option value="">-- Chọn phương thức --</option>
                    <option value="Tiền mặt">Tiền mặt</option>
                    <option value="Chuyển khoản ngân hàng">Chuyển khoản ngân hàng</option>
                    <option value="Thẻ tín dụng">Thẻ tín dụng</option>
                </select>
            </div>

            <button type="button" class="btn-secondary" onclick="window.history.back()">
                <i class="fas fa-arrow-left"></i> Quay lại
            </button>
            
            @php
                // Kiểm tra xem booking đã có payment 'Đã thanh toán' chưa
                $paidPayment = $booking->payments()->where('PaymentStatus', 'Đã thanh toán')->first();
            @endphp
            
            @if($paidPayment)
                <button type="button" class="btn-primary" disabled style="opacity: 0.6; cursor: not-allowed;">
                    <i class="fas fa-check-circle"></i> Đã thanh toán
                </button>
            @else
                <button type="submit" class="btn-primary">
                    <i class="fas fa-check"></i> Xác nhận thanh toán
                </button>
            @endif
        </form>
    </div>
</div>

@endsection

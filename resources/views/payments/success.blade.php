@extends('layouts.app')

@section('title', 'Thanh toán thành công')

@section('content')

<style>
    .success-container {
        max-width: 600px;
        margin: 60px auto;
        padding: 0 15px;
        text-align: center;
    }

    .success-box {
        background: #fff;
        border-radius: 10px;
        padding: 40px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
    }

    .success-icon {
        width: 80px;
        height: 80px;
        background: #e8f8f1;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 20px;
        font-size: 40px;
        color: #27ae60;
    }

    .success-title {
        font-size: 28px;
        font-weight: 700;
        color: #27ae60;
        margin-bottom: 10px;
    }

    .success-message {
        font-size: 16px;
        color: #7f8c8d;
        margin-bottom: 30px;
        line-height: 1.6;
    }

    .payment-details {
        background: #f9f9f9;
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 30px;
        text-align: left;
    }

    .detail-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #eee;
        font-size: 14px;
    }

    .detail-row:last-child {
        border-bottom: none;
    }

    .detail-label {
        color: #7f8c8d;
        font-weight: 500;
    }

    .detail-value {
        color: #2c3e50;
        font-weight: 600;
    }

    .amount-highlight {
        color: #e67e22;
        font-size: 18px;
        font-weight: 700;
    }

    .btn-group {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
    }

    .btn {
        padding: 12px 20px;
        border: none;
        border-radius: 6px;
        font-size: 15px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
        text-decoration: none;
        display: inline-block;
    }

    .btn-primary {
        background: #e67e22;
        color: #fff;
    }

    .btn-primary:hover {
        background: #d35400;
    }

    .btn-secondary {
        background: #ecf0f1;
        color: #34495e;
    }

    .btn-secondary:hover {
        background: #d5dbdb;
    }

    @media (max-width: 600px) {
        .success-container {
            margin: 30px auto;
        }

        .success-box {
            padding: 25px;
        }

        .btn-group {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="success-container">
    <div class="success-box">
        <div class="success-icon">
            <i class="fas fa-check"></i>
        </div>

        <h1 class="success-title">Thanh toán thành công!</h1>
        <p class="success-message">
            Cảm ơn bạn đã đặt phòng tại Leviosa Resort. Hóa đơn đã được gửi vào email của bạn.
        </p>

        <div class="payment-details">
            <div class="detail-row">
                <span class="detail-label">Mã thanh toán:</span>
                <span class="detail-value">#{{ $payment->PaymentID }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Mã booking:</span>
                <span class="detail-value">{{ $payment->BookingID }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Ngày thanh toán:</span>
                <span class="detail-value">
                    {{ \Carbon\Carbon::parse($payment->PaymentDate)->format('d/m/Y H:i') }}
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Phương thức:</span>
                <span class="detail-value">{{ $payment->PaymentMethod }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Trạng thái:</span>
                <span class="detail-value">
                    @if($payment->PaymentStatus == 'Đã thanh toán')
                        <span style="color: #27ae60;">✓ {{ $payment->PaymentStatus }}</span>
                    @elseif($payment->PaymentStatus == 'Chờ thanh toán')
                        <span style="color: #f39c12;">⏱ {{ $payment->PaymentStatus }}</span>
                    @else
                        {{ $payment->PaymentStatus }}
                    @endif
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Số tiền:</span>
                <span class="detail-value amount-highlight">
                    {{ number_format($payment->Amount) }} VNĐ
                </span>
            </div>
        </div>

        <div class="btn-group">
            <a href="{{ route('payment.invoice', $payment->PaymentID) }}" class="btn btn-secondary">
                <i class="fas fa-download"></i> Tải hóa đơn
            </a>
            <a href="{{ route('home') }}" class="btn btn-primary">
                <i class="fas fa-home"></i> Về trang chủ
            </a>
        </div>
    </div>
</div>

@endsection

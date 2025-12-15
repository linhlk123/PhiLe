<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hóa đơn #{{ $payment->PaymentID }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            line-height: 1.6;
        }

        .invoice-container {
            max-width: 900px;
            margin: 0 auto;
            padding: 40px;
            background: #fff;
        }

        .invoice-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #1d5a2e;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .invoice-logo {
            font-size: 28px;
            font-weight: 700;
            color: #1d5a2e;
        }

        .invoice-title {
            text-align: right;
        }

        .invoice-title h1 {
            font-size: 24px;
            color: #2c3e50;
            margin-bottom: 5px;
        }

        .invoice-title p {
            color: #7f8c8d;
            font-size: 14px;
        }

        .invoice-info {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
            margin-bottom: 30px;
        }

        .info-block h3 {
            color: #2c3e50;
            font-size: 14px;
            font-weight: 600;
            margin-bottom: 10px;
            text-transform: uppercase;
            border-bottom: 2px solid #ecf0f1;
            padding-bottom: 8px;
        }

        .info-block p {
            margin-bottom: 5px;
            font-size: 13px;
            color: #555;
        }

        .info-block strong {
            color: #2c3e50;
            display: block;
            font-size: 14px;
            margin-bottom: 3px;
        }

        .invoice-items {
            margin-bottom: 30px;
        }

        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .items-table thead {
            background: #f5f7fa;
        }

        .items-table th,
        .items-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ecf0f1;
            font-size: 13px;
        }

        .items-table th {
            font-weight: 600;
            color: #2c3e50;
        }

        .items-table tbody tr:nth-child(even) {
            background: #fafafa;
        }

        .text-right {
            text-align: right;
        }

        .amount {
            font-weight: 600;
            color: #1d5a2e;
        }

        .summary {
            margin-left: auto;
            width: 350px;
            border: 1px solid #ecf0f1;
            padding: 20px;
            border-radius: 6px;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
            font-size: 13px;
        }

        .summary-row.total {
            border-top: 2px solid #ecf0f1;
            padding-top: 10px;
            margin-top: 10px;
            font-size: 16px;
            font-weight: 700;
            color: #1d5a2e;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ecf0f1;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .invoice-container {
                max-width: 100%;
                padding: 0;
            }
        }
    </style>
</head>
<body>
    <div class="invoice-container">
        <!-- HEADER -->
        <div class="invoice-header">
            <div class="invoice-logo">🏨 LEVIOSA RESORT</div>
            <div class="invoice-title">
                <h1>HÓA ĐƠN THANH TOÁN</h1>
                <p>Invoice #{{ str_pad($payment->PaymentID, 6, '0', STR_PAD_LEFT) }}</p>
            </div>
        </div>

        <!-- INFO -->
        <div class="invoice-info">
            <div class="info-block">
                <h3>Thông tin khách hàng</h3>
                <strong>{{ $payment->booking->customer->FullName ?? 'N/A' }}</strong>
                <p>Email: {{ $payment->booking->customer->Email ?? 'N/A' }}</p>
                <p>Điện thoại: {{ $payment->booking->customer->Phone ?? 'N/A' }}</p>
            </div>
            <div class="info-block">
                <h3>Thông tin thanh toán</h3>
                <p><strong>Ngày thanh toán:</strong><br>
                    {{ \Carbon\Carbon::parse($payment->PaymentDate)->format('d/m/Y H:i') }}
                </p>
                <p><strong>Phương thức:</strong><br>{{ $payment->PaymentMethod }}</p>
                <p><strong>Trạng thái:</strong><br>{{ $payment->PaymentStatus }}</p>
                <p><strong>Mã booking:</strong><br>{{ $payment->BookingID }}</p>
            </div>
        </div>

        <!-- ITEMS -->
        <div class="invoice-items">
            <h3 style="margin-bottom: 15px; font-size: 14px; color: #2c3e50; font-weight: 600;">Chi tiết dịch vụ</h3>
            <table class="items-table">
                <thead>
                    <tr>
                        <th>Mô tả</th>
                        <th style="width: 100px;" class="text-right">Số lượng</th>
                        <th style="width: 120px;" class="text-right">Đơn giá</th>
                        <th style="width: 120px;" class="text-right">Thành tiền</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="4" style="padding: 12px; font-weight: 600; background: #fafafa; border: none;">PHÒNG</td>
                    </tr>
                    @foreach($payment->booking->bookingRooms as $room)
                    <tr>
                        <td>
                            {{ $room->room->RoomName }} 
                            <br>
                            <small style="color: #7f8c8d;">
                                {{ \Carbon\Carbon::parse($room->CheckInDate)->format('d/m/Y') }} - 
                                {{ \Carbon\Carbon::parse($room->CheckOutDate)->format('d/m/Y') }}
                            </small>
                        </td>
                        <td class="text-right">
                            {{ \Carbon\Carbon::parse($room->CheckInDate)->diffInDays(\Carbon\Carbon::parse($room->CheckOutDate)) }} đêm
                        </td>
                        <td class="text-right">{{ number_format($room->room->PricePerNight) }} VNĐ</td>
                        <td class="text-right amount">{{ number_format($room->TotalAmount ?? 0) }} VNĐ</td>
                    </tr>
                    @endforeach

                    @if($payment->booking->services->count() > 0)
                    <tr>
                        <td colspan="4" style="padding: 12px; font-weight: 600; background: #fafafa; border: none;">DỊCH VỤ</td>
                    </tr>
                    @foreach($payment->booking->services as $service)
                    <tr>
                        <td>{{ $service->service->ServiceName }}</td>
                        <td class="text-right">{{ $service->Quantity }}</td>
                        <td class="text-right">{{ number_format($service->service->Price) }} VNĐ</td>
                        <td class="text-right amount">{{ number_format($service->TotalServicePrice ?? 0) }} VNĐ</td>
                    </tr>
                    @endforeach
                    @endif
                </tbody>
            </table>
        </div>

        <!-- SUMMARY -->
        <div style="display: flex; justify-content: flex-end; margin-bottom: 40px;">
            <div class="summary">
                <div class="summary-row">
                    <span>Tổng tiền:</span>
                    <span class="amount">{{ number_format($payment->Amount) }} VNĐ</span>
                </div>
                <div class="summary-row total">
                    <span>THANH TOÁN:</span>
                    <span>{{ number_format($payment->Amount) }} VNĐ</span>
                </div>
            </div>
        </div>

        <!-- FOOTER -->
        <div class="footer">
            <p><strong>Leviosa Resort</strong> | Địa chỉ: Khu phố 6, Linh Trung, TP.Thủ Đức, TP.HCM, Việt Nam</p>
            <p>Cảm ơn bạn đã sử dụng dịch vụ của chúng tôi!</p>
            <p style="margin-top: 20px; font-size: 11px;">Tài liệu này được tạo tự động vào {{ now()->format('d/m/Y H:i') }}</p>
        </div>
    </div>
</body>
</html>

@extends('layouts.app')

@section('title', 'Hóa đơn của tôi')

@section('content')

<style>
    .invoice-container {
        max-width: 1100px;
        margin: 40px auto;
        padding: 0 15px;
    }

    .page-title {
        font-size: 26px;
        font-weight: 600;
        margin-bottom: 25px;
        color: #2c3e50;
    }

    .invoice-card {
        background: #fff;
        border-radius: 10px;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.08);
        overflow-x: auto;
    }

    .invoice-table {
        width: 100%;
        border-collapse: collapse;
        min-width: 800px;
    }

    .invoice-table thead {
        background: #f5f7fa;
    }

    .invoice-table th,
    .invoice-table td {
        padding: 14px 16px;
        text-align: center;
        border-bottom: 1px solid #eee;
        font-size: 15px;
    }

    .invoice-table th {
        font-weight: 600;
        color: #34495e;
    }

    .invoice-table tr:hover {
        background: #fafafa;
    }

    .amount {
        font-weight: 600;
        color: #e67e22;
    }

    .badge {
        padding: 6px 12px;
        border-radius: 20px;
        font-size: 13px;
        font-weight: 600;
        display: inline-block;
    }

    .badge-paid {
        background: #e8f8f1;
        color: #27ae60;
    }

    .badge-unpaid {
        background: #fdecea;
        color: #e74c3c;
    }

    .no-data {
        padding: 40px;
        text-align: center;
        color: #999;
        font-size: 16px;
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 22px;
        }
    }
</style>

<div class="invoice-container">

    <h2 class="page-title">
        <i class="fas fa-file-invoice-dollar"></i> Hóa đơn của tôi
    </h2>

    <div class="invoice-card">
        <table class="invoice-table">
            <thead>
                <tr>
                    <th>Mã hóa đơn</th>
                    <th>Mã booking</th>
                    <th>Ngày thanh toán</th>
                    <th>Số tiền</th>
                    <th>Phương thức</th>
                    <th>Trạng thái</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payments as $payment)
                <tr>
                    <td>#{{ $payment->PaymentID }}</td>
                    <td>{{ $payment->BookingID }}</td>
                    <td>
                        {{ \Carbon\Carbon::parse($payment->PaymentDate)->format('d/m/Y H:i') }}
                    </td>
                    <td class="amount">
                        {{ number_format($payment->Amount) }} VNĐ
                    </td>
                    <td>{{ $payment->PaymentMethod }}</td>
                    <td>
                        @if($payment->PaymentStatus === 'Đã thanh toán')
                        <span class="badge badge-paid">Đã thanh toán</span>
                        @else
                        <span class="badge badge-unpaid">Chưa thanh toán</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="no-data">
                        <i class="fas fa-receipt"></i><br>
                        Bạn chưa có hóa đơn nào
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

@endsection
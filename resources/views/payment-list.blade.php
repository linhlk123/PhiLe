@include('layouts.header')

<section class="container" style="padding:32px 0;">
  <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:16px;">
    <h2 style="margin:0;">Quản lý Thanh toán</h2>
    <a class="room__btn" href="{{ route('booking') }}">ĐẶT PHÒNG</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success" style="margin-bottom:12px;">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger" style="margin-bottom:12px;">{{ session('error') }}</div>
  @endif
  @if(session('info'))
    <div class="alert alert-info" style="margin-bottom:12px;">{{ session('info') }}</div>
  @endif

  <div class="card" style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden;">
    <div style="padding:16px; border-bottom:1px solid #e5e7eb; display:flex; align-items:center; justify-content:space-between;">
      <div>
        <strong>Khách hàng:</strong> <span>{{ $customerName ?: 'Khách' }}</span>
      </div>
      <div style="display:flex; gap:8px;">
        <span style="background:#fff4e6; color:#c77700; padding:6px 10px; border-radius:999px; font-size:12px;">Đặt phòng cần thanh toán</span>
      </div>
    </div>

    <div style="overflow:auto;">
      <table class="table" style="width:100%; border-collapse:collapse;">
        <thead>
          <tr style="background:#f8fafc;">
            <th style="padding:10px; text-align:left; border-bottom:1px solid #e5e7eb;">Mã Booking</th>
            <th style="padding:10px; text-align:left; border-bottom:1px solid #e5e7eb;">Phòng</th>
            <th style="padding:10px; text-align:left; border-bottom:1px solid #e5e7eb;">Loại</th>
            <th style="padding:10px; text-align:left; border-bottom:1px solid #e5e7eb;">Check-in</th>
            <th style="padding:10px; text-align:left; border-bottom:1px solid #e5e7eb;">Check-out</th>
            <th style="padding:10px; text-align:right; border-bottom:1px solid #e5e7eb;">Tổng tiền</th>
            <th style="padding:10px; text-align:center; border-bottom:1px solid #e5e7eb;">Trạng thái TT</th>
            <th style="padding:10px; text-align:center; border-bottom:1px solid #e5e7eb;">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @forelse($bookingRooms as $item)
            <tr>
              <td style="padding:10px; border-bottom:1px solid #f1f5f9;">
                <div style="display:flex; flex-direction:column; gap:2px;">
                  <span style="font-weight:600;">#{{ $item->BookingID }}</span>
                  <span style="color:#64748b; font-size:12px;">{{ $item->BookingDate }}</span>
                </div>
              </td>
              <td style="padding:10px; border-bottom:1px solid #f1f5f9;">
                <div style="display:flex; flex-direction:column; gap:2px;">
                  <span style="font-weight:600;">#{{ $item->RoomNumber }}</span>
                  <span style="color:#64748b; font-size:12px;">ID: {{ $item->RoomID }}</span>
                </div>
              </td>
              <td style="padding:10px; border-bottom:1px solid #f1f5f9;">{{ $item->TypeName }}</td>
              <td style="padding:10px; border-bottom:1px solid #f1f5f9;">{{ $item->CheckInDate }}</td>
              <td style="padding:10px; border-bottom:1px solid #f1f5f9;">{{ $item->CheckOutDate }}</td>
              <td style="padding:10px; border-bottom:1px solid #f1f5f9; text-align:right; font-weight:600; color:#e67e22;">
                {{ number_format($item->TotalAmount, 0, ',', '.') }} đ
              </td>
              <td style="padding:10px; border-bottom:1px solid #f1f5f9; text-align:center;">
                @if($item->PaymentStatus === 'Đã thanh toán')
                  <span style="background:#eef7ee; color:#1d5a2e; padding:6px 10px; border-radius:999px; font-size:12px; font-weight:600;">
                    ✓ Đã thanh toán
                  </span>
                @elseif($item->PaymentStatus === 'Chờ thanh toán')
                  <span style="background:#fff4e6; color:#c77700; padding:6px 10px; border-radius:999px; font-size:12px; font-weight:600;">
                    ⏱ Chờ thanh toán
                  </span>
                @else
                  <span style="background:#f3f4f6; color:#6b7280; padding:6px 10px; border-radius:999px; font-size:12px;">
                    Chưa thanh toán
                  </span>
                @endif
              </td>
              <td style="padding:10px; border-bottom:1px solid #f1f5f9;">
                <div style="display:flex; gap:10px; justify-content:center;">
                  @if($item->PaymentStatus === 'Đã thanh toán')
                    <button type="button" style="padding:8px 14px; font-size:14px; background:#e5e7eb; color:#6b7280; border:none; border-radius:6px; cursor:not-allowed; opacity:0.6;">
                      <i class="fas fa-check-circle"></i> Đã thanh toán
                    </button>
                  @else
                    <a href="{{ route('payment.checkout', ['bookingId' => $item->BookingID]) }}" 
                       class="room__btn" 
                       style="padding:8px 14px; font-size:14px; display:inline-flex; align-items:center; gap:6px;">
                      <i class="fas fa-credit-card"></i> Thanh toán
                    </a>
                  @endif
                </div>
              </td>
            </tr>
          @empty
            <tr>
              <td style="padding:16px; text-align:center; color:#64748b;" colspan="8">Không có booking nào cần thanh toán.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</section>

@include('layouts.footer')

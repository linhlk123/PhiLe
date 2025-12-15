@include('layouts.header')

<section class="container" style="padding:32px 0;">
  <div style="display:flex; align-items:center; justify-content:space-between; gap:16px; margin-bottom:16px;">
    <h2 style="margin:0;">Quản lý Checkin</h2>
    <a class="room__btn" href="{{ route('booking') }}">ĐẶT PHÒNG</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success" style="margin-bottom:12px;">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger" style="margin-bottom:12px;">{{ session('error') }}</div>
  @endif

  <div class="card" style="background:#fff; border:1px solid #e5e7eb; border-radius:12px; overflow:hidden;">
    <div style="padding:16px; border-bottom:1px solid #e5e7eb; display:flex; align-items:center; justify-content:space-between;">
      <div>
        <strong>Khách hàng:</strong> <span>{{ $customerName ?: 'Khách' }}</span>
      </div>
      <div style="display:flex; gap:8px;">
        <span style="background:#eef7ee; color:#1d5a2e; padding:6px 10px; border-radius:999px; font-size:12px;">Đặt phòng gần đây</span>
      </div>
    </div>

    <div style="overflow:auto;">
      <table class="table" style="width:100%; border-collapse:collapse;">
        <thead>
          <tr style="background:#f8fafc;">
            <th style="padding:10px; text-align:left; border-bottom:1px solid #e5e7eb;">Phòng</th>
            <th style="padding:10px; text-align:left; border-bottom:1px solid #e5e7eb;">Loại</th>
            <th style="padding:10px; text-align:left; border-bottom:1px solid #e5e7eb;">Check-in</th>
            <th style="padding:10px; text-align:left; border-bottom:1px solid #e5e7eb;">Check-out</th>
            <th style="padding:10px; text-align:right; border-bottom:1px solid #e5e7eb;">Tổng tiền</th>
            <th style="padding:10px; text-align:center; border-bottom:1px solid #e5e7eb;">Người lớn</th>
            <th style="padding:10px; text-align:center; border-bottom:1px solid #e5e7eb;">Trẻ em</th>
            <th style="padding:10px; text-align:center; border-bottom:1px solid #e5e7eb;">Thao tác</th>
          </tr>
        </thead>
        <tbody>
          @forelse($bookingRooms as $item)
            <tr>
              <td style="padding:10px; border-bottom:1px solid #f1f5f9;">
                <div style="display:flex; flex-direction:column; gap:2px;">
                  <span style="font-weight:600;">#{{ $item->RoomNumber }}</span>
                  <span style="color:#64748b; font-size:12px;">ID: {{ $item->RoomID }}</span>
                </div>
              </td>
              <td style="padding:10px; border-bottom:1px solid #f1f5f9;">{{ $item->TypeName }}</td>
              <td style="padding:10px; border-bottom:1px solid #f1f5f9;">{{ $item->CheckInDate }}</td>
              <td style="padding:10px; border-bottom:1px solid #f1f5f9;">{{ $item->CheckOutDate }}</td>
              <td style="padding:10px; border-bottom:1px solid #f1f5f9; text-align:right;">{{ number_format($item->TotalAmount, 0, ',', '.') }} đ</td>
              <td style="padding:10px; border-bottom:1px solid #f1f5f9; text-align:center;">{{ $item->AdultAmount }}</td>
              <td style="padding:10px; border-bottom:1px solid #f1f5f9; text-align:center;">{{ $item->ChildAmount }}</td>
              <td style="padding:10px; border-bottom:1px solid #f1f5f9;">
                @php($status = $item->Status)
                @if($status === 'CheckedIn')
                  <button type="button" style="padding:8px 14px; font-size:14px; background:#e5e7eb; color:#6b7280; border:none; border-radius:6px; cursor:not-allowed;">
                    Đã check-in
                  </button>
                @elseif($status === 'Pending')
                  <div style="display:flex; gap:10px; justify-content:center;">
                    <form method="POST" action="{{ route('checkin.perform', ['bookingId' => $item->BookingID]) }}">
                      @csrf
                      <button type="submit" class="room__btn" style="padding:8px 14px; font-size:14px;"
                        onclick="return confirm('Xác nhận check-in cho booking #{{ $item->BookingID }}?');">
                        Check-in
                      </button>
                    </form>
                    <form method="POST" action="{{ route('checkin.cancel', ['bookingId' => $item->BookingID]) }}">
                      @csrf
                      <button type="submit" class="btn-offer" style="padding:8px 14px; font-size:14px;"
                        onclick="return confirm('Bạn muốn hủy check-in booking #{{ $item->BookingID }}?');">
                        Hủy check-in
                      </button>
                    </form>
                  </div>
                @elseif($status === 'Cancelled')
                  <button type="button" style="padding:8px 14px; font-size:14px; background:#e5e7eb; color:#6b7280; border:none; border-radius:6px; cursor:not-allowed;">
                    Đã hủy check-in
                  </button>
                @else
                  <button type="button" style="padding:8px 14px; font-size:14px; background:#e5e7eb; color:#6b7280; border:none; border-radius:6px; cursor:not-allowed;">
                    {{ $status }}
                  </button>
                @endif
              </td>
              </td>
            </tr>
          @empty
            <tr>
              <td style="padding:16px; text-align:center; color:#64748b;" colspan="8">Không có dữ liệu đặt phòng.</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>
</section>

@include('layouts.footer')
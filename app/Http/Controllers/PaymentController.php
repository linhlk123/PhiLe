<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
// use Barryvdh\DomPDF\Facade\Pdf;

class PaymentController extends Controller
{
    // =============================
    // TRANG DANH SÁCH HÓA ĐƠN ĐÃ THANH TOÁN
    // =============================
    public function showInvoiceListPage()
    {
        $customerId = Auth::guard('customer')->id();
        $customerName = Auth::guard('customer')->user()->FullName ?? 'Khách';

        // Lấy tất cả hóa đơn đã thanh toán của khách hàng
        $payments = DB::table('PAYMENTS as p')
            ->join('BOOKINGS as b', 'p.BookingID', '=', 'b.BookingID')
            ->join('BOOKING_ROOMS as br', 'b.BookingID', '=', 'br.BookingID')
            ->join('ROOMS as r', 'br.RoomID', '=', 'r.RoomID')
            ->join('ROOM_TYPES as rt', 'r.RoomTypeID', '=', 'rt.RoomTypeID')
            ->where('b.CustomerID', $customerId)
            ->where('p.PaymentStatus', 'Đã thanh toán')
            ->select(
                'p.PaymentID',
                'p.BookingID',
                'p.Amount',
                'p.PaymentMethod',
                'p.PaymentStatus',
                'p.PaymentDate',
                'b.BookingDate',
                'br.RoomID',
                'r.RoomNumber',
                'rt.TypeName'
            )
            ->orderBy('p.PaymentDate', 'desc')
            ->get();

        return view('invoice-list', compact('payments', 'customerName'));
    }

    // =============================
    // TRANG DANH SÁCH THANH TOÁN
    // =============================
    public function showPaymentListPage()
    {
        $customerId = Auth::guard('customer')->id();
        $customerName = Auth::guard('customer')->user()->FullName ?? 'Khách';

        // Lấy tất cả booking của khách hàng với thông tin phòng và payment
        $bookingRooms = DB::table('BOOKINGS as b')
            ->join('BOOKING_ROOMS as br', 'b.BookingID', '=', 'br.BookingID')
            ->join('ROOMS as r', 'br.RoomID', '=', 'r.RoomID')
            ->join('ROOM_TYPES as rt', 'r.RoomTypeID', '=', 'rt.RoomTypeID')
            ->leftJoin('PAYMENTS as p', function($join) {
                $join->on('b.BookingID', '=', 'p.BookingID')
                     ->where('p.PaymentStatus', '=', 'Đã thanh toán');
            })
            ->leftJoin(DB::raw('(SELECT BookingID, SUM(TotalPrice) as ServiceTotal FROM SERVICE_USAGES GROUP BY BookingID) as su'), 'b.BookingID', '=', 'su.BookingID')
            ->where('b.CustomerID', $customerId)
            ->whereIn('b.Status', ['Pending', 'CheckedIn', 'checkout'])
            ->select(
                'b.BookingID',
                'b.BookingDate',
                'b.Status',
                'b.AdultAmount',
                'b.ChildAmount',
                'br.RoomID',
                'br.CheckInDate',
                'br.CheckOutDate',
                DB::raw('CASE 
                    WHEN br.RoomID >= 216 THEN br.TotalAmount + COALESCE(su.ServiceTotal, 0)
                    ELSE br.TotalAmount
                END as TotalAmount'),
                'r.RoomNumber',
                'rt.TypeName',
                DB::raw('CASE 
                    WHEN p.PaymentID IS NOT NULL THEN "Đã thanh toán"
                    ELSE "Chờ thanh toán"
                END as PaymentStatus')
            )
            ->orderBy('b.BookingDate', 'desc')
            ->get();

        return view('payment-list', compact('bookingRooms', 'customerName'));
    }

    // =============================
    // TRANG CHECKOUT
    // =============================
    public function checkout($bookingId)
{
    // 1. Khởi tạo truy vấn
        $query = Booking::with(['bookingRooms.room.roomType', 'services.service'])
            ->where('BookingID', $bookingId);

        // 2. NẾU ĐÃ ĐĂNG NHẬP, ĐƠN HÀNG BẮT BUỘC PHẢI THUỘC VỀ KHÁCH HÀNG NÀY (Bảo mật)
        if (Auth::guard('customer')->check()) {
            $currentCustomerId = Auth::guard('customer')->id();
            $query->where('CustomerID', $currentCustomerId);
        }

        // 3. Thực thi truy vấn, nếu không tìm thấy sẽ trả về 404
        // Sử dụng firstOrFail thay vì findOrFail để áp dụng điều kiện where
        $booking = $query->firstOrFail();

    $rooms = [];
    $services = [];
    $roomTotal = 0;
    $serviceTotal = 0;
    $discountAmount = 0;   // mặc định không có giảm giá

    foreach ($booking->bookingRooms as $br) {
        $checkin  = Carbon::parse($br->CheckInDate)->startOfDay();
        $checkout = Carbon::parse($br->CheckOutDate)->startOfDay();

        $days = $checkin->diffInDays($checkout);
        if ($days < 1) $days = 1;

        $price = $br->room->PricePerNight;
        $total = $days * $price;

        $roomTotal += $total;

        $rooms[] = [
            'room' => $br->room,
            'days' => $days,
            'price' => $price,
            'total' => $total,
        ];
    }

    foreach ($booking->services as $bs) {
        $price = $bs->service->Price;
        $total = $price * $bs->Quantity;

        $serviceTotal += $total;

        $services[] = [
            'service' => $bs->service,
            'qty' => $bs->Quantity,
            'price' => $price,
            'total' => $total
        ];
    }

    $totalBeforeDiscount = $roomTotal + $serviceTotal; 
    $total = $totalBeforeDiscount; // chưa áp dụng giảm giá
    $bookingId = $booking->BookingID;

    return view('payments.checkout', [
        'booking' => $booking,
        'rooms' => $rooms,
        'services' => $services,
        'roomTotal' => $roomTotal,
        'serviceTotal' => $serviceTotal,
        'totalBeforeDiscount' => $totalBeforeDiscount, 
        'discountAmount' => $discountAmount, 
        'total' => $total,
        'bookingId' => $bookingId
    ]);
}

    public function findActiveBooking()
    {
        // 1. Lấy ID khách hàng đã đăng nhập
        if (!Auth::guard('customer')->check()) {
            return redirect()->route('customer.login');
        }
        $customerId = Auth::guard('customer')->id();

        // 2. TÌM KIẾM ĐƠN HÀNG MỚI NHẤT
        $latestBooking = Booking::where('CustomerID', $customerId)
                                ->where('Status', 'checkout') 
                                ->latest()
                                ->first();

        if ($latestBooking) {
            // 3. CHUYỂN HƯỚNG ĐẾN ROUTE CHI TIẾT
            return redirect()->route('payment.checkout', ['bookingId' => $latestBooking->BookingID]);
        }
        
        // 4. KHÔNG CÓ ĐƠN HÀNG ACTIVE: Chuyển hướng về trang đặt phòng
        return redirect()->route('booking')->with(' info', 'Bạn chưa có đơn hàng nào đang chờ thanh toán. Vui lòng bắt đầu đặt phòng.');
    }

    // =============================
    // XỬ LÝ THANH TOÁN
    // =============================
    public function store(Request $request, $bookingId)
    {
        $request->validate([
            'PaymentMethod' => 'required',
        ]);

        $booking = Booking::with(['bookingRooms.room.roomType', 'services.service'])
            ->findOrFail($bookingId);

        $roomTotal = 0;
        $serviceTotal = 0;

        // =============================
        // UPDATE TIỀN PHÒNG VÀ LƯU DB
        // =============================
        foreach ($booking->bookingRooms as $br) {

            $checkin  = Carbon::parse($br->CheckInDate)->startOfDay();
            $checkout = Carbon::parse($br->CheckOutDate)->startOfDay();
            $days = $checkin->diffInDays($checkout);

            if ($days < 0) $days = abs($days);
            if ($days < 1) $days = 1;

            $price = $br->room->PricePerNight;
            $total = $days * $price;

            $br->TotalAmount = $total;
            $br->save();

            $roomTotal += $total;
        }

        // =============================
        // UPDATE TIỀN DỊCH VỤ VÀ LƯU DB
        // =============================
        foreach ($booking->services as $bs) {

            $price = $bs->service->Price;
            $total = $price * $bs->Quantity;

            $bs->TotalPrice = $total;
            $bs->save();

            $serviceTotal += $total;
        }

        $total = $roomTotal + $serviceTotal;

        // =============================
        // TẠO PAYMENT / LƯU DB
        // =============================
        $payment = Payment::create([
            'BookingID' => $bookingId,
            'PaymentDate' => now(),
            'Amount' => $total,
            'PaymentMethod' => $request->PaymentMethod,
            'PaymentStatus' => 'Đã thanh toán',
        ]);

        // =============================
        // CẬP NHẬT TRẠNG THÁI BOOKING
        // =============================
        $booking->Status = 'checkout';
        $booking->save();

        return redirect()->route('payment.success', $payment->PaymentID);
    }

    // =============================
    // TRANG THANH TOÁN THÀNH CÔNG
    // =============================
    public function success($paymentId)
    {
        $payment = Payment::with('booking')->findOrFail($paymentId);

        return view('payments.success', compact('payment'));
    }

    public function invoice($paymentId)
    {
        $payment = Payment::with(['booking.customer', 'booking.bookingRooms.room.roomType', 'booking.services.service'])->findOrFail($paymentId);

        // Tạm thời hiển thị HTML trực tiếp thay vì PDF
        return view('payments.invoice', compact('payment'));
        
        // Sau khi cài barryvdh/laravel-dompdf, uncomment dòng dưới:
        // $pdf = Pdf::loadView('payments.invoice', compact('payment'));
        // return $pdf->download('hoa-don-' . $paymentId . '.pdf');
    }

}

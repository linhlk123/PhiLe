<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\RoomType;
use App\Models\Booking;
use App\Models\BookingRoom;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class BookingController extends Controller
{
    public function index()
    {
        $roomTypes = [
            'deluxe' => 'Deluxe',
            'sea' => 'Sea View',
            'villa' => 'Villa'
        ];

        return view('booking', compact('roomTypes'));
    }

    public function search(Request $request)
    {
        // Validate request
        $validated = $request->validate([
            'checkInDate' => 'required|date|after:today',
            'checkOutDate' => 'required|date|after:checkInDate',
            'roomType' => 'required|string',
            'promo' => 'nullable|string'
        ]);

        // Process search logic here
        return redirect()->back()->with('success', 'Đang tìm kiếm phòng phù hợp...');
    }

    /**
     * Store booking from customer (Multi-room support)
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        
        try {
            // Validate input
            $validated = $request->validate([
                'check-in' => 'required|date',
                'check-out' => 'required|date|after:check-in',
                'adults' => 'required|integer|min:1',
                'children' => 'nullable|integer|min:0',
                'rooms' => 'required|array|min:1',
                'rooms.*.selected-room-id' => 'required|string',
                'full-name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
            ]);

            // Lấy thông tin user đã đăng nhập (nếu có)
            $customer = auth()->guard('customer')->user();
            $customerID = $customer ? $customer->CustomerID : null;

            // Tính số đêm
            $checkIn = Carbon::parse($validated['check-in']);
            $checkOut = Carbon::parse($validated['check-out']);
            $nightAmount = $checkIn->diffInDays($checkOut);

            // Bước 1: Tạo Booking chính (không có RoomID, CheckInDate, CheckOutDate, TotalAmount)
            $booking = Booking::create([
                'CustomerID' => $customerID,
                'StaffID' => null, // Booking online không có staff
                'AdultAmount' => $validated['adults'],
                'ChildAmount' => $validated['children'] ?? 0,
                'BookingDate' => now(),
                'Status' => 'Pending', // Trạng thái chờ xác nhận
            ]);

            // Bước 2: Tạo BookingRoom cho từng phòng đã chọn
            $totalBookingAmount = 0;
            $roomNumbers = [];

            foreach ($validated['rooms'] as $roomData) {
                $roomNumber = $roomData['selected-room-id'];
                $room = Room::where('RoomNumber', $roomNumber)->first();

                if (!$room) {
                    throw new \Exception("Không tìm thấy phòng: {$roomNumber}");
                }

                // Kiểm tra phòng có available không
                if ($room->Status !== 'Available') {
                    throw new \Exception("Phòng {$roomNumber} không còn khả dụng!");
                }

                // Lấy giá phòng từ RoomType
                $roomType = $room->roomType;
                $pricePerNight = $roomType->PricePerNight ?? 0;
                $roomTotalAmount = $pricePerNight * $nightAmount;
                $totalBookingAmount += $roomTotalAmount;

                // Tạo BookingRoom
                BookingRoom::create([
                    'BookingID' => $booking->BookingID,
                    'RoomID' => $room->RoomID,
                    'CheckInDate' => $validated['check-in'],
                    'CheckOutDate' => $validated['check-out'],
                    'TotalAmount' => $roomTotalAmount
                ]);

                // Cập nhật trạng thái phòng thành 'Booked'
                $room->update(['Status' => 'Booked']);

                $roomNumbers[] = $roomNumber;
            }

            DB::commit();

            $roomList = implode(', ', $roomNumbers);
            return redirect()->back()->with('success', 
                "Đặt phòng thành công! Mã đặt phòng: #{$booking->BookingID}" .
                "Phòng: {$roomList}" .
                "Tổng tiền: " . number_format($totalBookingAmount, 0, ',', '.') . " VNĐ"
            );

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * API: Lấy danh sách phòng available theo loại phòng
     */
    public function getAvailableRooms(Request $request)
    {
        try {
            // Lấy danh sách các loại phòng
            $roomTypes = RoomType::with(['rooms' => function($query) {
                $query->where('Status', 'Available');
            }])->get();

            // Format dữ liệu trả về
            $result = $roomTypes->map(function($roomType) {
                return [
                    'id' => 'area_' . $roomType->RoomTypeID,
                    'name' => $roomType->TypeName,
                    'description' => $roomType->Description,
                    'capacity' => $roomType->MaxGuests ?? 2,
                    'rooms' => $roomType->rooms->map(function($room) use ($roomType) {
                        return [
                            'id' => $room->RoomNumber,
                            'roomId' => $room->RoomID,
                            'type' => strtolower($roomType->TypeName),
                            'capacity' => $roomType->MaxGuests ?? 2,
                            'bedCountDouble' => $room->Double_Bed ?? 0,
                            'bedCountSingle' => $room->Single_Bed ?? 0,
                            'status' => $room->Status
                        ];
                    })
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'areas' => $result
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Checkin page: join BOOKING_ROOMS, BOOKINGS, ROOMS, ROOM_TYPES
     */
    public function showCheckinPage()
    {
        $customer = auth()->guard('customer')->user();
        $customerName = $customer ? $customer->FullName ?? ($customer->CustomerName ?? '') : '';

        $bookingRooms = DB::table('BOOKING_ROOMS as br')
            ->join('BOOKINGS as b', 'b.BookingID', '=', 'br.BookingID')
            ->join('ROOMS as r', 'r.RoomID', '=', 'br.RoomID')
            ->join('ROOM_TYPES as rt', 'rt.RoomTypeID', '=', 'r.RoomTypeID')
            ->leftJoin(DB::raw('(SELECT BookingID, SUM(TotalPrice) as ServiceTotal FROM SERVICE_USAGES GROUP BY BookingID) as su'), 'b.BookingID', '=', 'su.BookingID')
            ->select(
                'br.RoomID',
                'br.CheckInDate',
                'br.CheckOutDate',
                DB::raw('CASE 
                    WHEN br.RoomID >= 216 THEN br.TotalAmount + COALESCE(su.ServiceTotal, 0)
                    ELSE br.TotalAmount
                END as TotalAmount'),
                'b.AdultAmount',
                'b.ChildAmount',
                'b.BookingID',
                'b.Status',
                'r.RoomNumber',
                'rt.TypeName'
            )
            ->when($customer, function($q) use ($customer) {
                // chỉ lấy booking của customer đang đăng nhập nếu có
                $q->where('b.CustomerID', $customer->CustomerID);
            })
            ->orderBy('br.CheckInDate', 'desc')
            ->get();

        return view('checkin', compact('bookingRooms', 'customerName'));
    }

    /**
     * Perform Checkin: update BOOKINGS.Status => 'CheckedIn'
     */
    public function performCheckin($bookingId)
    {
        try {
            DB::table('BOOKINGS')->where('BookingID', $bookingId)->update(['Status' => 'CheckedIn']);
            return redirect()->route('checkin.page')->with('success', 'Checkin thành công.');
        } catch (\Exception $e) {
            return redirect()->route('checkin.page')->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }

    /**
     * Cancel Checkin: update BOOKINGS.Status => 'Pending'
     */
    public function cancelCheckin($bookingId)
    {
        try {
            DB::table('BOOKINGS')->where('BookingID', $bookingId)->update(['Status' => 'Pending']);
            return redirect()->route('checkin.page')->with('success', 'Đã hủy Checkin.');
        } catch (\Exception $e) {
            return redirect()->route('checkin.page')->with('error', 'Lỗi: ' . $e->getMessage());
        }
    }
}
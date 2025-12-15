<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Staff;
use App\Models\Service;
use App\Models\ServiceUsage;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\Customer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    /**
     * Hiển thị form đăng nhập nhân viên
     */
    public function showLoginForm()
    {
        return view('auth.staff-login');
    }


    /**
     * Xử lý đăng nhập 
     **/
    public function login(Request $request)
    {
        try {
            Log::info('Staff login request data:', $request->all());

            $validated = $request->validate([
                'Email' => 'required|email',
                'password' => 'required|string',
            ]);

            $staff = Staff::where('Email', $validated['Email'])->first();

            if (!$staff) {
                Log::warning('Staff not found for email:', ['email' => $validated['Email']]);
                return response()->json([
                    'success' => false,
                    'message' => 'Email hoặc mật khẩu không chính xác.'
                ], 401);
            }

            // Kiểm tra mật khẩu: ưu tiên bcrypt, fallback plaintext rồi nâng cấp
            $rawInput = $validated['password'];
            $stored = $staff->password;
            $isHashed = is_string($stored) && preg_match('/^\$2y\$\d{2}\$/', $stored);
            $authOk = false;

            if ($isHashed) {
                $authOk = Hash::check($rawInput, $stored);
            } else {
                // DB còn plaintext: so sánh trực tiếp và nâng cấp nếu đúng
                if (hash_equals($stored, $rawInput)) {
                    $authOk = true;
                    // Nâng cấp lên bcrypt
                    $staff->password = $rawInput; // mutator sẽ hash
                    $staff->save();
                    Log::info('Upgraded plaintext staff password to hash', ['staff_id' => $staff->StaffID]);
                }
            }

            if (!$authOk) {
                Log::warning('Staff login failed', ['email' => $validated['Email']]);
                return response()->json([
                    'success' => false,
                    'message' => 'Email hoặc mật khẩu không chính xác.'
                ], 401);
            }

            // Đăng nhập guard staff (session based)
            Auth::guard('staff')->login($staff);

            // Nếu request muốn JSON (Ajax/API) trả JSON, nếu không thì redirect
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đăng nhập thành công',
                    'staff' => [
                        'id' => $staff->StaffID,
                        'FullName' => $staff->FullName,
                        'Email' => $staff->Email,
                        'Role' => $staff->Role,
                    ],
                    'redirect' => route('staff.staff-room')
                ]);
            }

            return redirect()->route('staff.staff-room');

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Staff login validation error:', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Staff login error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đăng nhập. Vui lòng thử lại sau.'
            ], 500);
        }
    }

    /**
     * Xử lý đăng xuất nhân viên
     */
    public function logout(Request $request)
    {
        Auth::guard('staff')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Đăng xuất thành công!');
    }

    /**
     * Hiển thị trang quản lý phòng
     */
    public function staffRoom()
    {
        // Lấy thống kê số lượng phòng theo loại
        $roomStats = DB::table('ROOMS')
            ->join('ROOM_TYPES', 'ROOMS.RoomTypeID', '=', 'ROOM_TYPES.RoomTypeID')
            ->select(
                'ROOM_TYPES.TypeName',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN ROOMS.Status = "Available" THEN 1 ELSE 0 END) as available'),
                DB::raw('SUM(CASE WHEN ROOMS.Status = "Occupied" OR ROOMS.Status = "Booked" THEN 1 ELSE 0 END) as occupied'),
                DB::raw('SUM(CASE WHEN ROOMS.Status = "Cleaning" THEN 1 ELSE 0 END) as cleaning'),
                DB::raw('SUM(CASE WHEN ROOMS.Status = "Maintenance" THEN 1 ELSE 0 END) as maintenance')
            )
            ->groupBy('ROOM_TYPES.RoomTypeID', 'ROOM_TYPES.TypeName')
            ->get()
            ->keyBy('TypeName');
        
        // Thêm thống kê cho phòng Service (E-...)
        $serviceRoomStats = DB::table('ROOMS')
            ->where('RoomNumber', 'LIKE', 'E-%')
            ->select(
                DB::raw('"Service" as TypeName'),
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN Status = "Available" THEN 1 ELSE 0 END) as available'),
                DB::raw('SUM(CASE WHEN Status = "Occupied" OR Status = "Booked" THEN 1 ELSE 0 END) as occupied'),
                DB::raw('SUM(CASE WHEN Status = "Cleaning" THEN 1 ELSE 0 END) as cleaning'),
                DB::raw('SUM(CASE WHEN Status = "Maintenance" THEN 1 ELSE 0 END) as maintenance')
            )
            ->first();
        
        if ($serviceRoomStats && $serviceRoomStats->total > 0) {
            $roomStats['Service'] = $serviceRoomStats;
        }

        // Lấy danh sách booking với thông tin khách hàng và phòng (Multi-room support)
        $bookings = DB::table('BOOKINGS')
            ->join('CUSTOMERS', 'BOOKINGS.CustomerID', '=', 'CUSTOMERS.CustomerID')
            ->leftJoin('BOOKING_ROOMS', 'BOOKINGS.BookingID', '=', 'BOOKING_ROOMS.BookingID')
            ->leftJoin('ROOMS', 'BOOKING_ROOMS.RoomID', '=', 'ROOMS.RoomID')
            ->leftJoin('ROOM_TYPES', 'ROOMS.RoomTypeID', '=', 'ROOM_TYPES.RoomTypeID')
            ->select(
                'BOOKINGS.BookingID',
                'BOOKINGS.CustomerID',
                'BOOKINGS.StaffID',
                'BOOKINGS.AdultAmount',
                'BOOKINGS.ChildAmount',
                'BOOKINGS.BookingDate',
                'BOOKINGS.Status',
                'CUSTOMERS.FullName',
                'CUSTOMERS.Gender',
                'CUSTOMERS.Phone',
                'CUSTOMERS.Email',
                'CUSTOMERS.IDNumber',
                'CUSTOMERS.Address',
                // Lấy danh sách số phòng (ngăn cách bằng dấu phẩy)
                DB::raw('GROUP_CONCAT(DISTINCT ROOMS.RoomNumber ORDER BY ROOMS.RoomNumber SEPARATOR ", ") as RoomNumber'),
                // Lấy loại phòng
                DB::raw('GROUP_CONCAT(DISTINCT ROOM_TYPES.TypeName ORDER BY ROOM_TYPES.TypeName SEPARATOR ", ") as TypeName'),
                // Tính tổng tiền từ tất cả các phòng
                DB::raw('COALESCE(SUM(BOOKING_ROOMS.TotalAmount), 0) as TotalAmount'),
                // Lấy check-in và check-out date
                DB::raw('MIN(BOOKING_ROOMS.CheckInDate) as CheckInDate'),
                DB::raw('MAX(BOOKING_ROOMS.CheckOutDate) as CheckOutDate'),
                // Lấy số giường
                DB::raw('COALESCE(SUM(ROOMS.Single_Bed), 0) as Single_Bed'),
                DB::raw('COALESCE(SUM(ROOMS.Double_Bed), 0) as Double_Bed'),
                // Trạng thái phòng
                DB::raw('GROUP_CONCAT(DISTINCT ROOMS.Status ORDER BY ROOMS.Status SEPARATOR ", ") as RoomStatus')
            )
            ->groupBy(
                'BOOKINGS.BookingID',
                'BOOKINGS.CustomerID',
                'BOOKINGS.StaffID',
                'BOOKINGS.AdultAmount',
                'BOOKINGS.ChildAmount',
                'BOOKINGS.BookingDate',
                'BOOKINGS.Status',
                'CUSTOMERS.FullName',
                'CUSTOMERS.Gender',
                'CUSTOMERS.Phone',
                'CUSTOMERS.Email',
                'CUSTOMERS.IDNumber',
                'CUSTOMERS.Address'
            )
            ->orderBy('BOOKINGS.BookingDate', 'desc')
            ->get();

        // Tính số đêm cho mỗi booking
        $bookings = $bookings->map(function($booking) {
            if ($booking->CheckInDate && $booking->CheckOutDate) {
                $checkIn = \Carbon\Carbon::parse($booking->CheckInDate);
                $checkOut = \Carbon\Carbon::parse($booking->CheckOutDate);
                $booking->NightAmount = $checkIn->diffInDays($checkOut);
            } else {
                $booking->NightAmount = 0;
            }
            return $booking;
        });

        return view('staff.staff-room', compact('roomStats', 'bookings'));
    }

    /**
     * Hiển thị trang quản lý đặt phòng (Multi-room support)
     */
    public function bookingManagement()
    {
        // Lấy danh sách booking với thông tin khách hàng và phòng (có thể nhiều phòng)
        $bookings = DB::table('BOOKINGS')
            ->join('CUSTOMERS', 'BOOKINGS.CustomerID', '=', 'CUSTOMERS.CustomerID')
            ->leftJoin('BOOKING_ROOMS', 'BOOKINGS.BookingID', '=', 'BOOKING_ROOMS.BookingID')
            ->leftJoin('ROOMS', 'BOOKING_ROOMS.RoomID', '=', 'ROOMS.RoomID')
            ->leftJoin('ROOM_TYPES', 'ROOMS.RoomTypeID', '=', 'ROOM_TYPES.RoomTypeID')
            ->select(
                'BOOKINGS.BookingID',
                'BOOKINGS.CustomerID',
                'BOOKINGS.StaffID',
                'BOOKINGS.AdultAmount',
                'BOOKINGS.ChildAmount',
                'BOOKINGS.BookingDate',
                'BOOKINGS.Status',
                'CUSTOMERS.FullName',
                'CUSTOMERS.Gender',
                'CUSTOMERS.Phone',
                'CUSTOMERS.Email',
                'CUSTOMERS.IDNumber',
                'CUSTOMERS.Address',
                // Lấy danh sách số phòng (ngăn cách bằng dấu phẩy)
                DB::raw('GROUP_CONCAT(DISTINCT ROOMS.RoomNumber ORDER BY ROOMS.RoomNumber SEPARATOR ", ") as RoomNumber'),
                // Lấy loại phòng (có thể nhiều loại)
                DB::raw('GROUP_CONCAT(DISTINCT ROOM_TYPES.TypeName ORDER BY ROOM_TYPES.TypeName SEPARATOR ", ") as TypeName'),
                // Tính tổng tiền từ tất cả các phòng
                DB::raw('COALESCE(SUM(BOOKING_ROOMS.TotalAmount), 0) as TotalAmount'),
                // Lấy check-in và check-out date (giả sử tất cả phòng cùng ngày)
                DB::raw('MIN(BOOKING_ROOMS.CheckInDate) as CheckInDate'),
                DB::raw('MAX(BOOKING_ROOMS.CheckOutDate) as CheckOutDate'),
                // Lấy số giường (tổng từ tất cả phòng)
                DB::raw('COALESCE(SUM(ROOMS.Single_Bed), 0) as Single_Bed'),
                DB::raw('COALESCE(SUM(ROOMS.Double_Bed), 0) as Double_Bed'),
                // Trạng thái phòng
                DB::raw('GROUP_CONCAT(DISTINCT ROOMS.Status ORDER BY ROOMS.Status SEPARATOR ", ") as RoomStatus')
            )
            ->groupBy(
                'BOOKINGS.BookingID',
                'BOOKINGS.CustomerID',
                'BOOKINGS.StaffID',
                'BOOKINGS.AdultAmount',
                'BOOKINGS.ChildAmount',
                'BOOKINGS.BookingDate',
                'BOOKINGS.Status',
                'CUSTOMERS.FullName',
                'CUSTOMERS.Gender',
                'CUSTOMERS.Phone',
                'CUSTOMERS.Email',
                'CUSTOMERS.IDNumber',
                'CUSTOMERS.Address'
            )
            ->orderBy('BOOKINGS.BookingDate', 'desc')
            ->get();

        // Tính số đêm cho mỗi booking
        $bookings = $bookings->map(function($booking) {
            if ($booking->CheckInDate && $booking->CheckOutDate) {
                $checkIn = \Carbon\Carbon::parse($booking->CheckInDate);
                $checkOut = \Carbon\Carbon::parse($booking->CheckOutDate);
                $booking->NightAmount = $checkIn->diffInDays($checkOut);
            } else {
                $booking->NightAmount = 0;
            }
            return $booking;
        });

        return view('staff.staff-booking', compact('bookings'));
    }

    /**
     * Cập nhật trạng thái booking
     */
    public function updateBookingStatus(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:Confirmed,Cancelled,Pending',
            ]);

            $booking = Booking::findOrFail($id);
            $booking->Status = $validated['status'];
            
            // Nếu xác nhận booking, cập nhật staff xử lý
            if ($validated['status'] === 'Confirmed') {
                $booking->StaffID = auth()->guard('staff')->id();
            }
            
            $booking->save();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái booking thành công!'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách phòng của một booking
     */
    public function getBookingRooms($bookingId)
    {
        try {
            $rooms = DB::table('BOOKING_ROOMS')
                ->join('ROOMS', 'BOOKING_ROOMS.RoomID', '=', 'ROOMS.RoomID')
                ->leftJoin('ROOM_TYPES', 'ROOMS.RoomTypeID', '=', 'ROOM_TYPES.RoomTypeID')
                ->where('BOOKING_ROOMS.BookingID', $bookingId)
                ->select(
                    'BOOKING_ROOMS.BookingRoomID',
                    'BOOKING_ROOMS.CheckInDate',
                    'BOOKING_ROOMS.CheckOutDate',
                    'BOOKING_ROOMS.TotalAmount',
                    'ROOMS.RoomNumber',
                    'ROOMS.Status as RoomStatus',
                    'ROOMS.Single_Bed',
                    'ROOMS.Double_Bed',
                    'ROOM_TYPES.TypeName'
                )
                ->get();

            return response()->json([
                'success' => true,
                'rooms' => $rooms
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

// Hiển thị danh sách phòng theo loại cho nhân viên
    public function getRoomsByType($type)
    {
        // Map từ tên sang RoomTypeID
        $typeMap = [
            'Standard' => 1,
            'Superior' => 2,
            'Deluxe'   => 3,
            'Villa'    => 4
        ];

        // Xử lý đặc biệt cho phòng Service (E-...)
        if ($type === 'Service') {
            $rooms = DB::table('ROOMS')
                ->where('RoomNumber', 'LIKE', 'E-%')
                ->select('RoomID', 'RoomNumber', 'Status', 'Single_Bed', 'Double_Bed')
                ->orderBy('RoomNumber')
                ->get();
            
            return response()->json($rooms);
        }

        if (!array_key_exists($type, $typeMap)) {
            return response()->json(['error' => 'Loại phòng không hợp lệ'], 400);
        }

        $rooms = DB::table('ROOMS')
            ->where('RoomTypeID', $typeMap[$type])
            ->select('RoomID', 'RoomNumber', 'Status', 'Single_Bed', 'Double_Bed')
            ->orderBy('RoomNumber')
            ->get();

        return response()->json($rooms);
    }

    /**
     * Hiển thị trang quản lý nhân viên
     */
    public function employeeManagement()
    {
        $staffs = Staff::all();
        return view('staff.staff-employee', compact('staffs'));
    }

    /**
     * Lấy thông tin một nhân viên
     */
    public function getEmployee($id)
    {
        $staff = Staff::findOrFail($id);
        return response()->json($staff);
    }

    /**
     * Thêm nhân viên mới
     */
    public function storeEmployee(Request $request)
    {
        try {
            $validated = $request->validate([
                'fullName' => 'required|string|max:255',
                'role' => 'required|string|max:100',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|unique:STAFFS,Email',
                'password' => 'required|string|min:6',
                'cccd' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
            ]);

            $staff = new Staff();
            $staff->FullName = $validated['fullName'];
            $staff->Role = $validated['role'];
            $staff->Phone = $validated['phone'];
            $staff->Email = $validated['email'];
            $staff->password = $validated['password']; // Plain text password
            $staff->CCCD = $validated['cccd'] ?? null;
            $staff->Address = $validated['address'] ?? null;
            $staff->save();

            return response()->json([
                'success' => true,
                'message' => 'Thêm nhân viên thành công!',
                'staff' => $staff
            ]);
        } catch (\Exception $e) {
            Log::error('Store employee error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật thông tin nhân viên
     */
    public function updateEmployee(Request $request, $id)
    {
        try {
            $staff = Staff::findOrFail($id);

            $validated = $request->validate([
                'fullName' => 'required|string|max:255',
                'role' => 'required|string|max:100',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|unique:STAFFS,Email,' . $id . ',StaffID',
                'password' => 'nullable|string|min:6',
                'cccd' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
            ]);

            $staff->FullName = $validated['fullName'];
            $staff->Role = $validated['role'];
            $staff->Phone = $validated['phone'];
            $staff->Email = $validated['email'];
            
            // Only update password if provided
            if (!empty($validated['password'])) {
                $staff->password = $validated['password']; // Plain text password
            }
            
            $staff->CCCD = $validated['cccd'] ?? null;
            $staff->Address = $validated['address'] ?? null;
            $staff->save();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin nhân viên thành công!',
                'staff' => $staff
            ]);
        } catch (\Exception $e) {
            Log::error('Update employee error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa nhân viên
     */
    public function deleteEmployee($id)
    {
        try {
            $staff = Staff::findOrFail($id);
            $staff->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa nhân viên thành công!'
            ]);
        } catch (\Exception $e) {
            Log::error('Delete employee error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hiển thị trang quản lý dịch vụ
     */
    public function serviceManagement()
    {
        $services = Service::all();
        
        // Cập nhật status của tất cả phòng E-... đã được gán trong BOOKING_ROOMS thành 'Booked'
        DB::statement("
            UPDATE ROOMS 
            SET Status = 'Booked' 
            WHERE RoomNumber LIKE 'E-%' 
            AND RoomID IN (
                SELECT DISTINCT RoomID 
                FROM BOOKING_ROOMS
            )
        ");
        
        // Lấy danh sách booking đang active để chọn khi thêm service usage
        // Sử dụng LEFT JOIN với BOOKING_ROOMS để lấy thông tin phòng
        $bookings = DB::table('BOOKINGS')
            ->leftJoin('CUSTOMERS', 'BOOKINGS.CustomerID', '=', 'CUSTOMERS.CustomerID')
            ->leftJoin('BOOKING_ROOMS', 'BOOKINGS.BookingID', '=', 'BOOKING_ROOMS.BookingID')
            ->leftJoin('ROOMS', 'BOOKING_ROOMS.RoomID', '=', 'ROOMS.RoomID')
            ->select(
                'BOOKINGS.BookingID',
                'BOOKINGS.Status',
                'CUSTOMERS.FullName as CustomerName',
                DB::raw('GROUP_CONCAT(DISTINCT ROOMS.RoomNumber ORDER BY ROOMS.RoomNumber SEPARATOR ", ") as RoomNumbers'),
                DB::raw('MIN(BOOKING_ROOMS.CheckInDate) as CheckInDate'),
                DB::raw('MAX(BOOKING_ROOMS.CheckOutDate) as CheckOutDate')
            )
            ->where('BOOKINGS.Status', '!=', 'Cancelled')
            ->groupBy('BOOKINGS.BookingID', 'BOOKINGS.Status', 'CUSTOMERS.FullName')
            ->orderBy('BOOKINGS.BookingID', 'desc')
            ->get();
        
        // Lấy lịch sử sử dụng dịch vụ với join bao gồm thông tin phòng
        $serviceUsages = DB::table('SERVICE_USAGES')
            ->join('BOOKINGS', 'SERVICE_USAGES.BookingID', '=', 'BOOKINGS.BookingID')
            ->join('SERVICES', 'SERVICE_USAGES.ServiceID', '=', 'SERVICES.ServiceID')
            ->leftJoin('CUSTOMERS', 'BOOKINGS.CustomerID', '=', 'CUSTOMERS.CustomerID')
            ->leftJoin('BOOKING_ROOMS', 'BOOKINGS.BookingID', '=', 'BOOKING_ROOMS.BookingID')
            ->leftJoin('ROOMS', 'BOOKING_ROOMS.RoomID', '=', 'ROOMS.RoomID')
            ->select(
                'SERVICE_USAGES.UsageID',
                'SERVICE_USAGES.BookingID',
                'SERVICE_USAGES.ServiceID',
                'SERVICE_USAGES.Quantity',
                'SERVICE_USAGES.TotalPrice',
                'CUSTOMERS.FullName as CustomerName',
                'SERVICES.ServiceName',
                'SERVICES.Price as ServicePrice',
                DB::raw('GROUP_CONCAT(DISTINCT ROOMS.RoomNumber ORDER BY ROOMS.RoomNumber SEPARATOR ", ") as RoomNumbers')
            )
            ->groupBy(
                'SERVICE_USAGES.UsageID',
                'SERVICE_USAGES.BookingID',
                'SERVICE_USAGES.ServiceID',
                'SERVICE_USAGES.Quantity',
                'SERVICE_USAGES.TotalPrice',
                'CUSTOMERS.FullName',
                'SERVICES.ServiceName',
                'SERVICES.Price'
            )
            ->orderBy('SERVICE_USAGES.UsageID', 'desc')
            ->get();
        
        // Lấy danh sách phòng E (dịch vụ) để chọn - chỉ hiển thị phòng Available
        $serviceRooms = DB::table('ROOMS')
            ->where('RoomNumber', 'LIKE', 'E-%')
            ->where('Status', '=', 'Available')
            ->orderBy('RoomNumber')
            ->get();
        
        return view('staff.staff-service', compact('services', 'bookings', 'serviceUsages', 'serviceRooms'));
    }

    /**
     * Lấy thông tin một dịch vụ
     */
    public function getService($id)
    {
        $service = Service::findOrFail($id);
        return response()->json($service);
    }

    /**
     * Thêm dịch vụ mới
     */
    public function storeService(Request $request)
    {
        try {
            $validated = $request->validate([
                'serviceName' => 'required|string|max:100',
                'description' => 'nullable|string|max:255',
                'price' => 'required|numeric|min:0',
            ]);

            $service = new Service();
            $service->ServiceName = $validated['serviceName'];
            $service->Description = $validated['description'] ?? null;
            $service->Price = $validated['price'];
            $service->save();

            return response()->json([
                'success' => true,
                'message' => 'Thêm dịch vụ thành công!',
                'service' => $service
            ]);
        } catch (\Exception $e) {
            Log::error('Store service error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật thông tin dịch vụ
     */
    public function updateService(Request $request, $id)
    {
        try {
            $service = Service::findOrFail($id);

            $validated = $request->validate([
                'serviceName' => 'required|string|max:100',
                'description' => 'nullable|string|max:255',
                'price' => 'required|numeric|min:0',
            ]);

            $service->ServiceName = $validated['serviceName'];
            $service->Description = $validated['description'] ?? null;
            $service->Price = $validated['price'];
            $service->save();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật dịch vụ thành công!',
                'service' => $service
            ]);
        } catch (\Exception $e) {
            Log::error('Update service error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa dịch vụ
     */
    public function deleteService($id)
    {
        try {
            $service = Service::findOrFail($id);
            $service->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa dịch vụ thành công!'
            ]);
        } catch (\Exception $e) {
            Log::error('Delete service error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy thông tin một service usage
     */
    public function getServiceUsage($id)
    {
        $usage = DB::table('SERVICE_USAGES')
            ->join('BOOKINGS', 'SERVICE_USAGES.BookingID', '=', 'BOOKINGS.BookingID')
            ->join('SERVICES', 'SERVICE_USAGES.ServiceID', '=', 'SERVICES.ServiceID')
            ->leftJoin('CUSTOMERS', 'BOOKINGS.CustomerID', '=', 'CUSTOMERS.CustomerID')
            ->select(
                'SERVICE_USAGES.UsageID',
                'SERVICE_USAGES.BookingID',
                'SERVICE_USAGES.ServiceID',
                'SERVICE_USAGES.Quantity',
                'SERVICE_USAGES.TotalPrice',
                'CUSTOMERS.FullName as CustomerName',
                'SERVICES.ServiceName',
                'SERVICES.Price as ServicePrice'
            )
            ->where('SERVICE_USAGES.UsageID', $id)
            ->first();
            
        if (!$usage) {
            return response()->json(['error' => 'Service usage not found'], 404);
        }
        
        return response()->json($usage);
    }

    /**
     * Thêm service usage mới
     */
    public function storeServiceUsage(Request $request)
    {
        try {
            $validated = $request->validate([
                'bookingId' => 'required|exists:BOOKINGS,BookingID',
                'serviceId' => 'required|exists:SERVICES,ServiceID',
                'quantity' => 'required|integer|min:1',
            ]);

            $service = Service::findOrFail($validated['serviceId']);
            $totalPrice = $service->Price * $validated['quantity'];

            $usage = new ServiceUsage();
            $usage->BookingID = $validated['bookingId'];
            $usage->ServiceID = $validated['serviceId'];
            $usage->Quantity = $validated['quantity'];
            $usage->TotalPrice = $totalPrice;
            $usage->save();

            return response()->json([
                'success' => true,
                'message' => 'Thêm lượt sử dụng dịch vụ thành công!',
                'usage' => $usage->load(['booking.customer', 'service'])
            ]);
        } catch (\Exception $e) {
            Log::error('Store service usage error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật service usage
     */
    public function updateServiceUsage(Request $request, $id)
    {
        try {
            $usage = ServiceUsage::findOrFail($id);

            $validated = $request->validate([
                'bookingId' => 'required|exists:BOOKINGS,BookingID',
                'serviceId' => 'required|exists:SERVICES,ServiceID',
                'quantity' => 'required|integer|min:1',
            ]);

            $service = Service::findOrFail($validated['serviceId']);
            $totalPrice = $service->Price * $validated['quantity'];

            $usage->BookingID = $validated['bookingId'];
            $usage->ServiceID = $validated['serviceId'];
            $usage->Quantity = $validated['quantity'];
            $usage->TotalPrice = $totalPrice;
            $usage->save();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật lượt sử dụng dịch vụ thành công!',
                'usage' => $usage->load(['booking.customer', 'service'])
            ]);
        } catch (\Exception $e) {
            Log::error('Update service usage error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa service usage
     */
    public function deleteServiceUsage($id)
    {
        try {
            $usage = ServiceUsage::findOrFail($id);
            $usage->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa lượt sử dụng dịch vụ thành công!'
            ]);
        } catch (\Exception $e) {
            Log::error('Delete service usage error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật phòng cho service usage
     */
    public function assignRoomToServiceUsage(Request $request, $id)
    {
        try {
            $usage = ServiceUsage::findOrFail($id);

            $validated = $request->validate([
                'roomId' => 'required|exists:ROOMS,RoomID',
            ]);

            // Lấy thông tin phòng để kiểm tra
            $room = DB::table('ROOMS')->where('RoomID', $validated['roomId'])->first();
            
            if (!$room || !str_starts_with($room->RoomNumber, 'E-')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Chỉ được chọn phòng dịch vụ (E-...)'
                ], 400);
            }

            // Kiểm tra xem đã có BOOKING_ROOM chưa
            $bookingRoom = DB::table('BOOKING_ROOMS')
                ->where('BookingID', $usage->BookingID)
                ->where('RoomID', $validated['roomId'])
                ->first();

            if (!$bookingRoom) {
                // Tạo mới BOOKING_ROOM
                DB::table('BOOKING_ROOMS')->insert([
                    'BookingID' => $usage->BookingID,
                    'RoomID' => $validated['roomId'],
                    'CheckInDate' => now(),
                    'CheckOutDate' => now()->addDay(),
                    'TotalAmount' => 0 // Phòng dịch vụ không tính tiền phòng
                ]);
                
                // Cập nhật trạng thái phòng thành 'Booked'
                DB::table('ROOMS')
                    ->where('RoomID', $validated['roomId'])
                    ->update(['Status' => 'Booked']);
            }

            return response()->json([
                'success' => true,
                'message' => 'Gán phòng thành công!',
                'roomNumber' => $room->RoomNumber
            ]);
        } catch (\Exception $e) {
            Log::error('Assign room to service usage error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hiển thị trang profile của staff đang đăng nhập
     */
    public function showProfile()
    {
        $staff = Auth::guard('staff')->user();
        return view('staff.staff-profile', compact('staff'));
    }

    /**
     * Cập nhật thông tin profile của staff
     */
    public function updateProfile(Request $request)
    {
        try {
            $staff = Auth::guard('staff')->user();

            $validated = $request->validate([
                'fullName' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|unique:STAFFS,Email,' . $staff->StaffID . ',StaffID',
                'cccd' => 'nullable|string|max:20',
                'address' => 'nullable|string|max:500',
            ]);

            $staff->FullName = $validated['fullName'];
            $staff->Phone = $validated['phone'];
            $staff->Email = $validated['email'];
            $staff->CCCD = $validated['cccd'] ?? null;
            $staff->Address = $validated['address'] ?? null;
            $staff->save();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin thành công!',
                'staff' => $staff
            ]);
        } catch (\Exception $e) {
            Log::error('Update profile error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Đổi mật khẩu của staff
     */
    public function changePassword(Request $request)
    {
        try {
            $staff = Auth::guard('staff')->user();

            $validated = $request->validate([
                'currentPassword' => 'required|string',
                'newPassword' => 'required|string|min:6',
                'confirmPassword' => 'required|string|same:newPassword',
            ]);

            $currentInput = $validated['currentPassword'];
            $stored = $staff->password;
            $isHashed = is_string($stored) && preg_match('/^\$2y\$\d{2}\$/', $stored);

            $match = $isHashed ? Hash::check($currentInput, $stored) : hash_equals($stored, $currentInput);
            if (!$match) {
                return response()->json([
                    'success' => false,
                    'message' => 'Mật khẩu hiện tại không chính xác!'
                ], 400);
            }

            // Cập nhật mật khẩu mới (sẽ được hash bởi mutator)
            $staff->password = $validated['newPassword'];
            $staff->save();

            return response()->json([
                'success' => true,
                'message' => 'Đổi mật khẩu thành công!'
            ]);
        } catch (\Exception $e) {
            Log::error('Change password error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hiển thị trang quản lý hóa đơn
     */
    public function invoiceManagement()
    {
        // Lấy danh sách hóa đơn với tổng tiền phòng + dịch vụ tính theo BookingID
        $payments = DB::table('PAYMENTS')
            ->leftJoin('BOOKINGS', 'PAYMENTS.BookingID', '=', 'BOOKINGS.BookingID')
            ->leftJoin('CUSTOMERS', 'BOOKINGS.CustomerID', '=', 'CUSTOMERS.CustomerID')
            ->leftJoin(DB::raw('(SELECT BookingID, GROUP_CONCAT(DISTINCT RoomNumber ORDER BY RoomNumber SEPARATOR ", ") as RoomNumbers, MIN(CheckInDate) as CheckInDate, MAX(CheckOutDate) as CheckOutDate FROM BOOKING_ROOMS LEFT JOIN ROOMS ON BOOKING_ROOMS.RoomID = ROOMS.RoomID GROUP BY BookingID) as BR'), 'BOOKINGS.BookingID', '=', 'BR.BookingID')
            ->leftJoin(DB::raw('(SELECT BookingID, SUM(TotalAmount) as RoomTotal FROM BOOKING_ROOMS GROUP BY BookingID) as RT'), 'BOOKINGS.BookingID', '=', 'RT.BookingID')
            ->leftJoin(DB::raw('(SELECT BookingID, SUM(TotalPrice) as ServiceTotal FROM SERVICE_USAGES GROUP BY BookingID) as SU'), 'BOOKINGS.BookingID', '=', 'SU.BookingID')
            ->select(
                'PAYMENTS.PaymentID',
                'PAYMENTS.BookingID',
                'PAYMENTS.PaymentDate',
                'PAYMENTS.PaymentMethod',
                'PAYMENTS.PaymentStatus',
                'CUSTOMERS.FullName as CustomerName',
                DB::raw('BR.RoomNumbers'),
                DB::raw('BR.CheckInDate'),
                DB::raw('BR.CheckOutDate'),
                DB::raw('COALESCE(RT.RoomTotal, 0) + COALESCE(SU.ServiceTotal, 0) as Amount')
            )
            ->orderBy('PAYMENTS.PaymentID', 'desc')
            ->get();
        
        // Lấy danh sách booking với tổng tiền phòng + dịch vụ sử dụng subquery
        $bookings = DB::table('BOOKINGS')
            ->leftJoin('CUSTOMERS', 'BOOKINGS.CustomerID', '=', 'CUSTOMERS.CustomerID')
            ->leftJoin(DB::raw('(SELECT BookingID, GROUP_CONCAT(DISTINCT RoomNumber ORDER BY RoomNumber SEPARATOR ", ") as RoomNumbers, SUM(TotalAmount) as RoomTotal FROM BOOKING_ROOMS LEFT JOIN ROOMS ON BOOKING_ROOMS.RoomID = ROOMS.RoomID GROUP BY BookingID) as BR'), 'BOOKINGS.BookingID', '=', 'BR.BookingID')
            ->leftJoin(DB::raw('(SELECT BookingID, SUM(TotalPrice) as ServiceTotal FROM SERVICE_USAGES GROUP BY BookingID) as SU'), 'BOOKINGS.BookingID', '=', 'SU.BookingID')
            ->select(
                'BOOKINGS.BookingID',
                'CUSTOMERS.FullName as CustomerName',
                DB::raw('BR.RoomNumbers'),
                DB::raw('COALESCE(BR.RoomTotal, 0) as RoomTotal'),
                DB::raw('COALESCE(SU.ServiceTotal, 0) as ServiceTotal'),
                DB::raw('COALESCE(BR.RoomTotal, 0) + COALESCE(SU.ServiceTotal, 0) as TotalAmount')
            )
            ->where('BOOKINGS.Status', '!=', 'Cancelled')
            ->get();
        
        return view('staff.staff-invoice', compact('payments', 'bookings'));
    }

    /**
     * Lấy thông tin một hóa đơn
     */
    public function getPayment($id)
    {
        $payment = DB::table('PAYMENTS')
            ->leftJoin('BOOKINGS', 'PAYMENTS.BookingID', '=', 'BOOKINGS.BookingID')
            ->leftJoin('CUSTOMERS', 'BOOKINGS.CustomerID', '=', 'CUSTOMERS.CustomerID')
            ->leftJoin(DB::raw('(SELECT BookingID, GROUP_CONCAT(DISTINCT RoomNumber ORDER BY RoomNumber SEPARATOR ", ") as RoomNumbers FROM BOOKING_ROOMS LEFT JOIN ROOMS ON BOOKING_ROOMS.RoomID = ROOMS.RoomID GROUP BY BookingID) as BR'), 'BOOKINGS.BookingID', '=', 'BR.BookingID')
            ->leftJoin(DB::raw('(SELECT BookingID, SUM(TotalAmount) as RoomTotal FROM BOOKING_ROOMS GROUP BY BookingID) as RT'), 'BOOKINGS.BookingID', '=', 'RT.BookingID')
            ->leftJoin(DB::raw('(SELECT BookingID, SUM(TotalPrice) as ServiceTotal FROM SERVICE_USAGES GROUP BY BookingID) as SU'), 'BOOKINGS.BookingID', '=', 'SU.BookingID')
            ->select(
                'PAYMENTS.PaymentID',
                'PAYMENTS.BookingID',
                'PAYMENTS.PaymentDate',
                'PAYMENTS.Amount',
                'PAYMENTS.PaymentMethod',
                'PAYMENTS.PaymentStatus',
                'CUSTOMERS.FullName as CustomerName',
                DB::raw('BR.RoomNumbers'),
                DB::raw('COALESCE(RT.RoomTotal, 0) as RoomTotal'),
                DB::raw('COALESCE(SU.ServiceTotal, 0) as ServiceTotal')
            )
            ->where('PAYMENTS.PaymentID', $id)
            ->first();
        
        if (!$payment) {
            return response()->json(['error' => 'Payment not found'], 404);
        }
        
        return response()->json($payment);
    }

    /**
     * Thêm hóa đơn mới
     */
    public function storePayment(Request $request)
    {
        try {
            $validated = $request->validate([
                'bookingId' => 'required|exists:BOOKINGS,BookingID',
                'paymentDate' => 'required|date',
                'amount' => 'required|numeric|min:0',
                'paymentMethod' => 'required|string|max:30',
                'paymentStatus' => 'required|string|max:20',
            ]);

            $payment = new Payment();
            $payment->BookingID = $validated['bookingId'];
            $payment->PaymentDate = $validated['paymentDate'];
            $payment->Amount = $validated['amount'];
            $payment->PaymentMethod = $validated['paymentMethod'];
            $payment->PaymentStatus = $validated['paymentStatus'];
            $payment->save();

            return response()->json([
                'success' => true,
                'message' => 'Thêm hóa đơn thành công!',
                'payment' => $payment->load(['booking.customer'])
            ]);
        } catch (\Exception $e) {
            Log::error('Store payment error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật hóa đơn
     */
    public function updatePayment(Request $request, $id)
    {
        try {
            $payment = Payment::findOrFail($id);

            $validated = $request->validate([
                'bookingId' => 'required|exists:BOOKINGS,BookingID',
                'paymentDate' => 'required|date',
                'amount' => 'required|numeric|min:0',
                'paymentMethod' => 'required|string|max:30',
                'paymentStatus' => 'required|string|max:20',
            ]);

            $payment->BookingID = $validated['bookingId'];
            $payment->PaymentDate = $validated['paymentDate'];
            $payment->Amount = $validated['amount'];
            $payment->PaymentMethod = $validated['paymentMethod'];
            $payment->PaymentStatus = $validated['paymentStatus'];
            $payment->save();

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật hóa đơn thành công!',
                'payment' => $payment->load(['booking.customer'])
            ]);
        } catch (\Exception $e) {
            Log::error('Update payment error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa hóa đơn
     */
    public function deletePayment($id)
    {
        try {
            $payment = Payment::findOrFail($id);
            $payment->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa hóa đơn thành công!'
            ]);
        } catch (\Exception $e) {
            Log::error('Delete payment error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hiển thị trang quản lý khách hàng
     */
    public function customerManagement()
    {
        try {
            $customers = Customer::orderBy('CustomerID', 'desc')->get();
            return view('staff.staff-customer', compact('customers'));
        } catch (\Exception $e) {
            Log::error('Customer management error:', ['message' => $e->getMessage()]);
            return back()->with('error', 'Có lỗi xảy ra khi tải danh sách khách hàng');
        }
    }

    /**
     * Lấy thông tin một khách hàng
     */
    public function getCustomer($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            return response()->json([
                'success' => true,
                'customer' => $customer
            ]);
        } catch (\Exception $e) {
            Log::error('Get customer error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy khách hàng'
            ], 404);
        }
    }

    /**
     * Thêm khách hàng mới
     */
    public function storeCustomer(Request $request)
    {
        try {
            $validated = $request->validate([
                'FullName' => 'required|string|max:100',
                'Gender' => 'required|string|max:10',
                'Phone' => 'required|string|max:20',
                'Email' => 'required|email|max:100|unique:CUSTOMERS,Email',
                'IDNumber' => 'required|string|max:20|unique:CUSTOMERS,IDNumber',
                'Address' => 'required|string|max:255',
                'Password' => 'required|string|min:6',
            ]);

            $customer = Customer::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Thêm khách hàng thành công!',
                'customer' => $customer
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Store customer error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật thông tin khách hàng
     */
    public function updateCustomer(Request $request, $id)
    {
        try {
            $customer = Customer::findOrFail($id);

            $validated = $request->validate([
                'FullName' => 'required|string|max:100',
                'Gender' => 'required|string|max:10',
                'Phone' => 'required|string|max:20',
                'Email' => 'required|email|max:100|unique:CUSTOMERS,Email,' . $id . ',CustomerID',
                'IDNumber' => 'required|string|max:20|unique:CUSTOMERS,IDNumber,' . $id . ',CustomerID',
                'Address' => 'required|string|max:255',
                'Password' => 'nullable|string|min:6',
            ]);

            // Only update password if provided
            if (empty($validated['Password'])) {
                unset($validated['Password']);
            }

            $customer->update($validated);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật khách hàng thành công!',
                'customer' => $customer
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Update customer error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xóa khách hàng
     */
    public function deleteCustomer($id)
    {
        try {
            $customer = Customer::findOrFail($id);
            
            // Check if customer has bookings
            // if ($customer->bookings()->count() > 0) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Không thể xóa khách hàng đã có đặt phòng'
            //     ], 400);
            // }

            $customer->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa khách hàng thành công!'
            ]);
        } catch (\Exception $e) {
            Log::error('Delete customer error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Hiển thị trang sơ đồ phòng
     */
    public function showMap()
    {
        // Lấy tất cả phòng từ database với thông tin loại phòng
        $rooms = DB::table('ROOMS')
            ->leftJoin('ROOM_TYPES', 'ROOMS.RoomTypeID', '=', 'ROOM_TYPES.RoomTypeID')
            ->select(
                'ROOMS.RoomID',
                'ROOMS.RoomNumber',
                'ROOMS.Status',
                'ROOM_TYPES.TypeName as RoomType'
            )
            ->orderBy('ROOMS.RoomNumber')
            ->get();
        
        // Nhóm phòng theo loại và tầng
        $roomsData = [];
        foreach ($rooms as $room) {
            $roomsData[$room->RoomNumber] = [
                'id' => $room->RoomID,
                'number' => $room->RoomNumber,
                'type' => $room->RoomType ?? 'Standard',
                'status' => $room->Status ?? 'Available'
            ];
        }
        
        return view('staff.map', compact('roomsData'));
    }
}


<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use App\Models\ServiceUsage;
use App\Models\Customer;
use App\Models\Service;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ServiceController extends Controller
{
    /**
     * Đặt dịch vụ
     */
    public function bookService(Request $request)
    {
        try {
            // Validate dữ liệu
            $validated = $request->validate([
                'fullname' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'service_id' => 'required|integer|exists:SERVICES,ServiceID',
                'date' => 'required|date',
                'time' => 'required',
                'quantity' => 'required|integer|min:1',
                'note' => 'nullable|string|max:255'
            ]);

            DB::beginTransaction();

            // 1. Tìm hoặc tạo Customer dựa trên tên và SĐT
            $customer = Customer::where('Phone', $validated['phone'])
                ->where('FullName', $validated['fullname'])
                ->first();

            if (!$customer) {
                // Tạo customer mới nếu chưa có
                $customer = Customer::create([
                    'FullName' => $validated['fullname'],
                    'Phone' => $validated['phone'],
                    'Password' => bcrypt('123456'), // Mật khẩu mặc định
                ]);
            }

            // 2. Tạo Booking mới
            $booking = Booking::create([
                'CustomerID' => $customer->CustomerID,
                'StaffID' => null, // Chưa có staff xử lý
                'BookingDate' => now(),
                'Status' => 'Pending', // Đang chờ xác nhận
            ]);

            // 3. Lấy thông tin service để tính tổng tiền
            $service = Service::findOrFail($validated['service_id']);
            $totalPrice = $service->Price * $validated['quantity'];

            // 4. Tạo bản ghi SERVICE_USAGE
            $startDateTime = $validated['date'] . ' ' . $validated['time'];
            
            ServiceUsage::create([
                'BookingID' => $booking->BookingID,
                'ServiceID' => $validated['service_id'],
                'Quantity' => $validated['quantity'],
                'TotalPrice' => $totalPrice,
                'StartTime' => $startDateTime,
                'GhiChuThem' => $validated['note']
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Đặt dịch vụ thành công!',
                'booking_id' => $booking->BookingID,
                'customer_name' => $customer->FullName,
                'total_price' => number_format($totalPrice, 0, ',', '.') . 'đ'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Lỗi khi đặt dịch vụ: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đặt dịch vụ. Vui lòng thử lại!'
            ], 500);
        }
    }
}

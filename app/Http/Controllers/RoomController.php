<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoomController extends Controller
{
    public function index()
    {
        return view('staff');
    }

    public function getRoomsByType($type)
    {
        $rooms = Room::where('RoomTypeID', function($query) use ($type) {
            $query->select('RoomTypeID')
                  ->from('ROOM_TYPE')
                  ->where('TypeName', $type);
        })->get();

        // Chuyển đổi dữ liệu để phù hợp với format JavaScript mong đợi
        $formattedRooms = $rooms->map(function($room) use ($type) {
            return [
                'roomId' => $room->RoomID,
                'roomNumber' => $room->RoomNumber,
                'roomType' => $type,
                'status' => $room->Status ?? 'Available',
                'singleBeds' => $room->Single_Bed ?? 0,
                'doubleBeds' => $room->Double_Bed ?? 0,
                'guestName' => null, // Sẽ lấy từ bảng BOOKINGS nếu có
                'checkInDate' => null, // Sẽ lấy từ bảng BOOKINGS nếu có
                'checkOutDate' => null // Sẽ lấy từ bảng BOOKINGS nếu có
            ];
        });

        return response()->json($formattedRooms);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'roomId' => 'required|integer',
            'status' => 'required|string',
            'singleBeds' => 'nullable|integer|min:0|max:5',
            'doubleBeds' => 'nullable|integer|min:0|max:5',
        ]);

        try {
            $room = Room::findOrFail($validated['roomId']);
            
            // Map status từ frontend sang database
            $statusMap = [
                'available' => 'Available',
                'booking' => 'Busy',
                'maintenance' => 'Maintenance',
                'cleaning' => 'Cleaning'
            ];
            
            $dbStatus = $statusMap[$validated['status']] ?? $validated['status'];
            
            // Chỉ cập nhật status, không yêu cầu beds
            $updateData = ['Status' => $dbStatus];
            
            // Nếu có thông tin beds thì cập nhật
            if (isset($validated['singleBeds'])) {
                $updateData['Single_Bed'] = $validated['singleBeds'];
            }
            if (isset($validated['doubleBeds'])) {
                $updateData['Double_Bed'] = $validated['doubleBeds'];
            }
            
            $room->update($updateData);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật thông tin phòng thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy tất cả phòng theo loại cho booking
     */
    public function getAllRoomsByType()
    {
        try {
            // Lấy tất cả room types và rooms
            $roomTypes = DB::table('ROOM_TYPES')
                ->select('RoomTypeID', 'TypeName')
                ->orderBy('RoomTypeID')
                ->get();

            $result = [];

            foreach ($roomTypes as $type) {
                $rooms = DB::table('ROOMS')
                    ->where('RoomTypeID', $type->RoomTypeID)
                    ->select('RoomID', 'RoomNumber', 'Status', 'Single_Bed', 'Double_Bed')
                    ->orderBy('RoomNumber')
                    ->get();

                $result[] = [
                    'typeName' => $type->TypeName,
                    'typeId' => $type->RoomTypeID,
                    'rooms' => $rooms->map(function($room) {
                        return [
                            'roomId' => $room->RoomID,
                            'roomNumber' => $room->RoomNumber,
                            'status' => $room->Status,
                            'singleBeds' => $room->Single_Bed ?? 0,
                            'doubleBeds' => $room->Double_Bed ?? 0,
                        ];
                    })
                ];
            }

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra: ' . $e->getMessage()
            ], 500);
        }
    }
}
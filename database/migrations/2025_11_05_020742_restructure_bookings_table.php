<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Bước 1: Tạo bảng BOOKING_ROOMS trước
        Schema::create('BOOKING_ROOMS', function (Blueprint $table) {
            $table->id('BookingRoomID');
            $table->unsignedBigInteger('BookingID');
            $table->unsignedBigInteger('RoomID');
            $table->date('CheckInDate');
            $table->date('CheckOutDate');
            $table->decimal('TotalAmount', 10, 2);
            
            $table->foreign('BookingID')->references('BookingID')->on('BOOKINGS')->onDelete('cascade');
            $table->foreign('RoomID')->references('RoomID')->on('ROOMS');
        });

        // Bước 2: Migrate dữ liệu hiện tại từ BOOKINGS sang BOOKING_ROOMS
        DB::statement('
            INSERT INTO BOOKING_ROOMS (BookingID, RoomID, CheckInDate, CheckOutDate, TotalAmount)
            SELECT BookingID, RoomID, CheckInDate, CheckOutDate, TotalAmount 
            FROM BOOKINGS 
            WHERE RoomID IS NOT NULL
        ');

        // Bước 3: Xóa các cột không cần thiết khỏi BOOKINGS
        Schema::table('BOOKINGS', function (Blueprint $table) {
            $table->dropForeign(['RoomID']);
            $table->dropColumn(['RoomID', 'CheckInDate', 'CheckOutDate', 'NightAmount', 'TotalAmount']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Khôi phục lại cấu trúc cũ
        Schema::table('BOOKINGS', function (Blueprint $table) {
            $table->unsignedBigInteger('RoomID')->nullable()->after('StaffID');
            $table->date('CheckInDate')->after('RoomID');
            $table->date('CheckOutDate')->after('CheckInDate');
            $table->integer('NightAmount')->after('CheckOutDate');
            $table->decimal('TotalAmount', 10, 2)->after('Status');
            
            $table->foreign('RoomID')->references('RoomID')->on('ROOMS');
        });

        // Migrate dữ liệu về (chỉ lấy booking room đầu tiên nếu có nhiều)
        DB::statement('
            UPDATE BOOKINGS b
            INNER JOIN (
                SELECT BookingID, MIN(BookingRoomID) as FirstRoomID
                FROM BOOKING_ROOMS
                GROUP BY BookingID
            ) first ON b.BookingID = first.BookingID
            INNER JOIN BOOKING_ROOMS br ON br.BookingRoomID = first.FirstRoomID
            SET 
                b.RoomID = br.RoomID,
                b.CheckInDate = br.CheckInDate,
                b.CheckOutDate = br.CheckOutDate,
                b.TotalAmount = br.TotalAmount,
                b.NightAmount = DATEDIFF(br.CheckOutDate, br.CheckInDate)
        ');

        // Xóa bảng BOOKING_ROOMS
        Schema::dropIfExists('BOOKING_ROOMS');
    }
};

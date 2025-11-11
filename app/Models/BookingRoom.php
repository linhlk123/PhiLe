<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingRoom extends Model
{
    protected $table = 'BOOKING_ROOMS';
    protected $primaryKey = 'BookingRoomID';
    public $timestamps = false;

    protected $fillable = [
        'BookingID',
        'RoomID',
        'CheckInDate',
        'CheckOutDate',
        'TotalAmount'
    ];

    protected $casts = [
        'CheckInDate' => 'date',
        'CheckOutDate' => 'date',
        'TotalAmount' => 'decimal:2'
    ];

    // Quan hệ với Booking
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'BookingID', 'BookingID');
    }

    // Quan hệ với Room
    public function room()
    {
        return $this->belongsTo(Room::class, 'RoomID', 'RoomID');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $table = 'BOOKINGS';
    protected $primaryKey = 'BookingID';
    public $timestamps = false;

    protected $fillable = [
        'CustomerID',
        'StaffID',
        'AdultAmount',
        'ChildAmount',
        'BookingDate',
        'Status'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'CustomerID');
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class, 'StaffID');
    }

    // Quan hệ với BookingRooms (một booking có nhiều phòng)
    public function bookingRooms()
    {
        return $this->hasMany(BookingRoom::class, 'BookingID', 'BookingID');
    }

    // Quan hệ với Rooms thông qua BookingRooms
    public function rooms()
    {
        return $this->hasManyThrough(
            Room::class,
            BookingRoom::class,
            'BookingID', // Foreign key on BookingRoom
            'RoomID',    // Foreign key on Room
            'BookingID', // Local key on Booking
            'RoomID'     // Local key on BookingRoom
        );
    }

    public function serviceUsages()
    {
        return $this->hasMany(ServiceUsage::class, 'BookingID');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'BookingID', 'BookingID');
    }

    // Tính tổng tiền từ tất cả các phòng
    public function getTotalAmountAttribute()
    {
        return $this->bookingRooms()->sum('TotalAmount');
    }
}

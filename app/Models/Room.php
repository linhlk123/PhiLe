<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    protected $table = 'ROOMS';
    protected $primaryKey = 'RoomID';
    public $timestamps = false;
    
    protected $fillable = [
        'RoomNumber',
        'RoomTypeID',
        'Status',
        'Single_Bed',
        'Double_Bed'
    ];

    public function roomType()
    {
        return $this->belongsTo(RoomType::class, 'RoomTypeID', 'RoomTypeID');
    }

    // Accessor để lấy giá phòng từ RoomType
    public function getPricePerNightAttribute()
    {
        return $this->roomType ? $this->roomType->PricePerNight : 0;
    }

    // Accessor để lấy tên phòng
    public function getRoomNameAttribute()
    {
        return $this->RoomNumber . ' - ' . ($this->roomType ? $this->roomType->TypeName : 'N/A');
    }
}
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomType extends Model
{
    protected $table = 'ROOM_TYPES';
    protected $primaryKey = 'RoomTypeID';
    public $timestamps = false;
    
    protected $fillable = [
        'TypeName',
        'Description',
        'PricePerNight',
        'MaxGuests'
    ];

    public function rooms()
    {
        return $this->hasMany(Room::class, 'RoomTypeID', 'RoomTypeID');
    }
}
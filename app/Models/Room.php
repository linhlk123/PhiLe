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
}
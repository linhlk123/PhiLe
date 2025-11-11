<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceUsage extends Model
{
    protected $table = 'SERVICE_USAGES';
    protected $primaryKey = 'UsageID';
    public $timestamps = false;

    protected $fillable = [
        'BookingID',
        'ServiceID',
        'Quantity',
        'TotalPrice',
        'StartTime',
        'GhiChuThem'
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'BookingID');
    }

    public function service()
    {
        return $this->belongsTo(Service::class, 'ServiceID');
    }
}

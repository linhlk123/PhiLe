<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'PAYMENTS';
    protected $primaryKey = 'PaymentID';
    public $timestamps = false;

    protected $fillable = [
        'BookingID',
        'PaymentDate',
        'Amount',
        'PaymentMethod',
        'PaymentStatus'
    ];

    protected $casts = [
        'PaymentDate' => 'datetime',
        'Amount' => 'decimal:2'
    ];

    /**
     * Relationship với Booking
     */
    public function booking()
    {
        return $this->belongsTo(Booking::class, 'BookingID', 'BookingID');
    }
}

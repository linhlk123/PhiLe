<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'CUSTOMERS';
    protected $primaryKey = 'CustomerID';
    public $timestamps = false;

    protected $fillable = [
        'FullName',
        'Gender',
        'Phone',
        'Email',
        'IDNumber',
        'Address',
        'Password',
    ];

    protected $hidden = [
        'Password',
        'remember_token',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'CustomerID');
    }

    // Override the password attribute name for authentication
    public function getAuthPassword()
    {
        return $this->Password;
    }
}

<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Hash;

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

    // Tự hash khi gán Password (tránh quên)
    public function setPasswordAttribute($value)
    {
        if (is_string($value) && !preg_match('/^\$2y\$\d{2}\$/', $value)) {
            $this->attributes['Password'] = Hash::make($value);
        } else {
            $this->attributes['Password'] = $value;
        }
    }

    public function getAuthPassword()
    {
        return $this->Password;
    }
}

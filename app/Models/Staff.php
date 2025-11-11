<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Staff extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'staffs';
    protected $primaryKey = 'StaffID';

    protected $fillable = [
        'FullName',
        'Role',
        'Phone',
        'Email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'StaffID');
    }

    // ⚠ Không mã hoá mật khẩu khi lưu
    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = $value;
    }
}

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\StaffController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\ServiceController;

Route::get('/', function () {
    return view('home', ['currentYear' => date('Y')]);
})->name('home');

// Staff routes
Route::prefix('staff')->name('staff.')->group(function () {
    // Đăng nhập
    Route::get('/login', [StaffController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [StaffController::class, 'login']);
    
    Route::middleware('auth:staff')->group(function () {
        Route::get('/room', [StaffController::class, 'staffRoom'])->name('room');
        Route::get('/staff-room', [StaffController::class, 'staffRoom'])->name('staff-room');
        
        Route::get('/rooms/{type}', [StaffController::class, 'getRoomsByType'])->name('rooms.byType');
        Route::post('/room/update', [RoomController::class, 'update'])->name('room.update');
        Route::post('/staff/logout', [StaffController::class, 'logout'])->name('staff.logout');
        
        // Booking management routes
        Route::get('/booking', [StaffController::class, 'bookingManagement'])->name('booking');
        Route::put('/booking/{id}/status', [StaffController::class, 'updateBookingStatus'])->name('booking.updateStatus');
        Route::get('/booking/{id}/rooms', [StaffController::class, 'getBookingRooms'])->name('booking.rooms');
        
        // Customer management routes
        Route::get('/customer', [StaffController::class, 'customerManagement'])->name('customer');
        Route::get('/customer/{id}', [StaffController::class, 'getCustomer'])->name('customer.get');
        Route::post('/customer', [StaffController::class, 'storeCustomer'])->name('customer.store');
        Route::put('/customer/{id}', [StaffController::class, 'updateCustomer'])->name('customer.update');
        Route::delete('/customer/{id}', [StaffController::class, 'deleteCustomer'])->name('customer.delete');
        
        // Employee management routes
        Route::get('/employee', [StaffController::class, 'employeeManagement'])->name('employee');
        Route::get('/employee/{id}', [StaffController::class, 'getEmployee'])->name('employee.get');
        Route::post('/employee', [StaffController::class, 'storeEmployee'])->name('employee.store');
        Route::put('/employee/{id}', [StaffController::class, 'updateEmployee'])->name('employee.update');
        Route::delete('/employee/{id}', [StaffController::class, 'deleteEmployee'])->name('employee.delete');
        
        // Service management routes
        Route::get('/service', [StaffController::class, 'serviceManagement'])->name('service');
        Route::get('/service/{id}', [StaffController::class, 'getService'])->name('service.get');
        Route::post('/service', [StaffController::class, 'storeService'])->name('service.store');
        Route::put('/service/{id}', [StaffController::class, 'updateService'])->name('service.update');
        Route::delete('/service/{id}', [StaffController::class, 'deleteService'])->name('service.delete');
        
        // Service usage routes
        Route::get('/service-usage/{id}', [StaffController::class, 'getServiceUsage'])->name('service.usage.get');
        Route::post('/service-usage', [StaffController::class, 'storeServiceUsage'])->name('service.usage.store');
        Route::put('/service-usage/{id}', [StaffController::class, 'updateServiceUsage'])->name('service.usage.update');
        Route::delete('/service-usage/{id}', [StaffController::class, 'deleteServiceUsage'])->name('service.usage.delete');
        Route::put('/service-usage/{id}/assign-room', [StaffController::class, 'assignRoomToServiceUsage'])->name('service.usage.assignRoom');
        
        // Profile routes
        Route::get('/profile', [StaffController::class, 'showProfile'])->name('profile');
        Route::put('/profile', [StaffController::class, 'updateProfile'])->name('profile.update');
        Route::post('/change-password', [StaffController::class, 'changePassword'])->name('change.password');
        
        // Invoice management routes
        Route::get('/invoice', [StaffController::class, 'invoiceManagement'])->name('invoice');
        Route::get('/invoice/{id}', [StaffController::class, 'getPayment'])->name('invoice.get');
        Route::post('/invoice', [StaffController::class, 'storePayment'])->name('invoice.store');
        Route::put('/invoice/{id}', [StaffController::class, 'updatePayment'])->name('invoice.update');
        Route::delete('/invoice/{id}', [StaffController::class, 'deletePayment'])->name('invoice.delete');
        
        // Map routes
        Route::get('/map', [StaffController::class, 'showMap'])->name('map');
    });

});

// Register routes
Route::get('/register', function() {
    return view('customer-signin');
})->name('register');
Route::post('/register', [CustomerController::class, 'register'])->name('customer.register');

// Đăng nhập
Route::get('/login', function() {
    return view('customer-signin');
})->name('customer.login');
Route::post('/login', [CustomerController::class, 'login']);

// Đăng xuất
Route::post('/logout', [CustomerController::class, 'logout'])->name('customer.logout');

Route::get('/dbconn', function () {
    return view('dbconn');
});

//Trang xem phòng
Route::get('/booking', function() {
    return view('booking');
})->name('booking');

// Trang đặt phòng
Route::get('/booking-room', function() {
    $user = auth()->guard('customer')->user();
    return view('booking_room', compact('user'));
})->name('booking.room');

// Route xử lý form đặt phòng
Route::post('/booking-room', [BookingController::class, 'store'])->name('booking.store');


// Hiển thị danh sách phòng theo loại cho nhân viên
Route::get('/staff/rooms/{type}', [StaffController::class, 'getRoomsByType'])->name('staff.rooms.byType');

// API lấy danh sách phòng cho booking
Route::get('/api/rooms/all-by-type', [RoomController::class, 'getAllRoomsByType'])->name('api.rooms.allByType');

// API lấy phòng available cho booking room
Route::get('/api/rooms/available', [BookingController::class, 'getAvailableRooms'])->name('api.rooms.available');

// Test route để debug
Route::get('/test-rooms', function() {
    $rooms = \App\Models\Room::all();
    $roomTypes = \App\Models\RoomType::all();
    return response()->json([
        'rooms_count' => $rooms->count(),
        'room_types_count' => $roomTypes->count(),
        'rooms' => $rooms,
        'room_types' => $roomTypes
    ]);
});


Route::get('/services', function () {
    return view('ServiceList');
})->name('services');

// Route đặt dịch vụ
Route::post('/services/book', [ServiceController::class, 'bookService'])->name('services.book');

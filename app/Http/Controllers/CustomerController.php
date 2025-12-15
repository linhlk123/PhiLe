<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    /**
     * Hiển thị form đăng ký
     */
    public function showRegisterForm()
    {
        return view('auth.customer-register');
    }

    /**
     * Xử lý đăng ký
     */
    public function register(Request $request)
    {
        try {
            Log::info('Register request:', $request->all());

            // Validate dữ liệu đầu vào
            $validated = $request->validate([
                'FullName' => 'required|string|max:100',
                'Gender' => 'required',
                'Phone' => 'required|string|max:20',
                'Email' => 'required|email|unique:customers,Email',
                'IDNumber' => 'required|string|unique:customers,IDNumber',
                'Address' => 'required|string',
                'password' => 'required|string|min:6',
            ]);

            // Tạo khách hàng mới (lưu password dạng text)
            $customer = Customer::create([
                'FullName' => $validated['FullName'],
                'Gender' => $validated['Gender'],
                'Phone' => $validated['Phone'],
                'Email' => $validated['Email'],
                'IDNumber' => $validated['IDNumber'],
                'Address' => $validated['Address'],
                'Password' => $validated['password'],
            ]);

            Log::info('Customer created successfully:', ['id' => $customer->CustomerID]);

            return response()->json([
                'success' => true,
                'message' => 'Tài khoản đã được tạo thành công! Vui lòng đăng nhập.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Registration error:', ['error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đăng ký. Vui lòng thử lại.'
            ], 500);
        }
    }

    /**
     * Hiển thị form đăng nhập
     */
    public function showLoginForm()
    {
        return view('auth.customer-login');
    }

    /**
     * Xử lý đăng nhập (dùng password dạng text)
     */
    public function login(Request $request)
    {
        try {
            Log::info('Login request data:', $request->all());

            $validated = $request->validate([
                'Email' => 'required|email',
                'password' => 'required|string',
            ]);

            $customer = Customer::where('Email', $validated['Email'])->first();

            // So sánh với mật khẩu đã băm
            if (!$customer || !Hash::check($validated['password'], $customer->Password)) {
                Log::warning('Customer login failed', ['email' => $validated['Email']]);
                return response()->json([
                    'success' => false,
                    'message' => 'Email hoặc mật khẩu không chính xác.'
                ], 401);
            }

            Auth::guard('customer')->login($customer);
            $request->session()->regenerate();

            return response()->json([
                'success' => true,
                'message' => 'Đăng nhập thành công!',
                'redirect' => route('home')
            ]);

            Log::warning('Password mismatch for customer:', [
                'id' => $customer->CustomerID,
                'provided_password' => $validated['password']
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Email hoặc mật khẩu không chính xác.'
            ], 401);
        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation error:', ['errors' => $e->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Login error:', ['message' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi đăng nhập. Vui lòng thử lại sau.'
            ], 500);
        }
    }

    /**
     * Xử lý đăng xuất
     */
    public function logout(Request $request)
    {
        try {
            $customer = Auth::guard('customer')->user();
            if ($customer) {
                Log::info('Customer logout', ['id' => $customer->CustomerID, 'email' => $customer->Email]);
            }

            Auth::guard('customer')->logout();

            // Hủy toàn bộ session cũ
            $request->session()->invalidate();
            // Tạo lại CSRF token
            $request->session()->regenerateToken();

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Đã đăng xuất',
                    'redirect' => route('home')
                ]);
            }

            return redirect()->route('home');
        } catch (\Throwable $e) {
            Log::error('Logout error', ['message' => $e->getMessage()]);
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi đăng xuất'
                ], 500);
            }
            return redirect()->route('home')->with('error', 'Lỗi đăng xuất');
        }
    }

    public function showProfile()
    {
        $customer = Auth::guard('customer')->user();

        return view('profile', compact('customer'));
    }
}

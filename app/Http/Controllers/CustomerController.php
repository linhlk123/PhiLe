<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
                'Password' => $validated['password'], // <-- chữ P hoa đúng theo DB
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

            if (!$customer) {
                Log::warning('Customer not found for email:', ['email' => $validated['Email']]);
                return response()->json([
                    'success' => false,
                    'message' => 'Email hoặc mật khẩu không chính xác.'
                ], 401);
            }

            // So sánh password text
            if ($customer->Password === $validated['password']) {
                Log::info('Password matches for customer:', ['id' => $customer->CustomerID]);

                Auth::guard('customer')->login($customer);
                $request->session()->regenerate();

                return response()->json([
                    'success' => true,
                    'message' => 'Đăng nhập thành công!',
                    'redirect' => route('home')
                ]);
            }

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
        Auth::guard('customer')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}

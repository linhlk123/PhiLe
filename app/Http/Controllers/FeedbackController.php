<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Feedback;

class FeedbackController extends Controller
{
    public function store(Request $request)
    {
        // Validate
        $request->validate([
            'full_name' => 'required|max:100',
            'email'     => 'required|email|max:100',
            'phone'     => 'required|max:20',
            'message'   => 'nullable|max:255',
        ]);

        // INSERT VÀO BẢNG FEEDBACK
        Feedback::create([
            'FullName' => $request->full_name,
            'Email'    => $request->email,
            'Phone'    => $request->phone,
            'Message'  => $request->message,
        ]);

        return back()->with('success', 'Gửi phản hồi thành công!');
    }
}

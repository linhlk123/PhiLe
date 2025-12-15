<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Payment;

class CustomerPaymentController extends Controller
{
    public function index()
    {
        $customer = Auth::guard('customer')->user();

        $payments = Payment::whereHas('booking', function ($q) use ($customer) {
            $q->where('CustomerID', $customer->CustomerID);
        })
            ->orderBy('PaymentDate', 'desc')
            ->get();

        return view('payments', compact('payments'));
    }
}

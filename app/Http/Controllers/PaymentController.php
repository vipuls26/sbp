<?php

namespace App\Http\Controllers;


use App\Models\Payment;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    public function paymentHistory()
    {
        $userId = Auth::user()->id;
        $payments = Payment::where('user_id', $userId)
            ->with('plan')
            ->latest()
            ->get();

        return view('user.payment-history', compact('payments'));
    }
}

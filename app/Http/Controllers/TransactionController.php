<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->orWhereHas('shop', function ($q) {
                $q->where('user_id', Auth::id());
            })
            ->latest()
            ->get();
        return view('transactions.index', compact('transactions'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;

class AdminTopupController extends Controller
{
    public function index()
    {
        $pendingTopups = Transaction::where('type', 'top-up')->where('status', 'pending')->with('user')->latest()->get();
        return view('admin.topups', compact('pendingTopups'));
    }

    public function approve($id)
    {
        $transaction = Transaction::findOrFail($id);
        if ($transaction->status !== 'pending') {
            return back()->with('error', 'Already processed.');
        }
        $user = User::findOrFail($transaction->user_id);
        $user->wallet_balance += $transaction->amount;
        $user->save();
        $transaction->status = 'approved';
        $transaction->save();
        return back()->with('success', 'Top up approved and balance updated.');
    }
}

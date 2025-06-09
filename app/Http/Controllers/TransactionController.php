<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\User;

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

    public function showTopUpForm()
    {
        return view('topup');
    }

    public function topUp(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = $request->user();
        $amount = $request->input('amount');
        $imagePath = $request->file('image')->store('topup_proofs', 'public');

        // Create transaction record as pending
        Transaction::create([
            'user_id' => $user->id,
            'type' => 'top-up',
            'amount' => $amount,
            'description' => 'Wallet top-up',
            'image_url' => $imagePath,
            'status' => 'pending',
        ]);

        return redirect()->route('dashboard')->with('success', 'Top up request submitted! Awaiting admin approval.');
    }
}

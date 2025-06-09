<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function index()
    {
        // Only allow user with id 1 (admin)
        if (!auth()->check() || auth()->user()->id !== 1) {
            return redirect('/'); // or abort(403, 'Unauthorized');
        }
        $totalIncome = Order::where('status', 'completed')->sum('platform_fee');
        $recentOrders = Order::with('customer')->where('status', 'completed')->latest()->take(5)->get();
        return view('admin.dashboard', [
            'totalIncome' => $totalIncome,
            'recentOrders' => $recentOrders,
        ]);
    }
}

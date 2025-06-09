<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Shop;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if ($user->shop) {
            // Seller: show orders containing items from their shop
            $orders = \App\Models\Order::whereHas('orderItems', function ($q) use ($user) {
                $q->where('shop_id', $user->shop->id);
            })->with(['orderItems' => function ($q) use ($user) {
                $q->where('shop_id', $user->shop->id)->with('product');
            }, 'customer'])->get();
        } else {
            // Buyer: show their own orders
            $orders = Order::where('customer_id', $user->id)->with('orderItems.product')->get();
        }
        return view('orders.index', compact('orders'));
    }

    public function create(Product $product)
    {
        return view('orders.create', compact('product'));
    }

    public function store(Request $request, Product $product)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);
        // Order creation logic here (simplified)
        // ...
        return redirect()->route('orders.index');
    }

    public function show(Order $order)
    {
        $user = Auth::user();
        if ($user->shop) {
            // Seller: only show items from their shop
            $items = $order->orderItems()->where('shop_id', $user->shop->id)->with('product', 'shop')->get();
        } else {
            // Buyer: show all items
            $items = $order->orderItems()->with('product', 'shop')->get();
        }
        return view('orders.show', [
            'order' => $order,
            'items' => $items,
        ]);
    }

    public function markCompleted(Order $order)
    {
        $user = Auth::user();
        if (!$user->shop) {
            abort(403, 'Only sellers can complete orders.');
        }
        $shop = $user->shop;
        $shopId = $shop->id;
        // Only allow if the order contains items from this seller's shop and is not already completed
        $hasItems = $order->orderItems()->where('shop_id', $shopId)->exists();
        if (!$hasItems) {
            abort(403, 'Order does not belong to your shop.');
        }
        if ($order->status === 'completed') {
            return back()->with('success', 'Order already completed.');
        }
        // Calculate total for this shop
        $shopTotal = $order->orderItems()->where('shop_id', $shopId)->sum(\DB::raw('quantity * price_per_unit'));
        // Calculate platform fee (10%) and vendor payout (90%)
        $platformFee = round($shopTotal * 0.10, 2);
        $vendorPayout = $shopTotal - $platformFee;
        // Credit shop wallet with 90%
        $shop->wallet_balance += $vendorPayout;
        $shop->save();
        // Create transaction for shop (90%)
        \App\Models\Transaction::create([
            'user_id' => $shop->user_id,
            'shop_id' => $shop->id,
            'order_id' => $order->id,
            'type' => 'sale_credit',
            'amount' => $vendorPayout,
            'description' => 'Penjualan order #' . $order->id . ' (90%)',
        ]);
        // Credit admin wallet with 10%
        $admin = \App\Models\User::find(1);
        if ($admin) {
            $admin->wallet_balance += $platformFee;
            $admin->save();
            \App\Models\Transaction::create([
                'user_id' => $admin->id,
                'order_id' => $order->id,
                'type' => 'sale_credit', // use allowed type
                'amount' => $platformFee,
                'description' => 'Platform fee from order #' . $order->id . ' (10%)',
            ]);
        }
        // Mark as completed
        $order->status = 'completed';
        $order->save();
        return back()->with('success', 'Order marked as completed and wallet updated.');
    }

    public function markInShipping(Order $order)
    {
        $user = Auth::user();
        if (!$user->shop) {
            abort(403, 'Only sellers can update shipping status.');
        }
        $shop = $user->shop;
        $shopId = $shop->id;
        // Only allow if the order contains items from this seller's shop and is not already in shipping or completed
        $hasItems = $order->orderItems()->where('shop_id', $shopId)->exists();
        if (!$hasItems) {
            abort(403, 'Order does not belong to your shop.');
        }
        if ($order->status === 'inshipping' || $order->status === 'completed') {
            return back()->with('success', 'Order already in shipping or completed.');
        }
        $order->status = 'inshipping';
        $order->save();
        return back()->with('success', 'Order marked as in shipping.');
    }
}

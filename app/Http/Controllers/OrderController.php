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
        // Credit shop wallet
        $shop->wallet_balance += $shopTotal;
        $shop->save();
        // Create transaction for shop
        \App\Models\Transaction::create([
            'user_id' => $shop->user_id,
            'shop_id' => $shop->id,
            'order_id' => $order->id,
            'type' => 'sale_credit',
            'amount' => $shopTotal,
            'description' => 'Penjualan order #' . $order->id,
        ]);
        // Mark as completed
        $order->status = 'completed';
        $order->save();
        return back()->with('success', 'Order marked as completed and wallet updated.');
    }
}

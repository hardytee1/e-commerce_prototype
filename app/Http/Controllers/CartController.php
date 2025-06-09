<?php
namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function index()
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $items = $cart->items()->with('product')->get();
        return view('cart.index', compact('cart', 'items'));
    }

    public function add(Request $request, $productId)
    {
        $cart = Cart::firstOrCreate(['user_id' => Auth::id()]);
        $item = $cart->items()->where('product_id', $productId)->first();
        if ($item) {
            $item->quantity += $request->input('quantity', 1);
            $item->save();
        } else {
            $cart->items()->create([
                'product_id' => $productId,
                'quantity' => $request->input('quantity', 1),
            ]);
        }
        return redirect()->route('cart.index')->with('success', 'Product added to cart.');
    }

    public function update(Request $request, $itemId)
    {
        $item = CartItem::findOrFail($itemId);
        // Manual access control: only cart owner can update
        if (Auth::id() !== $item->cart->user_id) {
            abort(403, 'Unauthorized action.');
        }
        $item->quantity = $request->input('quantity', 1);
        $item->save();
        return back()->with('success', 'Cart updated.');
    }

    public function remove($itemId)
    {
        $item = CartItem::findOrFail($itemId);
        // Manual access control: only cart owner can remove
        if (Auth::id() !== $item->cart->user_id) {
            abort(403, 'Unauthorized action.');
        }
        $item->delete();
        return back()->with('success', 'Item removed from cart.');
    }

    public function checkout()
    {
        $user = Auth::user();
        $cart = Cart::firstOrCreate(['user_id' => $user->id]);
        $items = $cart->items()->with('product')->get();
        if ($items->isEmpty()) {
            return redirect()->route('cart.index')->with('success', 'Keranjang kosong.');
        }

        // Calculate total
        $total = 0;
        foreach ($items as $item) {
            if ($item->product->stock < $item->quantity) {
                return redirect()->route('cart.index')->with('error', 'Stok produk ' . $item->product->name . ' tidak cukup.');
            }
            $total += $item->product->price * $item->quantity;
        }

        if ($user->wallet_balance < $total) {
            return redirect()->route('cart.index')->with('error', 'Saldo tidak cukup.');
        }

        \DB::transaction(function () use ($user, $cart, $items, $total) {
            // Create order
            $order = \App\Models\Order::create([
                'customer_id' => $user->id,
                'total_amount' => $total,
                'status' => 'pending',
            ]);

            foreach ($items as $item) {
                $product = $item->product;
                $shop = $product->shop;
                // Reduce stock
                $product->stock -= $item->quantity;
                $product->save();
                // Add to order items
                $orderItem = $order->orderItems()->create([
                    'product_id' => $product->id,
                    'shop_id' => $shop->id,
                    'quantity' => $item->quantity,
                    'price_per_unit' => $product->price,
                ]);
                // --- REMOVED: Shop wallet and transaction update here ---
                // $shop->wallet_balance += $item->quantity * $product->price;
                // $shop->save();
                // \App\Models\Transaction::create([
                //     'user_id' => $shop->user_id,
                //     'shop_id' => $shop->id,
                //     'order_id' => $order->id,
                //     'type' => 'sale_credit',
                //     'amount' => $item->quantity * $product->price,
                //     'description' => 'Penjualan produk #' . $product->id,
                // ]);
            }
            // Deduct user balance
            $user->wallet_balance -= $total;
            $user->save();
            // User transaction
            \App\Models\Transaction::create([
                'user_id' => $user->id,
                'order_id' => $order->id,
                'type' => 'purchase', // changed from 'debit'
                'amount' => $total,
                'description' => 'Pembelian order #' . $order->id,
            ]);
            // Clear cart
            $cart->items()->delete();
        });

        return view('cart.checkout', ['success' => true, 'total' => $total]);
    }
}

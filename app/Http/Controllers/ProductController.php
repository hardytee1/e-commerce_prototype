<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Shop $shop)
    {
        $products = $shop->products;
        return view('products.index', compact('shop', 'products'));
    }

    public function create(Shop $shop)
    {
        // Only allow the owner to add products to their own shop
        if (auth()->user()->shop && auth()->user()->shop->id !== $shop->id) {
            abort(403, 'Unauthorized action.');
        }
        return view('products.create', compact('shop'));
    }

    public function store(Request $request, Shop $shop)
    {
        // Only allow the owner to add products to their own shop
        if (auth()->user()->shop && auth()->user()->shop->id !== $shop->id) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);
        $shop->products()->create($request->only('name', 'price', 'stock'));
        return redirect()->route('shops.show', $shop);
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }

    public function edit(Product $product)
    {
        // Only allow the owner to edit their own product
        if (auth()->user()->shop && $product->shop_id !== auth()->user()->shop->id) {
            abort(403, 'Unauthorized action.');
        }
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        // Only allow the owner to update their own product
        if (auth()->user()->shop && $product->shop_id !== auth()->user()->shop->id) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|integer',
        ]);
        $product->update($request->only('name', 'price', 'stock'));
        return redirect()->route('products.show', $product);
    }

    public function allProducts()
    {
        $products = Product::with('shop')->latest()->paginate(20);
        return view('products.all', compact('products'));
    }

    public function destroy(Product $product)
    {
        // Only allow the owner to delete their own product
        if (auth()->user()->shop && $product->shop_id !== auth()->user()->shop->id) {
            abort(403, 'Unauthorized action.');
        }
        $product->delete();
        return redirect()->route('shops.products.index', $product->shop_id)->with('success', 'Product deleted successfully.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ShopController extends Controller
{
    public function index()
    {
        $shops = Shop::with('user')->get();
        return view('shops.index', compact('shops'));
    }

    public function create()
    {
        return view('shops.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:shops,name',
        ]);
        $shop = Shop::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);
        return redirect()->route('shops.show', $shop);
    }

    public function show(Shop $shop)
    {
        return view('shops.show', compact('shop'));
    }

    public function edit(Shop $shop)
    {
        // Only allow the owner to edit their own shop
        if (auth()->user()->shop && auth()->user()->shop->id !== $shop->id) {
            abort(403, 'Unauthorized action.');
        }
        return view('shops.edit', compact('shop'));
    }

    public function update(Request $request, Shop $shop)
    {
        // Only allow the owner to update their own shop
        if (auth()->user()->shop && auth()->user()->shop->id !== $shop->id) {
            abort(403, 'Unauthorized action.');
        }
        $request->validate([
            'name' => 'required|unique:shops,name,' . $shop->id,
        ]);
        $shop->update([
            'name' => $request->name,
            'slug' => Str::slug($request->name),
        ]);
        return redirect()->route('shops.show', $shop);
    }
}

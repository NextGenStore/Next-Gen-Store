<?php

namespace App\Http\Controllers;

use App\Models\Products;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Cart;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = Cart::where('user_id', Auth::id())->with('product')->get();
        $products = $cartItems->map(fn($item) => $item->product);
        
        return view('card.index', [
            'products' => $products,
            'cartItems' => $cartItems->keyBy('product_id')
        ]);
    }
    
    public function getCount()
    {
        return response()->json([
            'count' => Cart::where('user_id', Auth::id())->sum('quantity')
        ]);
    }

    public function add(Request $request, Products $product)
    {
        $quantity = $request->input('quantity', 1);
        
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();
            
        if ($cartItem) {
            $cartItem->quantity += $quantity;
            $cartItem->save();
        } else {
            Cart::create([
                'user_id' => Auth::id(),
                'product_id' => $product->id,
                'quantity' => $quantity
            ]);
        }
        
        return response()->json([
            'count' => Cart::where('user_id', Auth::id())->sum('quantity')
        ]);
    }

    public function remove(Products $product)
    {
        Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->delete();
            
        return response()->json([
            'count' => Cart::where('user_id', Auth::id())->sum('quantity')
        ]);
    }

    public function updateQuantity(Request $request, Products $product)
    {
        $quantity = $request->input('quantity');
        if ($quantity <= 0) {
            return response()->json([
                'message' => 'Quantity must be greater than 0'
            ], 400);
        }
        
        $cartItem = Cart::where('user_id', Auth::id())
            ->where('product_id', $product->id)
            ->first();
            
        if ($cartItem) {
            $cartItem->quantity = $quantity;
            $cartItem->save();
            
            return response()->json([
                'count' => Cart::where('user_id', Auth::id())->sum('quantity')
            ]);
        }
        
        return response()->json([
            'message' => 'Cart item not found'
        ], 404);
    }

    public function checkout()
    {
        // TODO: Implement checkout logic
        return redirect()->route('checkout.index');
    }
} 
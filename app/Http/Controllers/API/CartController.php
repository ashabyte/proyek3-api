<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart as CartModel;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    // Get user's cart
    public function index()
    {
        $carts = CartModel::with('product')
            ->where('user_id', Auth::id())
            ->get();

        return response()->json([
            'success' => true,
            'data' => $carts
        ]);
    }

    // Add item to cart
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1'
        ]);

        $productId = $request->input('product_id');
        $quantity = (int) $request->input('quantity');

        $product = Product::where('id', $productId)->first();

        // Check if product already in cart
        $existingCart = CartModel::where('user_id', Auth::id())
            ->where('product_id', $productId)
            ->first();

        if ($existingCart) {
            $existingCart->quantity = $existingCart->quantity + $quantity;
            $existingCart->save();
            $cart = $existingCart;
        } else {
            $cart = new CartModel();
            $cart->user_id = Auth::id();
            $cart->product_id = $productId;
            $cart->quantity = $quantity;
            $cart->nama = $product->nama;
            $cart->deskripsi = $product->deskripsi;
            $cart->harga = $product->harga;
            $cart->durasi = $product->durasi;
            $cart->kategori = $product->kategori;
            $cart->stok = $product->stok;
            $cart->gambar = $product->gambar;
            $cart->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Item added to cart',
            'data' => $cart
        ]);
    }

    // Remove item from cart
    public function destroy(int $id)
    {
        $cart = CartModel::where('user_id', Auth::id())
            ->where('id', $id)
            ->first();

        if ($cart) {
            $cart->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Item removed from cart'
        ]);
    }

    // Clear all cart
    public function clear()
    {
        CartModel::where('user_id', Auth::id())->delete();

        return response()->json([
            'success' => true,
            'message' => 'Cart cleared'
        ]);
    }
}

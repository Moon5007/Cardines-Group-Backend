<?php

namespace App\Http\Controllers;

use App\Models\CartProduct;
use App\Http\Resources\CartProductResource;
use Illuminate\Http\Request;

class CartProductController extends Controller
{
    public function index()
    {
        return CartProductResource::collection(CartProduct::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'total' => 'required|numeric|min:0',
        ]);

        $cartProduct = CartProduct::create($validated);

        return new CartProductResource($cartProduct);
    }

    public function show(CartProduct $cartProduct)
    {
        return new CartProductResource($cartProduct);
    }

    public function update(Request $request, CartProduct $cartProduct)
    {
        $validated = $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'total' => 'required|numeric|min:0',
        ]);

        $cartProduct->update($validated);

        return new CartProductResource($cartProduct);
    }

    public function destroy(CartProduct $cartProduct)
    {
        $cartProduct->delete();

        return response()->json([
            'message' => 'Cart product deleted successfully'
        ]);
    }
}
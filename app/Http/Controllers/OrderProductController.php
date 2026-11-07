<?php
namespace App\Http\Controllers;

use App\Models\OrderProduct;
use App\Http\Resources\OrderProductResource;
use Illuminate\Http\Request;

class OrderProductController extends Controller
{
    public function index()
    {
        return OrderProductResource::collection(OrderProduct::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'productid' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'total' => 'required|numeric|min:0',
            'contact_number' => 'required|string|max:30',
        ]);

        $orderProduct = OrderProduct::create($validated);

        return new OrderProductResource($orderProduct);
    }

    public function show(OrderProduct $orderProduct)
    {
        return new OrderProductResource($orderProduct);
    }

    public function update(Request $request, OrderProduct $orderProduct)
    {
        $validated = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'productid' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'total' => 'required|numeric|min:0',
            'contact_number' => 'required|string|max:30',
        ]);

        $orderProduct->update($validated);

        return new OrderProductResource($orderProduct);
    }

    public function destroy(OrderProduct $orderProduct)
    {
        $orderProduct->delete();

        return response()->json([
            'message' => 'Order product deleted successfully'
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Http\Resources\OrderResource;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index()
    {
        return OrderResource::collection(Order::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'status' => 'required|string|max:50',
            'address' => 'required|string',
            'contact_number' => 'required|string|max:30',
        ]);

        $order = Order::create($validated);

        return new OrderResource($order);
    }

    public function show(Order $order)
    {
        return new OrderResource($order);
    }

    public function update(Request $request, Order $order)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'status' => 'required|string|max:50',
            'address' => 'required|string',
            'contact_number' => 'required|string|max:50',
        ]);

        $order->update($validated);

        return new OrderResource($order);
    }

    public function destroy(Order $order)
    {
        $order->delete();

        return response()->json([
            'message' => 'Order deleted successfully'
        ]);
    }
}

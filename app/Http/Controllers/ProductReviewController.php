<?php

namespace App\Http\Controllers;

use App\Models\ProductReview;
use App\Http\Resources\ProductReviewResource;
use Illuminate\Http\Request;

class ProductReviewController extends Controller
{
    public function index()
    {
        return ProductReviewResource::collection(ProductReview::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'comment' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ]);

        $review = ProductReview::create($validated);

        return new ProductReviewResource($review);
    }

    public function show(ProductReview $productReview)
    {
        return new ProductReviewResource($productReview);
    }

    public function update(Request $request, ProductReview $productReview)
    {
        $validated = $request->validate([
            'comment' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'user_id' => 'required|exists:users,id',
            'product_id' => 'required|exists:products,id',
        ]);

        $productReview->update($validated);

        return new ProductReviewResource($productReview);
    }

    public function destroy(ProductReview $productReview)
    {
        $productReview->delete();

        return response()->json([
            'message' => 'Product review deleted successfully'
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\ProductPhoto;
use App\Http\Resources\ProductPhotoResource;
use Illuminate\Http\Request;

class ProductPhotoController extends Controller
{
    public function index()
    {
        return ProductPhotoResource::collection(ProductPhoto::all());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'photo' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
        ]);

        $photo = ProductPhoto::create($validated);

        return new ProductPhotoResource($photo);
    }

    public function show(ProductPhoto $productPhoto)
    {
        return new ProductPhotoResource($productPhoto);
    }

    public function update(Request $request, ProductPhoto $productPhoto)
    {
        $validated = $request->validate([
            'photo' => 'required|string|max:255',
            'product_id' => 'required|exists:products,id',
        ]);

        $productPhoto->update($validated);

        return new ProductPhotoResource($productPhoto);
    }

    public function destroy(ProductPhoto $productPhoto)
    {
        $productPhoto->delete();

        return response()->json([
            'message' => 'Product photo deleted successfully'
        ]);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Http\Resources\ProductResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ProductController extends Controller
{
    public function index()
    {
        return ProductResource::collection(
            Product::with(['category', 'photos'])->latest()->paginate(15)
        );
    }

    public function show(Product $product)
    {
        return new ProductResource($product->load(['category', 'photos']));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:255|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'stock' => 'required|integer|min:0',
            'status' => 'nullable|string|max:50',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        $validated['category_id'] = Category::firstOrCreate(
            ['name' => trim($validated['category'])],
            ['description' => null, 'status' => 'active']
        )->id;
        unset($validated['category']);

        $image = $this->resolveImage($request, $validated['image'] ?? null);
        unset($validated['image'], $validated['image_file']);

        $product = Product::create($validated);
        $this->savePhoto($product, $image);

        return (new ProductResource($product->load(['category', 'photos'])))
            ->response()
            ->setStatusCode(201);
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => ['required', 'string', 'max:255',
                      Rule::unique('products', 'sku')->ignore($product->id)],
            'price' => 'required|numeric|min:0',
            'category' => 'required|string|max:100',
            'stock' => 'required|integer|min:0',
            'status' => 'nullable|string|max:50',
            'image' => 'nullable|string|max:255',
            'image_file' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'description' => 'nullable|string',
        ]);

        $validated['category_id'] = Category::firstOrCreate(
            ['name' => trim($validated['category'])],
            ['description' => null, 'status' => 'active']
        )->id;
        unset($validated['category']);

        $image = $this->resolveImage($request, $validated['image'] ?? null);
        unset($validated['image'], $validated['image_file']);

        $product->update($validated);
        $this->savePhoto($product, $image);

        return new ProductResource($product->load(['category', 'photos']));
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted successfully'
        ]);
    }

    // Use the uploaded file if there is one, otherwise the typed filename/URL
    private function resolveImage(Request $request, ?string $image): ?string
    {
        if ($request->hasFile('image_file')) {
            $file = $request->file('image_file');
            $name = Str::uuid() . '.' . $file->extension();
            $file->move(public_path('images/products'), $name);
            return $name;
        }

        return $image;
    }

    // Save the image filename/URL in product_photos (update the first photo or create one)
    private function savePhoto(Product $product, ?string $image): void
    {
        if (!filled($image)) {
            return;
        }

        $photo = $product->photos()->first();

        if ($photo) {
            $photo->update(['photo' => $image]);
        } else {
            $product->photos()->create(['photo' => $image]);
        }
    }
}
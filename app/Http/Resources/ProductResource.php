<?php

namespace App\Http\Resources;
    
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $photo = $this->photos->first()?->photo;

        return [
            'id' => $this->id,
            'name' => $this->name,
            'sku' => $this->sku,
            'price' => $this->price,
            'category' => $this->relationLoaded('category')
                ? $this->getRelation('category')?->name
                : $this->getAttribute('category'),
            'stock' => $this->stock,
            'status' => $this->status,
            'image' => $photo,
            'image_url' => $photo
                ? (str_starts_with($photo, 'http') ? $photo : asset('images/products/' . $photo))
                : null,
            'description' => $this->description,
        ];
    }
}
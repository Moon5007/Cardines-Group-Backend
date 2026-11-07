<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'order_id' => $this->order_id,
            'productid' => $this->productid,
            'quantity' => $this->quantity,
            'total' => $this->total,
            'contact_number' => $this->contact_number,
        ];
    }
}
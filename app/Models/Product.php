<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;

class Product extends Model
{
  protected $fillable = [
        'name',
        'sku',
        'price',
        'category_id',
        'stock',
        'status',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function cartProducts()
{
    return $this->hasMany(CartProduct::class);
}
public function photos()
{
    return $this->hasMany(ProductPhoto::class);
}
public function reviews()
{
    return $this->hasMany(ProductReview::class);
}
public function orderProducts()
{
    return $this->hasMany(OrderProduct::class);
}
}

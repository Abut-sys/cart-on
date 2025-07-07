<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'sub_category_product_id',
        'brand_id',
        'name',
        'price',
        'description',
        'sales',
        'color',
        'size',
        'old_price',
        'rating',
        'rating_count',
        'markup',
        'qr_code_path'
    ];

    protected $casts = [
        'color' => 'array',
        'size' => 'array',
    ];

    public function subCategory()
    {
        return $this->belongsTo(SubCategoryProduct::class, 'sub_category_product_id');
    }

    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function subVariant()
    {
        return $this->hasMany(SubVariant::class);
    }

    public function carts()
    {
        return $this->hasMany(Cart::class);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class);
    }

    public function checkouts()
    {
        return $this->hasMany(Checkout::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class);
    }

    public function firstImage()
    {
        return $this->hasOne(ProductImage::class)->orderBy('id');
    }

    public function reviewProducts()
    {
        return $this->hasMany(ReviewProduct::class);
    }

    public function getQrCodeUrlAttribute()
    {
        return $this->qr_code_path ? asset($this->qr_code_path) : null;
    }
}

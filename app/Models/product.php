<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'sub_category_product_id',
        'brand_id',
        'name',
        'image_path',
        'price',
        'description',
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
}

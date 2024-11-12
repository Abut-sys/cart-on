<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategoryProduct extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'category_product_id'];

    public function category()
    {
        return $this->belongsTo(CategoryProduct::class, 'category_product_id');
    }

    public function subCategory()
    {
        return $this->hasMany(Product::class, 'product_id');
    }
}

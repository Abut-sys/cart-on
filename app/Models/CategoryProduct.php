<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryProduct extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function subCategories()
    {
        return $this->hasMany(SubCategoryProduct::class, 'category_product_id');
    }

    public function CategoryProduct()
    {
        return $this->hasMany(product::class, 'product_id');
    }

    public function brands()
    {
        return $this->hasMany(Brand::class);
    }
}

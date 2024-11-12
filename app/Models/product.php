<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model

{
    use HasFactory;

    protected $fillable = [
        'name',
        'brands_id',
        'category_products_id',
        'sub_category_products_id',
        'description',
        'price',
        'stock',
        'image_path'
    ];

    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brands_id');
    }

    public function subVariants()
    {
        return $this->hasMany(SubVariant::class);
    }

    public function categoryProduct()
    {
        return $this->belongsTo(CategoryProduct::class, 'category_products_id');
    }

    public function subCategoryProduct()
    {
        return $this->belongsTo(SubCategoryProduct::class, 'sub_category_products_id');
    }

}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class product extends Model
{
    use HasFactory;

    // Allow mass assignment for these attributes
    protected $fillable = [
        'name',
        'brands_id',
        'category_products_id',
        'sub_category_products_id',
        'description',
        'price',
        'stock',
        'image_path',
    ];

    /**
     * Relationship with Brand model
     */
    public function brand()
    {
        return $this->belongsTo(Brand::class, 'brands_id');
    }

    /**
     * Relationship with SubVariant model
     */
    public function subVariants()
    {
        return $this->hasMany(SubVariant::class);
    }

    /**
     * Relationship with CategoryProduct model
     */
    public function categoryProduct()
    {
        return $this->belongsTo(CategoryProduct::class, 'category_products_id');
    }

    /**
     * Many-to-Many relationship with SubCategoryProduct model via 'product_sub_category' pivot table
     */
    public function subCategoryProducts()
    {
        return $this->belongsToMany(SubCategoryProduct::class, 'product_sub_category', 'products_id', 'sub_category_products_id');
    }

}

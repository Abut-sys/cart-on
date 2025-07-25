<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category_product_id',
        'description',
        'logo_path',
    ];


    public function product()
    {
        return $this->hasMany(Product::class);
    }

    public function categoryProduct()
    {
        return $this->belongsTo(CategoryProduct::class);
    }
}

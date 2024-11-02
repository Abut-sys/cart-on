<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'logo_path',
    ];


    public function Brand()
    {
        return $this->hasMany(Product::class, 'product_id');
    }

    // public function products(){
    //     return $this->hasMany(product::class);
    // }
}

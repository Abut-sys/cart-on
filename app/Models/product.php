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

}

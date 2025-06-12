<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Information extends Model
{
    protected $table = 'information';

    protected $fillable = [
        'title',
        'description',
        'type',
        'rating',
        'rating_count',
        'image_url',
        'is_active',
    ];

    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
}

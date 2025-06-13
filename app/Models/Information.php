<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Information extends Model
{
    use HasFactory;

    protected $table = 'information'; // Optional, jika mengikuti konvensi Laravel, boleh dihapus

    protected $fillable = [
        'title',
        'description',
        'rating',
        'rating_count',
    ];

    /**
     * Relasi polymorphic ke reviews.
     */
    public function reviews()
    {
        return $this->morphMany(Review::class, 'reviewable');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_sub_category', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('products_id');
            $table->unsignedBigInteger('sub_category_products_id');
            $table->timestamps();

            // Foreign keys
            $table->foreign('products_id')->references('id')->on('products')->onDelete('cascade');
            $table->foreign('sub_category_products_id')->references('id')->on('sub_category_products')->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sub_category');
    }
};

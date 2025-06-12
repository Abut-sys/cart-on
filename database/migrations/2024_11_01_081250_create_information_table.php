<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('information', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // Judul atau nama informasi
            $table->text('description')->nullable(); // Deskripsi detail
            $table->string('type')->default('product'); // Jenis informasi, misalnya 'product', 'promo', dll

            // Rating dan review
            $table->float('rating', 2, 1)->default(0); // Rating rata-rata, misal 4.5
            $table->unsignedInteger('rating_count')->default(0); // Jumlah orang yang memberi rating

            // Informasi tambahan
            $table->string('image_url')->nullable(); // Gambar pendukung
            $table->boolean('is_active')->default(true); // Status aktif atau tidak
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('information');
    }
};

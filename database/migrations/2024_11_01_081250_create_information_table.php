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
            $table->string('image')->nullable(); // Kolom untuk menyimpan path atau URL gambar
            $table->string('email', 255)->unique(); // Kolom email dengan panjang maksimum 255 karakter
            $table->string('website_name', 100); // Nama website, panjang maksimal 100 karakter
            $table->string('phone_number', 20); // Nomor telepon, panjang maksimal 20 karakter
            $table->string('company_address', 255); // Alamat perusahaan
            $table->text('about_us'); // Deskripsi tentang perusahaan
            $table->timestamps(); // Kolom created_at dan updated_at
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

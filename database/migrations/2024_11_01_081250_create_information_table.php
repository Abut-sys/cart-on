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
            $table->string('image')->nullable(); // Kolom untuk gambar
            $table->string('email')->unique(); // Kolom untuk email
            $table->string('website_name'); // Nama website
            $table->string('phone_number'); // Nomor telepon
            $table->string('company_address'); // Alamat perusahaan
            $table->text('about_us'); // Deskripsi tentang perusahaan
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

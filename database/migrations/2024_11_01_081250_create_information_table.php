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
        $table->string('title')->default('Rating Web');
        $table->text('description')->nullable();
        $table->float('rating', 3, 2)->default(0);
        $table->unsignedInteger('rating_count')->default(0);
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

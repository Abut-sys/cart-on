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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
             $table->enum('type', ['percentage', 'fixed']); 
            $table->decimal('discount_value', 13, 0);
            $table->date('start_date');
            $table->date('end_date');
            $table->text('terms_and_conditions')->nullable();
            $table->integer('usage_limit');
            $table->integer('used_count')->default(0);
            $table->integer('max_per_user')->default(1);
            $table->enum('status', ['inactive', 'active']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};

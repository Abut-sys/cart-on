<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->dateTime('order_date');
            $table->string('unique_order_id')->unique();
            $table->string('address', 255);
            $table->string('courier');
            $table->string('shipping_service');
            $table->decimal('shipping_cost', 13, 0);
            $table->decimal('amount', 13, 0);
            $table->enum('payment_status', ['pending', 'completed', 'failed']);
            $table->enum('order_status', ['pending', 'shipped', 'delivered', 'canceled']);
            $table->timestamps();
        });
    }
    
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

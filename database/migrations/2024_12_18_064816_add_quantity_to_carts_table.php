<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::table('carts', function (Blueprint $table) {
        $table->integer('quantity')->default(1); // Default to 1 if no value exists
    });
}

public function down()
{
    Schema::table('carts', function (Blueprint $table) {
        $table->dropColumn('quantity');
    });
}

};

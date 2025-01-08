<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAddressIdToProfilesTable extends Migration
{
    public function up()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->unsignedBigInteger('address_id')->nullable()->after('user_id'); // Menambahkan kolom address_id
            $table->foreign('address_id')->references('id')->on('addresses')->onDelete('set null'); // Menambahkan relasi ke tabel addresses
        });
    }

    public function down()
    {
        Schema::table('profiles', function (Blueprint $table) {
            $table->dropForeign(['address_id']);
            $table->dropColumn('address_id');
        });
    }
}

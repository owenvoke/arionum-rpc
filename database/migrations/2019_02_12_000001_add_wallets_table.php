<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWalletsTable extends Migration
{
    public function up(): void
    {
        Schema::create('wallets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('address', 256);
            $table->string('public_key', 760);
            $table->string('private_key', 760);
            $table->integer('data')->default(0);
            $table->string('acc', 120)->default('0');
        });
    }

    public function down(): void
    {
        //
    }
}

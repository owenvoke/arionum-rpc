<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWalletConfigurationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_configurations', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->text('value');
        });
    }

    public function down(): void
    {
        //
    }
}

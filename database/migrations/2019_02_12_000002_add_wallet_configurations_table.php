<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AddWalletConfigurationsTable extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_configurations', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->text('value');
        });

        DB::table('wallet_configurations')->insert(['id' => 'expiry', 'value' => 0]);
        DB::table('wallet_configurations')->insert(['id' => 'iv', 'value' => '']);
        DB::table('wallet_configurations')->insert(['id' => 'private_key', 'value' => '']);
        DB::table('wallet_configurations')->insert(['id' => 'public_key', 'value' => '']);
    }

    public function down(): void
    {
        //
    }
}

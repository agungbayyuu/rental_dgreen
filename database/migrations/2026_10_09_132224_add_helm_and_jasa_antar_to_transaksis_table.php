<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->unsignedInteger('helm')->nullable()->default(null)->after('harga');
            $table->unsignedBigInteger('jasa_antar')->nullable()->default(null)->after('helm');
        });
    }

    public function down(): void
    {
        Schema::table('transaksis', function (Blueprint $table) {
            $table->dropColumn(['helm', 'jasa_antar']);
        });
    }
};
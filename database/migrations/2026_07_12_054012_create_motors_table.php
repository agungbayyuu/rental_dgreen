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
    Schema::create('motors', function (Blueprint $table) {
        $table->id();
        $table->string('nomor_polisi')->unique(); // catatan: "polosi" -> "polisi"
        $table->string('motor');
        // $table->decimal('harga_sewa_harian', 10, 2);
        $table->string('status')->default('Tersedia'); // misal: tersedia, disewa, servis
        // $table->text('catatan')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('motors');
    }
};

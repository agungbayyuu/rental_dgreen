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
        Schema::create('transaksis', function (Blueprint $table) {
            $table->id();
            $table->string('nama_customer');
            $table->string('no_whatsapp');
            $table->foreignId('motor_id')->constrained('motors')->cascadeOnDelete();
            $table->dateTime('tanggal_sewa');
            $table->dateTime('tanggal_kembali');
            $table->unsignedBigInteger('harga');
            $table->string('lokasi_antar')->nullable();
            $table->string('lokasi_ambil')->nullable();
            $table->enum('status', ['Dibooking', 'Berjalan', 'Selesai', 'Batal'])->default('Dibooking');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksis');
    }
};

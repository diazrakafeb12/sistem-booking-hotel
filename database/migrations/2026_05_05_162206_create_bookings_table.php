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
    Schema::create('bookings', function (Blueprint $table) {
        $table->id('id_booking');
        $table->string('kode_booking', 20)->unique();
        $table->foreignId('id_tamu')->constrained('tamu', 'id_tamu')->onDelete('restrict');
        $table->foreignId('id_kamar')->constrained('kamar', 'id_kamar')->onDelete('restrict');
        $table->date('tgl_checkin');
        $table->date('tgl_checkout');
        $table->integer('jumlah_malam');
        $table->decimal('total_biaya', 12, 2);
        $table->enum('status', ['pending','confirmed','checkin','checkout','cancelled'])->default('pending');
        $table->text('catatan')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

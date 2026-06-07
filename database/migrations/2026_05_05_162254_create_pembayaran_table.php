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
    Schema::create('pembayaran', function (Blueprint $table) {
        $table->id('id_pembayaran');
        $table->foreignId('id_booking')->constrained('bookings', 'id_booking')->onDelete('cascade');
        $table->decimal('jumlah_bayar', 12, 2);
        $table->enum('metode', ['cash', 'transfer', 'kartu'])->default('cash');
        $table->enum('status', ['lunas', 'belum_lunas', 'dp'])->default('belum_lunas');
        $table->dateTime('tgl_bayar')->useCurrent();
        $table->text('keterangan')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembayaran');
    }
};

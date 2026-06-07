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
    Schema::create('kamar', function (Blueprint $table) {
        $table->id('id_kamar');
        $table->string('nomor_kamar', 10)->unique();
        $table->foreignId('id_tipe')->constrained('tipe_kamar', 'id_tipe')->onDelete('restrict');
        $table->integer('lantai')->nullable();
        $table->integer('kapasitas')->default(2);
        $table->string('foto')->nullable();
        $table->enum('status', ['tersedia', 'terisi', 'maintenance'])->default('tersedia');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kamar');
    }
};

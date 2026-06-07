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
    Schema::create('tamu', function (Blueprint $table) {
        $table->id('id_tamu');
        $table->string('nama_lengkap', 150);
        $table->string('nik', 20)->unique()->nullable();
        $table->string('no_hp', 15)->nullable();
        $table->string('email', 100)->nullable();
        $table->text('alamat')->nullable();
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tamu');
    }
};

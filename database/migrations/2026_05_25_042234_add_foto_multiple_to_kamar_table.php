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
    Schema::table('kamar', function (Blueprint $table) {
        $table->string('foto_2')->nullable()->after('foto');
        $table->string('foto_3')->nullable()->after('foto_2');
    });
}

public function down(): void
{
    Schema::table('kamar', function (Blueprint $table) {
        $table->dropColumn(['foto_2', 'foto_3']);
    });
  }
};

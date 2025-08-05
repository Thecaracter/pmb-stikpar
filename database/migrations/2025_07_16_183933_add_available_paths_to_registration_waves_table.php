<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('registration_waves', function (Blueprint $table) {
            $table->json('available_paths')->nullable()->comment('Jalur pendaftaran yang tersedia: reguler, prestasi, kip');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('registration_waves', function (Blueprint $table) {
            $table->dropColumn('available_paths');
        });
    }
};

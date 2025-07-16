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
        Schema::create('registration_paths', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // regular, achievement, kip
            $table->string('code'); // REG, ACH, KIP
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique('code'); // Kode jalur harus unik
            $table->index('is_active'); // Index untuk filter jalur aktif
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_paths');
    }
};

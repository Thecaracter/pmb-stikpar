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
        Schema::create('graduation_proofs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');
            $table->string('proof_number')->unique();
            $table->date('issued_date');
            $table->string('proof_file');
            $table->boolean('is_sent')->default(false);
            $table->timestamp('sent_at')->nullable();
            $table->timestamps();

            $table->index('registration_id'); // Index untuk relasi
            $table->index('proof_number'); // Index untuk pencarian nomor bukti
            $table->index('is_sent'); // Index untuk filter status pengiriman
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('graduation_proofs');
    }
};

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
        Schema::create('document_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');
            $table->enum('document_type', [
                'payment_proof',
                'diploma',
                'family_card',
                'id_card',
                'photo',
                'kip_certificate',
                'poverty_certificate',
                'report_card',
                'achievement_certificate',
                'achievement_recommendation',
                'achievement_award',
                'achievement_documentation'
            ]);
            $table->string('file_name');
            $table->string('file_path');
            $table->string('mime_type');
            $table->integer('file_size');
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('verification_notes')->nullable();
            $table->timestamps();

            $table->index(['registration_id', 'document_type']); // Index untuk query dokumen per registrasi
            $table->index('verification_status'); // Index untuk filter status verifikasi
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('document_uploads');
    }
};

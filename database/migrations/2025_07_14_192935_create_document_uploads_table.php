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
                'photo',
                'family_card',
                'birth_certificate',
                'id_card',
                'diploma',
                'report_card',
                'baptism_certificate',
                'pastor_recommendation',
                'marriage_certificate',
                'kip_certificate',
                'poverty_certificate',
                'achievement_certificate',
                'achievement_recommendation',
                'achievement_award',
                'achievement_documentation'
            ]);
            $table->string('document_name');
            $table->string('file_name');
            $table->string('file_path');
            $table->string('mime_type');
            $table->integer('file_size');
            $table->boolean('is_required')->default(true);
            $table->enum('verification_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('verification_notes')->nullable();
            $table->timestamps();

            $table->index(['registration_id', 'document_type']);
            $table->index('verification_status');
            $table->unique(['registration_id', 'document_type']);
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
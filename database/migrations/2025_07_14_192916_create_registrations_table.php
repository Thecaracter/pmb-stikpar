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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            $table->string('registration_number')->unique();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('wave_id')->constrained('registration_waves')->onDelete('cascade');
            $table->foreignId('path_id')->constrained('registration_paths')->onDelete('cascade');
            $table->enum('status', [
                'pending',
                'waiting_payment',
                'waiting_documents',
                'waiting_decision',
                'passed',
                'failed',
                'waiting_final_payment',
                'completed',
                'rejected'
            ])->default('pending');
            $table->decimal('admin_fee_paid', 10, 2)->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->timestamp('document_submitted_at')->nullable();
            $table->timestamp('passed_at')->nullable();
            $table->text('failure_reason')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'wave_id']);
            $table->index('status');
            $table->index('registration_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};

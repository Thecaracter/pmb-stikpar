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
        Schema::create('registration_forms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('registration_id')->constrained()->onDelete('cascade');
            $table->string('full_name');
            $table->string('email');
            $table->string('phone_number');
            $table->date('birth_date');
            $table->string('birth_place');
            $table->enum('gender', ['male', 'female']);
            $table->text('address');
            $table->string('school_origin');
            $table->string('nisn')->nullable();
            $table->string('parent_name');
            $table->string('parent_phone');
            $table->string('parent_job');
            $table->decimal('parent_income', 12, 2)->nullable();
            // For achievement path:
            $table->string('achievement_type')->nullable();
            $table->string('achievement_level')->nullable();
            $table->integer('achievement_rank')->nullable();
            $table->string('achievement_organizer')->nullable();
            $table->date('achievement_date')->nullable();
            $table->timestamps();

            $table->index('registration_id');
            $table->index('email');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_forms');
    }
};

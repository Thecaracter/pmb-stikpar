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
        Schema::create('registration_waves', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('wave_number');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('administration_fee', 10, 2)->comment('Biaya administrasi pendaftaran');
            $table->decimal('registration_fee', 10, 2)->comment('Biaya daftar ulang');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['start_date', 'end_date']);
            $table->index('is_active');
            $table->index('wave_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registration_waves');
    }
};

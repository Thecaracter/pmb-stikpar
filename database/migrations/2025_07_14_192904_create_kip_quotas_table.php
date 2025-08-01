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
        Schema::create('kip_quotas', function (Blueprint $table) {
            $table->id();
            $table->integer('year');
            $table->integer('total_quota');
            $table->integer('remaining_quota');
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['year', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kip_quotas');
    }
};

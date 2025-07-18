<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Tambah field baru ke registration_forms table
        Schema::table('registration_forms', function (Blueprint $table) {
            // Tambah field agama dan nama paroki
            $table->string('religion')->after('gender')->comment('Agama');
            $table->string('parish_name')->after('email')->comment('Nama Paroki');

            // Tambah field data ibu
            $table->string('mother_name')->after('parent_job');
            $table->string('mother_job')->after('mother_name');

            // Untuk jalur prestasi - tambah nilai raport
            $table->decimal('grade_8_sem2', 4, 2)->nullable()->after('achievement_date')->comment('Nilai Raport Kelas 8 Semester Genap');
            $table->decimal('grade_9_sem1', 4, 2)->nullable()->after('grade_8_sem2')->comment('Nilai Raport Kelas 9 Semester Ganjil');

            // Status pengisian form
            $table->boolean('is_completed')->default(false)->after('grade_9_sem1');
            $table->timestamp('completed_at')->nullable()->after('is_completed');
        });

        // Tambah document types baru ke document_uploads table
        // Karena enum tidak bisa dimodifikasi langsung, kita gunakan raw SQL
        DB::statement("ALTER TABLE document_uploads MODIFY COLUMN document_type ENUM(
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
            'achievement_documentation',
            'birth_certificate',
            'baptism_certificate', 
            'pastor_recommendation',
            'marriage_certificate'
        )");

        // Tambah field baru ke document_uploads
        Schema::table('document_uploads', function (Blueprint $table) {
            $table->string('document_name')->nullable()->after('document_type')->comment('Nama dokumen yang user-friendly');
            $table->boolean('is_required')->default(true)->after('document_name')->comment('Apakah dokumen wajib');
            $table->text('rejection_reason')->nullable()->after('verification_notes')->comment('Alasan penolakan dokumen');
            $table->foreignId('verified_by')->nullable()->after('rejection_reason')->constrained('users')->comment('Admin yang memverifikasi');
            $table->timestamp('verified_at')->nullable()->after('verified_by')->comment('Waktu verifikasi');
            $table->text('notes')->nullable()->after('verified_at')->comment('Catatan tambahan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan perubahan di registration_forms
        Schema::table('registration_forms', function (Blueprint $table) {
            // Hapus kolom baru
            $table->dropColumn([
                'religion',
                'parish_name',
                'mother_name',
                'mother_job',
                'grade_8_sem2',
                'grade_9_sem1',
                'is_completed',
                'completed_at'
            ]);
        });

        // Kembalikan enum document_type ke semula
        DB::statement("ALTER TABLE document_uploads MODIFY COLUMN document_type ENUM(
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
        )");

        // Hapus kolom baru dari document_uploads
        Schema::table('document_uploads', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'document_name',
                'is_required',
                'rejection_reason',
                'verified_by',
                'verified_at',
                'notes'
            ]);
        });
    }
};
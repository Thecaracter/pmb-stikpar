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
            if (!Schema::hasColumn('registration_forms', 'religion')) {
                $table->string('religion')->after('gender')->comment('Agama');
            }
            if (!Schema::hasColumn('registration_forms', 'parish_name')) {
                $table->string('parish_name')->after('email')->comment('Nama Paroki');
            }

            // Tambah field data ibu
            if (!Schema::hasColumn('registration_forms', 'mother_name')) {
                $table->string('mother_name')->after('parent_job');
            }
            if (!Schema::hasColumn('registration_forms', 'mother_job')) {
                $table->string('mother_job')->after('mother_name');
            }

            // Untuk jalur prestasi - tambah nilai raport
            if (!Schema::hasColumn('registration_forms', 'grade_8_sem2')) {
                $table->decimal('grade_8_sem2', 4, 2)->nullable()->after('achievement_date')->comment('Nilai Raport Kelas 8 Semester Genap');
            }
            if (!Schema::hasColumn('registration_forms', 'grade_9_sem1')) {
                $table->decimal('grade_9_sem1', 4, 2)->nullable()->after('grade_8_sem2')->comment('Nilai Raport Kelas 9 Semester Ganjil');
            }

            // Status pengisian form
            if (!Schema::hasColumn('registration_forms', 'is_completed')) {
                $table->boolean('is_completed')->default(false)->after('grade_9_sem1');
            }
            if (!Schema::hasColumn('registration_forms', 'completed_at')) {
                $table->timestamp('completed_at')->nullable()->after('is_completed');
            }
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
            // Skip document_name karena sudah ada di create migration
            // $table->string('document_name')->nullable()->after('document_type')->comment('Nama dokumen yang user-friendly');

            // Skip is_required juga karena sudah ada
            // $table->boolean('is_required')->default(true)->after('document_name')->comment('Apakah dokumen wajib');

            if (!Schema::hasColumn('document_uploads', 'rejection_reason')) {
                $table->text('rejection_reason')->nullable()->after('verification_notes')->comment('Alasan penolakan dokumen');
            }
            if (!Schema::hasColumn('document_uploads', 'verified_by')) {
                $table->foreignId('verified_by')->nullable()->after('rejection_reason')->constrained('users')->comment('Admin yang memverifikasi');
            }
            if (!Schema::hasColumn('document_uploads', 'verified_at')) {
                $table->timestamp('verified_at')->nullable()->after('verified_by')->comment('Waktu verifikasi');
            }
            if (!Schema::hasColumn('document_uploads', 'notes')) {
                $table->text('notes')->nullable()->after('verified_at')->comment('Catatan tambahan');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Kembalikan perubahan di registration_forms
        Schema::table('registration_forms', function (Blueprint $table) {
            // Hapus kolom baru (cek dulu apakah ada)
            $columns_to_drop = [];
            if (Schema::hasColumn('registration_forms', 'religion'))
                $columns_to_drop[] = 'religion';
            if (Schema::hasColumn('registration_forms', 'parish_name'))
                $columns_to_drop[] = 'parish_name';
            if (Schema::hasColumn('registration_forms', 'mother_name'))
                $columns_to_drop[] = 'mother_name';
            if (Schema::hasColumn('registration_forms', 'mother_job'))
                $columns_to_drop[] = 'mother_job';
            if (Schema::hasColumn('registration_forms', 'grade_8_sem2'))
                $columns_to_drop[] = 'grade_8_sem2';
            if (Schema::hasColumn('registration_forms', 'grade_9_sem1'))
                $columns_to_drop[] = 'grade_9_sem1';
            if (Schema::hasColumn('registration_forms', 'is_completed'))
                $columns_to_drop[] = 'is_completed';
            if (Schema::hasColumn('registration_forms', 'completed_at'))
                $columns_to_drop[] = 'completed_at';

            if (!empty($columns_to_drop)) {
                $table->dropColumn($columns_to_drop);
            }
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
            if (Schema::hasColumn('document_uploads', 'verified_by')) {
                $table->dropForeign(['verified_by']);
            }

            $columns_to_drop = [];
            // Skip document_name dan is_required karena ada di create migration
            if (Schema::hasColumn('document_uploads', 'rejection_reason'))
                $columns_to_drop[] = 'rejection_reason';
            if (Schema::hasColumn('document_uploads', 'verified_by'))
                $columns_to_drop[] = 'verified_by';
            if (Schema::hasColumn('document_uploads', 'verified_at'))
                $columns_to_drop[] = 'verified_at';
            if (Schema::hasColumn('document_uploads', 'notes'))
                $columns_to_drop[] = 'notes';

            if (!empty($columns_to_drop)) {
                $table->dropColumn($columns_to_drop);
            }
        });
    }
};
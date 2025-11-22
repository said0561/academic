<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('class_subject', function (Blueprint $table) {

            // Ongeza teacher_user_id kama haipo
            if (!Schema::hasColumn('class_subject', 'teacher_user_id')) {
                $table->foreignId('teacher_user_id')
                      ->nullable()
                      ->after('subject_id')
                      ->constrained('users')
                      ->nullOnDelete();
            }
        });
    }

    public function down(): void
    {
        Schema::table('class_subject', function (Blueprint $table) {
            if (Schema::hasColumn('class_subject', 'teacher_user_id')) {
                $table->dropConstrainedForeignId('teacher_user_id');
            }
        });
    }
};

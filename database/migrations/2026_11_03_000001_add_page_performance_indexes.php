<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Index the filters and sort orders used by public lists and the admin dashboard.
     */
    public function up(): void
    {
        Schema::table('ebooks', function (Blueprint $table) {
            $table->index(['status', 'created_at'], 'ebooks_status_created_at_index');
            $table->index(['status', 'jumlah_unduhan'], 'ebooks_status_downloads_index');
        });

        Schema::table('informasi', function (Blueprint $table) {
            $table->index(['status', 'published_at'], 'informasi_status_published_at_index');
            $table->index(['status', 'created_at'], 'informasi_status_created_at_index');
        });

        Schema::table('permohonan_akses', function (Blueprint $table) {
            $table->index(['dibaca_at', 'created_at'], 'permohonan_read_created_at_index');
            $table->index(['status', 'created_at'], 'permohonan_status_created_at_index');
        });

        Schema::table('berkas_tokens', function (Blueprint $table) {
            $table->index('is_used', 'berkas_tokens_is_used_index');
        });
    }

    public function down(): void
    {
        Schema::table('ebooks', function (Blueprint $table) {
            $table->dropIndex('ebooks_status_created_at_index');
            $table->dropIndex('ebooks_status_downloads_index');
        });

        Schema::table('informasi', function (Blueprint $table) {
            $table->dropIndex('informasi_status_published_at_index');
            $table->dropIndex('informasi_status_created_at_index');
        });

        Schema::table('permohonan_akses', function (Blueprint $table) {
            $table->dropIndex('permohonan_read_created_at_index');
            $table->dropIndex('permohonan_status_created_at_index');
        });

        Schema::table('berkas_tokens', function (Blueprint $table) {
            $table->dropIndex('berkas_tokens_is_used_index');
        });
    }
};

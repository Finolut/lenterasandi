<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop check constraint from PostgreSQL if present
        DB::statement("ALTER TABLE informasi DROP CONSTRAINT IF EXISTS informasi_kategori_check");

        Schema::table('informasi', function (Blueprint $table) {
            $table->string('kategori', 255)->default('Informasi')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('informasi', function (Blueprint $table) {
            $table->string('file_pendukung_url')->nullable()->after('gambar_url');
            $table->string('file_pendukung_nama')->nullable()->after('file_pendukung_url');
            $table->string('file_pendukung_ukuran')->nullable()->after('file_pendukung_nama');
            $table->string('file_pendukung_tipe', 20)->nullable()->after('file_pendukung_ukuran');
            $table->string('akses_file', 30)->default('Publik')->after('file_pendukung_tipe'); // 'Publik' or 'Perlu Ajukan'
        });

        Schema::create('permohonan_akses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('informasi_id')->constrained('informasi')->cascadeOnDelete();
            $table->string('nama_lengkap');
            $table->string('instansi');
            $table->string('email');
            $table->string('telepon')->nullable();
            $table->text('keperluan')->nullable();
            $table->string('status', 30)->default('Menunggu'); // 'Menunggu', 'Disetujui', 'Ditolak'
            $table->text('catatan_admin')->nullable();
            $table->timestamp('dibaca_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permohonan_akses');

        Schema::table('informasi', function (Blueprint $table) {
            $table->dropColumn([
                'file_pendukung_url',
                'file_pendukung_nama',
                'file_pendukung_ukuran',
                'file_pendukung_tipe',
                'akses_file',
            ]);
        });
    }
};

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
        Schema::create('informasi', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('slug')->unique();
            $table->longText('konten');
            $table->text('ringkasan')->nullable();          // meta description / excerpt
            $table->string('gambar_url')->nullable();       // thumbnail / featured image
            $table->enum('kategori', ['Pengumuman', 'Kegiatan', 'Informasi'])->default('Informasi');
            $table->enum('status', ['Publik', 'Draf', 'Disembunyikan'])->default('Draf');
            $table->timestamp('published_at')->nullable();
            $table->unsignedInteger('jumlah_dilihat')->default(0);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('informasi');
    }
};

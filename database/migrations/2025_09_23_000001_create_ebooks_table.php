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
        Schema::create('ebooks', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('kategori');
            $table->text('deskripsi')->nullable();
            $table->string('pengarang')->default('Diskominfo Provinsi Jawa Timur');
            $table->string('penerbit')->nullable();
            $table->year('tahun')->nullable();
            $table->string('file_url')->nullable();        // path ke file PDF
            $table->string('cover_url')->nullable();       // path ke gambar cover
            $table->unsignedBigInteger('ukuran_file')->nullable(); // ukuran dalam bytes
            $table->unsignedInteger('jumlah_unduhan')->default(0);
            $table->enum('status', ['Publik', 'Draf', 'Disembunyikan'])->default('Draf');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ebooks');
    }
};

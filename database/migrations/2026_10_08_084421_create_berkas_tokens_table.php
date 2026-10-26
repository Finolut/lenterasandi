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
        Schema::create('berkas_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('informasi_id')->constrained('informasi')->cascadeOnDelete();
            $table->string('token', 32)->unique();
            $table->boolean('is_used')->default(false);
            $table->timestamp('used_at')->nullable();
            $table->string('used_by_ip', 45)->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->string('catatan', 255)->nullable();
            $table->foreignId('permohonan_id')->nullable()->constrained('permohonan_akses')->nullOnDelete();
            $table->timestamps();

            $table->index(['informasi_id', 'is_used']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('berkas_tokens');
    }
};

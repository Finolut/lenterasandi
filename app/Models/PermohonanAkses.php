<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int         $id
 * @property int         $informasi_id
 * @property string      $nama_lengkap
 * @property string      $instansi
 * @property string      $email
 * @property string|null $telepon
 * @property string|null $keperluan
 * @property string      $status
 * @property string|null $catatan_admin
 * @property Carbon|null $dibaca_at
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class PermohonanAkses extends Model
{
    use HasFactory;

    protected $table = 'permohonan_akses';

    protected $fillable = [
        'informasi_id',
        'nama_lengkap',
        'instansi',
        'email',
        'telepon',
        'keperluan',
        'status',
        'catatan_admin',
        'dibaca_at',
    ];

    protected $casts = [
        'dibaca_at' => 'datetime',
    ];

    public const STATUSES = ['Menunggu', 'Disetujui', 'Ditolak'];

    protected static function booted(): void
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('admin_sidebar_counts');
            \Illuminate\Support\Facades\Cache::forget('admin_dashboard_kpis');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('admin_sidebar_counts');
            \Illuminate\Support\Facades\Cache::forget('admin_dashboard_kpis');
        });
    }

    public function informasi(): BelongsTo
    {
        return $this->belongsTo(Informasi::class, 'informasi_id');
    }

    public function tokens(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BerkasToken::class, 'permohonan_id');
    }

    public function scopeUnread($query)
    {
        return $query->whereNull('dibaca_at');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'Menunggu');
    }

    public function getTanggalPengajuanAttribute(): string
    {
        return $this->created_at ? $this->created_at->locale('id')->translatedFormat('d M Y, H:i') . ' WIB' : '—';
    }

    /**
     * Konversi nomor telepon lokal ke format WhatsApp internasional (62xxx)
     */
    public function getFormattedWaNumberAttribute(): ?string
    {
        if (!$this->telepon) return null;

        $number = preg_replace('/[^0-9]/', '', $this->telepon);
        if (empty($number)) return null;

        if (str_starts_with($number, '0')) {
            $number = '62' . substr($number, 1);
        } elseif (str_starts_with($number, '8')) {
            $number = '62' . $number;
        }

        return $number;
    }

    /**
     * Tautan resmi WhatsApp dengan teks template token siap kirim
     */
    public function getWhatsappShareUrl(?string $tokenCode = null): ?string
    {
        $wa = $this->formatted_wa_number;
        if (!$wa) return null;

        $token = $tokenCode;
        if (!$token) {
            $token = $this->tokens->where('is_used', false)->last()?->token
                ?? $this->tokens->last()?->token
                ?? 'LS-XXXX-XXXX';
        }

        $nama = $this->nama_lengkap;
        $judul = $this->informasi->judul ?? 'Lentera Sandi';
        $berkas = $this->informasi->file_pendukung_nama ?? 'Berkas Pendukung';
        $url = url('/informasi/' . $this->informasi_id);

        $text = "Halo *{$nama}*,\n\n"
              . "Permohonan akses berkas Anda di portal *Lentera Sandi* telah *DISETUJUI* oleh Admin.\n\n"
              . "📄 *Dokumen:* {$judul}\n"
              . "📁 *Berkas:* {$berkas}\n\n"
              . "🔑 *KODE TOKEN UNDUH ANDA:*\n"
              . "*{$token}*\n\n"
              . "📌 *Petunjuk Mengunduh:*\n"
              . "1. Buka tautan artikel: {$url}\n"
              . "2. Klik tombol *'Masukkan Token'* pada berkas pendukung.\n"
              . "3. Masukkan kode token: *{$token}*\n"
              . "4. Berkas akan otomatis terunduh ke perangkat Anda.\n\n"
              . "_(Catatan: Token ini hanya berlaku 1 kali unduh)_\n\n"
              . "Terima kasih,\n"
              . "*Tim Lentera Sandi - Diskominfo Prov. Jatim*";

        return "https://wa.me/{$wa}?text=" . rawurlencode($text);
    }
}

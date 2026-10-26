<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int         $id
 * @property string      $judul
 * @property string      $slug
 * @property string      $konten
 * @property string|null $ringkasan
 * @property string|null $gambar_url
 * @property string      $kategori
 * @property string      $status
 * @property Carbon|null $published_at
 * @property int         $jumlah_dilihat
 * @property int|null    $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Informasi extends Model
{
    use HasFactory;

    protected $table = 'informasi';

    protected $fillable = [
        'judul',
        'slug',
        'konten',
        'ringkasan',
        'gambar_url',
        'file_pendukung_url',
        'file_pendukung_nama',
        'file_pendukung_ukuran',
        'file_pendukung_tipe',
        'akses_file',
        'kategori',
        'status',
        'published_at',
        'jumlah_dilihat',
        'created_by',
    ];

    protected $casts = [
        'published_at'  => 'datetime',
        'jumlah_dilihat'=> 'integer',
    ];

    public const CATEGORIES = ['Pengumuman', 'Kegiatan', 'Informasi'];
    public const STATUSES   = ['Publik', 'Draf', 'Disembunyikan'];
    public const AKSES_FILE = ['Publik', 'Perlu Ajukan'];

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

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function permohonanAkses(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PermohonanAkses::class, 'informasi_id');
    }

    public function tokens(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(BerkasToken::class, 'informasi_id');
    }

    public function hasFilePendukung(): bool
    {
        return !empty($this->file_pendukung_url);
    }

    public function filePendukungIsPublik(): bool
    {
        return $this->hasFilePendukung() && ($this->akses_file === 'Publik' || empty($this->akses_file));
    }

    public function filePendukungNeedsApproval(): bool
    {
        return $this->hasFilePendukung() && $this->akses_file === 'Perlu Ajukan';
    }

    /** Daftar kategori dalam bentuk array */
    public function getKategoriListAttribute(): array
    {
        if (empty($this->kategori)) {
            return [];
        }

        $items = array_map('trim', explode(',', $this->kategori));
        return array_values(array_filter($items, fn($c) => $c !== ''));
    }

    /** Tanggal publish dalam format Indonesia */
    public function getTanggalPublishAttribute(): string
    {
        $date = $this->published_at ?? $this->created_at;
        return $date ? $date->locale('id')->translatedFormat('d M Y') : '—';
    }

    /** URL gambar cover / thumbnail artikel */
    public function getGambarAttribute(): ?string
    {
        if (empty($this->gambar_url)) {
            return null;
        }

        if (str_starts_with($this->gambar_url, 'http://') || str_starts_with($this->gambar_url, 'https://')) {
            return $this->gambar_url;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->gambar_url);
    }
}

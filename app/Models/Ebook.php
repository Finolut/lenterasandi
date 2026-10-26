<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int         $id
 * @property string      $judul
 * @property string      $kategori
 * @property string|null $deskripsi
 * @property string      $pengarang
 * @property string|null $penerbit
 * @property int|null    $tahun
 * @property string|null $file_url
 * @property string|null $cover_url
 * @property int|null    $ukuran_file
 * @property int         $jumlah_unduhan
 * @property string      $status
 * @property int|null    $created_by
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Ebook extends Model
{
    use HasFactory;

    protected $table = 'ebooks';

    protected $fillable = [
        'judul',
        'kategori',
        'deskripsi',
        'pengarang',
        'penerbit',
        'tahun',
        'file_url',
        'cover_url',
        'ukuran_file',
        'jumlah_unduhan',
        'status',
        'created_by',
    ];

    protected $casts = [
        'tahun'          => 'integer',
        'ukuran_file'    => 'integer',
        'jumlah_unduhan' => 'integer',
    ];

    /** Kategori yang tersedia */
    public const CATEGORIES = [
        'Keamanan Informasi',
        'Keamanan Siber',
        'Literasi Digital',
    ];

    public const STATUSES = ['Publik', 'Draf', 'Disembunyikan'];

    protected static function booted(): void
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('home_total_ebooks');
            \Illuminate\Support\Facades\Cache::forget('home_total_downloads');
            \Illuminate\Support\Facades\Cache::forget('ebook_total_count');
            \Illuminate\Support\Facades\Cache::forget('admin_sidebar_counts');
            \Illuminate\Support\Facades\Cache::forget('admin_dashboard_kpis');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('home_total_ebooks');
            \Illuminate\Support\Facades\Cache::forget('home_total_downloads');
            \Illuminate\Support\Facades\Cache::forget('ebook_total_count');
            \Illuminate\Support\Facades\Cache::forget('admin_sidebar_counts');
            \Illuminate\Support\Facades\Cache::forget('admin_dashboard_kpis');
        });
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /** Ukuran file dalam format human-readable */
    public function getUkuranFormatAttribute(): string
    {
        if (! $this->ukuran_file) {
            return '—';
        }
        $units = ['B', 'KB', 'MB', 'GB'];
        $size  = $this->ukuran_file;
        $i     = 0;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }
        return round($size, 1).' '.$units[$i];
    }

    /** Tanggal upload dalam format Indonesia */
    public function getTanggalUploadAttribute(): string
    {
        return $this->created_at
            ? $this->created_at->locale('id')->translatedFormat('d M Y')
            : '—';
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

    /** URL gambar cover e-book */
    public function getCoverImageAttribute(): ?string
    {
        if (empty($this->cover_url)) {
            return null;
        }

        if (str_starts_with($this->cover_url, 'http://') || str_starts_with($this->cover_url, 'https://')) {
            return $this->cover_url;
        }

        return \Illuminate\Support\Facades\Storage::disk('public')->url($this->cover_url);
    }
}

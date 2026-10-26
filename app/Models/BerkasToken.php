<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

/**
 * @property int         $id
 * @property int         $informasi_id
 * @property string      $token
 * @property bool        $is_used
 * @property Carbon|null $used_at
 * @property string|null $used_by_ip
 * @property int|null    $created_by
 * @property string|null $catatan
 * @property int|null    $permohonan_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class BerkasToken extends Model
{
    use HasFactory;

    protected $table = 'berkas_tokens';

    protected $fillable = [
        'informasi_id',
        'token',
        'is_used',
        'used_at',
        'used_by_ip',
        'created_by',
        'catatan',
        'permohonan_id',
        'email_sent_at',
    ];

    protected $casts = [
        'is_used'       => 'boolean',
        'used_at'       => 'datetime',
        'email_sent_at' => 'datetime',
    ];

    public function informasi(): BelongsTo
    {
        return $this->belongsTo(Informasi::class, 'informasi_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(PermohonanAkses::class, 'permohonan_id');
    }

    /**
     * Generate token unik yang mudah dibaca & disalin:
     * Format: LS-XXXX-XXXX (contoh: LS-7K9A-4M2P)
     */
    public static function generateUniqueToken(): string
    {
        $characters = '23456789ABCDEFGHJKLMNPQRSTUVWXYZ'; // Tanpa 0, 1, I, O agar tidak membingungkan
        
        do {
            $part1 = '';
            $part2 = '';
            for ($i = 0; $i < 4; $i++) {
                $part1 .= $characters[random_int(0, strlen($characters) - 1)];
                $part2 .= $characters[random_int(0, strlen($characters) - 1)];
            }
            $token = 'LS-' . $part1 . '-' . $part2;
        } while (static::where('token', $token)->exists());

        return $token;
    }

    /**
     * Tandai token telah digunakan (sekali pakai)
     */
    public function markAsUsed(?string $ip = null): void
    {
        $this->update([
            'is_used'    => true,
            'used_at'    => now(),
            'used_by_ip' => $ip,
        ]);
    }
}

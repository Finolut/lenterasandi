<?php

namespace App\Console\Commands;

use App\Services\JatimprovArticleService;
use Illuminate\Console\Command;

class SyncJatimprovArticles extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'artikel:sync-jatimprov {--limit=10 : Jumlah maksimal artikel yang diambil}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi otomatis artikel berita terbaru dari portal resmi Diskominfo Provinsi Jawa Timur';

    /**
     * Execute the console command.
     */
    public function handle(JatimprovArticleService $service): int
    {
        $limit = (int) $this->option('limit');
        $this->info("Memulai sinkronisasi artikel dari Diskominfo Jawa Timur (Maks: {$limit} artikel)...");

        $result = $service->sync($limit);

        if (! ($result['success'] ?? false)) {
            $this->error($result['message'] ?? 'Terjadi kesalahan saat sinkronisasi.');
            return self::FAILURE;
        }

        $this->info("Sinkronisasi selesai!");
        $this->line("- Total dipindai : " . $result['total_scanned']);
        $this->line("- Artikel baru   : " . $result['imported']);
        $this->line("- Dilewati (ada) : " . $result['skipped']);

        if (! empty($result['articles'])) {
            $this->newLine();
            $this->info("Daftar artikel yang berhasil diimpor:");
            foreach ($result['articles'] as $art) {
                $this->line("  [#{$art['id']}] [{$art['kategori']}] {$art['judul']}");
            }
        }

        return self::SUCCESS;
    }
}


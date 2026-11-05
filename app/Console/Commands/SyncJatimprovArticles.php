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
    protected $signature = 'artikel:sync-jatimprov 
                            {--limit=25 : Jumlah maksimal artikel baru yang diambil}
                            {--pages=5 : Jumlah halaman paginasi yang dipindai}
                            {--start-page=1 : Mulai dari halaman berapa}';

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
        $pages = (int) $this->option('pages');
        $startPage = (int) $this->option('start-page');

        $this->info("Memulai sinkronisasi artikel dari Diskominfo Jawa Timur...");
        $this->line("Target: Maks {$limit} artikel baru, memindai {$pages} halaman mulai dari halaman {$startPage}.");

        $result = $service->sync(limit: $limit, maxPages: $pages, startPage: $startPage);

        if (! ($result['success'] ?? false)) {
            $this->error($result['message'] ?? 'Terjadi kesalahan saat sinkronisasi.');
            return self::FAILURE;
        }

        $this->info("Sinkronisasi selesai!");
        $this->line("- Halaman dipindai: " . ($result['pages_scanned'] ?? 0));
        $this->line("- Total artikel   : " . $result['total_scanned']);
        $this->line("- Artikel baru    : " . $result['imported']);
        $this->line("- Dilewati (ada)  : " . $result['skipped']);

        if (! empty($result['articles'])) {
            $this->newLine();
            $this->info("Daftar artikel yang berhasil diimpor:");
            foreach ($result['articles'] as $art) {
                $hal = isset($art['halaman']) ? " [Hal {$art['halaman']}]" : '';
                $this->line("  [#{$art['id']}]{$hal} [{$art['kategori']}] {$art['judul']}");
            }
        }

        return self::SUCCESS;
    }
}


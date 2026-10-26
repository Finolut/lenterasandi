<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class MigrateStorageToSupabase extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'supabase:migrate-storage {--force : Lewati konfirmasi}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrasikan semua file lokal di storage/app/public ke Supabase Storage (S3)';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('=== Migrasi File Lokal ke Supabase Storage ===');

        $s3Key = config('filesystems.disks.s3.key');
        $s3Secret = config('filesystems.disks.s3.secret');
        $bucket = config('filesystems.disks.s3.bucket');
        $endpoint = config('filesystems.disks.s3.endpoint');

        if (empty($s3Key) || empty($s3Secret) || empty($endpoint)) {
            $this->error('Kredensial Supabase Storage (S3) belum lengkap di file .env!');
            $this->line('Silakan isi variabel berikut di file .env Anda:');
            $this->line('  AWS_ACCESS_KEY_ID=<Access Key ID dari Supabase>');
            $this->line('  AWS_SECRET_ACCESS_KEY=<Secret Access Key dari Supabase>');
            $this->line('  AWS_DEFAULT_REGION=ap-southeast-1');
            $this->line('  AWS_BUCKET=lenterasandi');
            $this->line('  AWS_ENDPOINT=https://zqkftcrmochjsszttcxr.supabase.co/storage/v1/s3');
            $this->line('  AWS_URL=https://zqkftcrmochjsszttcxr.supabase.co/storage/v1/object/public/lenterasandi');
            $this->line('  AWS_USE_PATH_STYLE_ENDPOINT=true');
            return Command::FAILURE;
        }

        $localPublicPath = storage_path('app/public');
        if (!File::exists($localPublicPath)) {
            $this->warn("Direktori {$localPublicPath} tidak ditemukan.");
            return Command::SUCCESS;
        }

        $allFiles = File::allFiles($localPublicPath);

        // Filter out .gitignore
        $filesToUpload = array_values(array_filter($allFiles, function ($file) {
            return $file->getFilename() !== '.gitignore';
        }));

        $totalCount = count($filesToUpload);

        if ($totalCount === 0) {
            $this->info('Tidak ada file lokal di storage/app/public untuk dimigrasi.');
            return Command::SUCCESS;
        }

        $this->line("Ditemukan <fg=yellow>{$totalCount}</> file di local public storage.");

        if (!$this->option('force') && !$this->confirm("Mulai upload {$totalCount} file ke bucket Supabase '{$bucket}'?")) {
            $this->line('Dibatalkan.');
            return Command::SUCCESS;
        }

        $bar = $this->output->createProgressBar($totalCount);
        $bar->start();

        $successCount = 0;
        $failCount = 0;

        foreach ($filesToUpload as $file) {
            $relativePath = str_replace('\\', '/', $file->getRelativePathname());

            try {
                $stream = fopen($file->getRealPath(), 'r+');
                $uploaded = Storage::disk('s3')->put($relativePath, $stream, 'public');
                if (is_resource($stream)) {
                    fclose($stream);
                }

                if ($uploaded) {
                    $successCount++;
                } else {
                    $failCount++;
                }
            } catch (\Throwable $e) {
                $failCount++;
                $this->error("\nGagal mengupload {$relativePath}: " . $e->getMessage());
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Migrasi Selesai!");
        $this->line("  - Berhasil diupload : <fg=green>{$successCount}</> file");
        if ($failCount > 0) {
            $this->line("  - Gagal diupload    : <fg=red>{$failCount}</> file");
        }

        $this->line("\nFile sekarang sudah aman tersimpan di Supabase Storage.");
        $this->line("Pastikan di file .env Anda sudah mengatur: <fg=cyan>FILESYSTEM_DISK=s3</>");

        return Command::SUCCESS;
    }
}
